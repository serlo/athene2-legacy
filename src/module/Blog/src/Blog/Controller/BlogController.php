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

use Blog\Form\CreatePostForm;
use Blog\Form\UpdatePostForm;
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

    /**
     * @var CreatePostForm
     */
    protected $createPostForm;

    /**
     * @var UpdatePostForm
     */
    protected $updatePostForm;

    public function __construct(
        BlogManagerInterface $blogManager,
        InstanceManagerInterface $instanceManager,
        UserManagerInterface $userManager,
        CreatePostForm $createPostForm,
        UpdatePostForm $updatePostForm
    ) {
        $this->blogManager     = $blogManager;
        $this->instanceManager = $instanceManager;
        $this->userManager     = $userManager;
        $this->createPostForm  = $createPostForm;
        $this->updatePostForm  = $updatePostForm;
    }

    public function createAction()
    {
        $blog     = $this->getBlogManager()->getBlog($this->params('id'));
        $identity = $this->getUserManager()->getUserFromAuthenticator();
        $this->assertGranted('blog.post.create', $blog);

        $form = $this->createPostForm;

        if ($this->getRequest()->isPost()) {
            $data = array_merge(
                $this->params()->fromPost(),
                [
                    'blog'     => $blog,
                    'author'   => $identity,
                    'instance' => $blog->getInstance()
                ]
            );
            $form->setData($data);

            if ($this->getBlogManager()->createPost($form) !== false) {
                $this->getBlogManager()->flush();
                $this->redirect()->toRoute('blog/view', ['id' => $this->params('id')]);
            }
        }

        $view = new ViewModel([
            'blog' => $blog,
            'form' => $form
        ]);

        $view->setTemplate('blog/blog/post/create');
        $this->layout('athene2-editor');

        return $view;
    }

    public function indexAction()
    {
        $instance = $this->getInstanceManager()->getInstanceFromRequest();
        $blogs    = $this->getBlogManager()->findAllBlogs($instance);
        $view     = new ViewModel(['blogs' => $blogs]);
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
        $form = $this->updatePostForm;

        $this->assertGranted('blog.post.update', $post);
        $form->bind($post);

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getBlogManager()->updatePost($form);
                $this->getBlogManager()->flush();
                $this->redirect()->toRoute('blog/post/view', ['post' => $this->params('post')]);
            }
        }

        $view = new ViewModel(['post' => $post, 'form' => $form]);
        $view->setTemplate('blog/blog/post/update');
        $this->layout('athene2-editor');

        return $view;
    }

    public function viewAction()
    {
        $blog = $this->getBlogManager()->getBlog($this->params('id'));
        $this->assertGranted('blog.view', $blog);

        $posts = $blog->getAssociated('blogPosts')->filter(
            function ($e) {
                return $e->isPublished() && !$e->isTrashed();
            }
        );

        $view = new ViewModel([
            'blog'  => $blog,
            'posts' => $posts
        ]);

        $view->setTemplate('blog/blog/view');

        return $view;
    }

    public function viewAllAction()
    {
        $blog = $this->getBlogManager()->getBlog($this->params('id'));
        $this->assertGranted('blog.posts.view.unpublished', $blog);

        $posts = $blog->getAssociated('blogPosts')->filter(
            function ($e) {
                return !$e->isPublished() && !$e->isTrashed();
            }
        );

        $view = new ViewModel([
            'blog'  => $blog,
            'posts' => $posts
        ]);

        $view->setTemplate('blog/blog/view-all');

        return $view;
    }

    public function viewPostAction()
    {
        $post = $this->getBlogManager()->getPost($this->params('post'));

        $view = new ViewModel([
            'blog' => $post->getBlog(),
            'post' => $post
        ]);

        $view->setTemplate('blog/blog/post/view');

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
}
