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
    use \Blog\Manager\BlogManagerAwareTrait,\User\Manager\UserManagerAwareTrait;

    public function indexAction()
    {
        $blog = $this->getBlogManager()->getBlog($this->params('id'));
        $view = new ViewModel(array(
            'blog' => $blog,
            'posts' => $blog->findAllPosts()
        ));
        $view->setTemplate('blog/blog/view');
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
                $blog->createPost($author, $title, $content, $publish);
                
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
        
        $view->setTemplate('blog/blog/create');
        
        return $view;
    }
}