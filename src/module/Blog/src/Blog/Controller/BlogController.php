<?php
/**
 * 
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author	Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license	LGPL-3.0
 * @license	http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link		https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Blog\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Blog\Form\PostForm;

class BlogController extends AbstractActionController
{
    use\Blog\Manager\BlogManagerAwareTrait,\User\Manager\UserManagerAwareTrait,\Language\Manager\LanguageManagerAwareTrait;

    public function indexAction()
    {
        $blogs = $this->getBlogManager()->findAllBlogs($this->getLanguageManager()
            ->getLanguageFromRequest());
        $view = new ViewModel(array(
            'blogs' => $blogs
        ));
        $view->setTemplate('blog/blog/blogs');
        return $view;
    }

    public function viewPostAction()
    {
        $blog = $this->getBlogManager()->getBlog($this->params('blog'));
        $post = $blog->getPost($this->params('post'));
        $view = new ViewModel(array(
            'blog' => $blog,
            'post' => $post
        ));
        $view->setTemplate('blog/blog/post/view');
        return $view;
    }

    public function viewAction()
    {
        $blog = $this->getBlogManager()->getBlog($this->params('id'));
        $view = new ViewModel(array(
            'blog' => $blog,
            'posts' => $blog->findAllPosts()->filter(function ($e)
            {
                return ! $e->isTrashed();
            })
        ));
        $view->setTemplate('blog/blog/view');
        return $view;
    }

    public function trashAction()
    {
        $blog = $this->getBlogManager()->getBlog($this->params('blog'));
        $post = $blog->getPost($this->params('post'));
        $post->setTrashed(true);
        $post->getObjectManager()->flush();
        $this->redirect()->toReferer();
        return '';
    }

    public function updateAction()
    {
        $blog = $this->getBlogManager()->getBlog($this->params('blog'));
        $post = $blog->getPost($this->params('post'));
        
        $form = new PostForm();
        $form->setAttribute('action', $this->url()
            ->fromRoute('blog/post/update', array(
            'blog' => $this->params('blog'),
            'post' => $this->params('post')
        )));
        
        $form->setData(array(
            'title' => $post->getTitle(),
            'content' => $post->getContent()
        ));
        
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);
            if ($form->isValid()) {
                $author = $this->getUserManager()->getUserFromAuthenticator();
                $data = $form->getData();
                $title = $data['title'];
                $content = $data['content'];
                $language = $this->getLanguageManager()->getLanguageFromRequest();
                $blog->updatePost($post->getId(), $title, $content);
                
                $this->getEventManager()->trigger('post.update', $this, array(
                    'blog' => $blog,
                    'post' => $post,
                    'actor' => $author,
                    'post' => $data,
                    'language' => $language
                ));
                
                $blog->getObjectManager()->flush();
                
                $this->redirect()->toRoute('blog/view', array(
                    'id' => $this->params('blog')
                ));
            }
        }
        
        $view = new ViewModel(array(
            'blog' => $blog,
            'post' => $post,
            'form' => $form
        ));
        
        $view->setTemplate('blog/blog/post/update');
        
        return $view;
    }

    public function createAction()
    {
        $blog = $this->getBlogManager()->getBlog($this->params('id'));
        
        $form = new PostForm();
        $form->setAttribute('action', $this->url()
            ->fromRoute('blog/post/create', array(
            'id' => $this->params('id')
        )));
        
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);
            if ($form->isValid()) {
                $author = $this->getUserManager()->getUserFromAuthenticator();
                $data = $form->getData();
                $title = $data['title'];
                $content = $data['content'];
                $publish = null; // $data['publish'];
                $language = $this->getLanguageManager()->getLanguageFromRequest();
                $post = $blog->createPost($author, $title, $content, $publish);


                $this->getEventManager()->trigger('post.create.postflush', $this, array(
                    'blog' => $blog,
                    'post' => $post,
                    'actor' => $author,
                    'data' => $data,
                    'language' => $language
                ));
                
                $blog->getObjectManager()->flush();
                
                $this->redirect()->toRoute('blog/view', array(
                    'id' => $this->params('id')
                ));
            }
        }
        
        $view = new ViewModel(array(
            'blog' => $blog,
            'form' => $form
        ));
        
        $view->setTemplate('blog/blog/post/create');
        
        return $view;
    }
}