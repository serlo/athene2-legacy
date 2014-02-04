<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright   Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Blog\Controller;

use Blog\Form\PostForm;
use Blog\Manager\BlogManagerAwareTrait;
use Blog\Manager\BlogManagerInterface;
use DateTime;
use Instance\Manager\InstanceManagerAwareTrait;
use Instance\Manager\InstanceManagerInterface;
use User\Manager\UserManagerAwareTrait;
use User\Manager\UserManagerInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class BlogController extends AbstractActionController
{
    use BlogManagerAwareTrait, UserManagerAwareTrait;
    use InstanceManagerAwareTrait;

    public function __construct(
        BlogManagerInterface $blogManager,
        InstanceManagerInterface $instanceManager,
        UserManagerInterface $userManager,
        PostForm $postForm
    ) {
        $this->blogManager     = $blogManager;
        $this->instanceManager = $instanceManager;
        $this->userManager     = $userManager;
        $this->postForm        = $postForm;
    }

    public function createAction()
    {
        $blog     = $this->getBlogManager()->getBlog($this->params('id'));
        $identity = $this->getUserManager()->getUserFromAuthenticator();
        $instance = $this->getInstanceManager()->getInstanceFromRequest();
        //$this->assertGranted('blog.post.create', $blog);

        $form = $this->postForm;

        if ($this->getRequest()->isPost()) {
            $data             = $this->params()->fromPost();
            $data['blog']     = $blog;
            $data['author']   = $identity;
            $data['instance'] = $instance;

            $form->setData($data);

            if ($form->isValid()) {
                $data = $form->getData();

                $this->getBlogManager()->createPost($form);
                $this->getBlogManager()->flush();
                $this->redirect()->toRoute('blog/view', ['id' => $this->params('id')]);
            }
        }

        $view = new ViewModel(array(
            'blog' => $blog,
            'form' => $form
        ));

        $view->setTemplate('blog/blog/post/create');
        $this->layout('athene2-editor');

        return $view;
    }

    public function indexAction()
    {
        $blogs = $this->getBlogManager()->findAllBlogs(
            $this->getInstanceManager()->getInstanceFromRequest()
        );

        $view = new ViewModel(array(
            'blogs' => $blogs
        ));

        $view->setTemplate('blog/blog/blogs');

        return $view;
    }

    public function trashAction()
    {
        $post = $this->getBlogManager()->getPost($this->params('post'));
        $this->assertGranted('blog.post.trash', $post);

        $this->getBlogManager()->trashPost($this->params('post'));
        $this->getBlogManager()->flush();
        $this->redirect()->toReferer();

        return false;
    }

    public function updateAction()
    {
        $post = $this->getBlogManager()->getPost($this->params('post'));
        $this->assertGranted('blog.post.update', $post);

        $form = new PostForm();

        $form->setData(
            array(
                'title'   => $post->getTitle(),
                'content' => $post->getContent(),
                'publish' => $post->getPublish()->format('d.m.Y')
            )
        );

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);
            if ($form->isValid()) {
                $data    = $form->getData();
                $publish = $this->toDateTime($data['publish']);

                $this->getBlogManager()->updatePost($post->getId(), $data['title'], $data['content'], $publish);
                $this->getBlogManager()->flush();

                $this->redirect()->toRoute(
                    'blog/post/view',
                    array(
                        'post' => $this->params('post')
                    )
                );
            }
        }

        $view = new ViewModel(array(
            'post' => $post,
            'form' => $form
        ));

        $view->setTemplate('blog/blog/post/update');
        $this->layout('athene2-editor');

        return $view;
    }

    protected function toDateTime($publish = null)
    {
        if ($publish) {
            $dateData = explode('.', $publish);

            return (new Datetime())->setDate($dateData[2], $dateData[1], $dateData[0])->setTime(0, 0, 0);
        } else {
            return new DateTime();
        }
    }

    public function viewAction()
    {
        $blog  = $this->getBlogManager()->getBlog($this->params('id'));
        $posts = $blog->getAssociated('blogPosts')->filter(
            function ($e) {
                return $e->isPublished() && !$e->isTrashed();
            }
        );

        $view = new ViewModel(array(
            'blog'  => $blog,
            'posts' => $posts
        ));

        $view->setTemplate('blog/blog/view');

        return $view;
    }

    public function viewAllAction()
    {
        $blog = $this->getBlogManager()->getBlog($this->params('id'));
        $this->assertGranted('blog.posts.view_all', $blog);

        $posts = $blog->getAssociated('blogPosts')->filter(
            function ($e) {
                return !$e->isPublished() && !$e->isTrashed();
            }
        );

        $view = new ViewModel(array(
            'blog'  => $blog,
            'posts' => $posts
        ));

        $view->setTemplate('blog/blog/view-all');

        return $view;
    }

    public function viewPostAction()
    {
        $post = $this->getBlogManager()->getPost($this->params('post'));

        $view = new ViewModel(array(
            'blog' => $post->getBlog(),
            'post' => $post
        ));

        $view->setTemplate('blog/blog/post/view');

        return $view;
    }
}
