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
namespace Discussion\Controller;

use Zend\View\Model\ViewModel;
use Discussion\Form\CommentForm;
use Discussion\Form\DiscussionForm;

class DiscussionController extends AbstractController
{
    use \Language\Manager\LanguageManagerAwareTrait,\User\Manager\UserManagerAwareTrait,\Uuid\Manager\UuidManagerAwareTrait;

    public function startAction()
    {
        $form = new DiscussionForm();
        
        $ref = $this->params()->fromQuery('ref', $this->referer()
            ->toUrl('/'));
        
        $form->setAttribute('action', $this->url()
            ->fromRoute('discussion/discussion/start', array(
            'on' => $this->params('on')
        )) . '?ref=' . $ref);
        
        $view = new ViewModel(array(
            'form' => $form
        ));
        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()
                ->getPost());
            if ($form->isValid()) {
                $object = $this->getUuidManager()->getUuid($this->params('on'));
                $language = $this->getLanguageManager()->getLanguageFromRequest();
                $author = $this->getUserManager()->getUserFromAuthenticator();
                $title = $form->getData()['title'];
                $content = $form->getData()['content'];
                $forum = $form->getData()['forum'];
                
                $this->getDiscussionManager()->startDiscussion($object, $language, $author, $forum, $title, $content);
                
                $this->getDiscussionManager()
                    ->getObjectManager()
                    ->flush();
                
                $this->getEventManager()->trigger('start', $this, array());
                
                $this->redirect()->toUrl($ref);
            }
        }
        
        $view->setTemplate('discussion/discussion/start');
        return $view;
    }

    public function commentAction()
    {
        $form = new CommentForm();
        
        $ref = $this->params()->fromQuery('ref', $this->referer()
            ->toUrl('/'));
        
        $form->setAttribute('action', $this->url()
            ->fromRoute('discussion/discussion/start', array(
            'on' => $this->params('discussion')
        )) . '?ref=' . $ref);
        
        $view = new ViewModel(array(
            'form' => $form
        ));
        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()
                ->getPost());
            if ($form->isValid()) {
                $discussion = $this->getDiscussionManager()->getDiscussion($this->params('discussion'));
                $language = $this->getLanguageManager()->getLanguageFromRequest();
                $author = $this->getUserManager()->getUserFromAuthenticator();
                $content = $form->getData()['content'];
                
                $this->getDiscussionManager()->commentDiscussion($discussion, $language, $author, $content);
                
                $this->getDiscussionManager()
                    ->getObjectManager()
                    ->flush();
                
                $this->getEventManager()->trigger('comment', $this, array());
                
                $this->redirect()->toUrl($ref);
            }
        }
        
        $view->setTemplate('discussion/discussion/start');
        return $view;
    }

    public function voteAction()
    {
        $discussion = $this->getDiscussionManager()->getComment($this->params('comment'));
        $user = $this->getUserManager()->getUserFromAuthenticator();
        
        if($this->params('vote') == 'down'){
            if($discussion->downVote($user) === NULL){
                $this->flashMessenger()->addErrorMessage('You can\'t downvote this comment.');
            } else {
                $this->flashMessenger()->addSuccessMessage('You have downvoted this comment.');                
            }
        } else {
            if($discussion->upVote($user) === NULL){
                $this->flashMessenger()->addErrorMessage('You can\'t upvote this comment.');
            } else {
                $this->flashMessenger()->addSuccessMessage('You have upvoted this comment.');                
            }
        }
        
        $this->getDiscussionManager()
            ->getObjectManager()
            ->flush();
        
        $this->redirect()->toReferer();
        return '';
    }

    public function archiveAction()
    {
        $discussion = $this->getDiscussionManager()->getComment($this->params('comment'));
        $user = $this->getUserManager()->getUserFromAuthenticator();
        
        $discussion->setArchived(!$discussion->getArchived());
        
        $this->getDiscussionManager()
            ->getObjectManager()
            ->flush();
        
        $this->redirect()->toReferer();
        return '';
    }

    public function trashAction()
    {        
        $this->getDiscussionManager()->removeComment($this->params('comment'));
        
        $this->getDiscussionManager()
            ->getObjectManager()
            ->flush();
        
        $this->redirect()->toReferer();
        return '';
    }

    public function showAction()
    {
        $discussion = $this->getDiscussion();
        $view = new ViewModel(array(
            'discussion' => $discussion
        ));
        $view->setTemplate('discussion/discussion/show');
        return $view;
    }

    protected function getDiscussion()
    {
        return $this->getDiscussionManager()->get($this->params('discussion'));
    }
}