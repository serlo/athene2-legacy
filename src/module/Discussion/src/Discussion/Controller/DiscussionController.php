<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author    Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license   LGPL-3.0
 * @license   http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link      https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Discussion\Controller;

use Discussion\Form\CommentForm;
use Discussion\Form\DiscussionForm;
use Instance\Manager\InstanceManagerAwareTrait;
use User\Manager\UserManagerAwareTrait;
use Uuid\Manager\UuidManagerAwareTrait;
use Zend\View\Model\ViewModel;

class DiscussionController extends AbstractController
{
    use InstanceManagerAwareTrait, UserManagerAwareTrait, UuidManagerAwareTrait;

    public function archiveAction()
    {
        $discussion = $this->getDiscussionManager()->getComment($this->params('comment'));
        $this->assertGranted('discussion.archive', $discussion);
        $this->getDiscussionManager()->toggleArchived($this->params('comment'));
        $this->getDiscussionManager()->flush();
        $this->redirect()->toReferer();

        return null;
    }

    public function commentAction()
    {
        $discussion = $this->getDiscussionManager()->getDiscussion($this->params('discussion'));
        $this->assertGranted('discussion.comment.create', $discussion);

        $form = new CommentForm();

        $ref = $this->params()->fromQuery(
            'ref',
            $this->referer()->toUrl('/')
        );

        $form->setAttribute(
            'action',
            $this->url()->fromRoute(
                'discussion/discussion/start',
                array(
                    'on' => $this->params('discussion')
                )
            ) . '?ref=' . $ref
        );

        $view = new ViewModel(array(
            'form' => $form
        ));
        if ($this->getRequest()->isPost()) {
            $form->setData(
                $this->getRequest()->getPost()
            );
            if ($form->isValid()) {
                $instance = $this->getInstanceManager()->getInstanceFromRequest();
                $author   = $this->getUserManager()->getUserFromAuthenticator();
                $content  = $form->getData()['content'];

                $comment = $this->getDiscussionManager()->commentDiscussion(
                    $discussion,
                    $instance,
                    $author,
                    $content,
                    $form->getData()
                );

                $this->getDiscussionManager()->getObjectManager()->flush();

                $this->redirect()->toUrl($ref);
            }
        }

        $view->setTemplate('discussion/discussion/start');

        return $view;
    }

    public function showAction()
    {
        $discussion = $this->getDiscussion();
        $view       = new ViewModel(array(
            'discussion' => $discussion,
            'user'       => $this->getUserManager()->getUserFromAuthenticator()
        ));
        $view->setTemplate('discussion/discussion/show');

        return $view;
    }

    protected function getDiscussion()
    {
        return $this->getDiscussionManager()->getComment($this->params('id'));
    }

    public function startAction()
    {
        $this->assertGranted('discussion.create');

        $form = new DiscussionForm();

        $ref = $this->params()->fromQuery(
            'ref',
            $this->referer()->toUrl('/')
        );

        $view = new ViewModel(array(
            'form' => $form
        ));
        if ($this->getRequest()->isPost()) {
            $form->setData(
                $this->getRequest()->getPost()
            );
            if ($form->isValid()) {
                $object   = $this->getUuidManager()->getUuid($this->params('on'));
                $instance = $this->getInstanceManager()->getInstanceFromRequest();
                $author   = $this->getUserManager()->getUserFromAuthenticator();
                $title    = $form->getData()['title'];
                $content  = $form->getData()['content'];
                $forum    = $form->getData()['forum'];

                $discussion = $this->getDiscussionManager()->startDiscussion(
                    $object,
                    $instance,
                    $author,
                    $forum,
                    $title,
                    $content,
                    $form->getData()
                );

                $this->getDiscussionManager()->getObjectManager()->flush();

                $this->redirect()->toUrl($ref);
            }
        }

        $view->setTemplate('discussion/discussion/start');

        return $view;
    }

    public function voteAction()
    {
        $discussion = $this->getDiscussionManager()->getComment($this->params('comment'));
        $this->assertGranted('discussion.vote', $discussion);

        $user = $this->getUserManager()->getUserFromAuthenticator();

        if ($this->params('vote') == 'down') {
            if ($discussion->downVote($user) === null) {
                $this->flashMessenger()->addErrorMessage('You can\'t downvote this comment.');
            } else {
                $this->flashMessenger()->addSuccessMessage('You have downvoted this comment.');
            }
        } else {
            if ($discussion->upVote($user) === null) {
                $this->flashMessenger()->addErrorMessage('You can\'t upvote this comment.');
            } else {
                $this->flashMessenger()->addSuccessMessage('You have upvoted this comment.');
            }
        }

        $this->getDiscussionManager()->flush();
        $this->redirect()->toReferer();

        return null;
    }
}
