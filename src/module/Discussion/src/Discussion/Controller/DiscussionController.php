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
        return $this->redirect()->toReferer();
    }

    public function commentAction()
    {
        $discussion = $this->getDiscussionManager()->getDiscussion($this->params('discussion'));
        $this->assertGranted('discussion.comment.create', $discussion);

        $form = new CommentForm();
        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()) {
                $instance = $this->getInstanceManager()->getInstanceFromRequest();
                $author   = $this->getUserManager()->getUserFromAuthenticator();
                $content  = $form->getData()['content'];

                $this->getDiscussionManager()->commentDiscussion(
                $discussion,
                    $instance,
                    $author,
                    $content,
                    $form->getData()
                );

                $this->getDiscussionManager()->getObjectManager()->flush();
                return $this->redirect()->toUrl($this->referer()->fromStorage());
            }
        } else {
            $this->referer()->store();
        }

        $view = new ViewModel(['form' => $form]);
        $view->setTemplate('discussion/discussion/start');

        return $view;
    }

    public function showAction()
    {
        $discussion = $this->getDiscussion();
        $view       = new ViewModel([
                                        'discussion' => $discussion,
                                        'user'       => $this->getUserManager()->getUserFromAuthenticator()
                                    ]);
        $view->setTemplate('discussion/discussion/show');

        return $view;
    }

    protected function getDiscussion()
    {
        return $this->getDiscussionManager()->getComment($this->params('id'));
    }

    public function startAction()
    {
        $form     = new DiscussionForm();
        $view     = new ViewModel(['form' => $form]);
        $instance = $this->getInstanceManager()->getInstanceFromRequest();
        $author   = $this->getUserManager()->getUserFromAuthenticator();
        $this->assertGranted('discussion.create', $instance);

        if ($this->getRequest()->isPost()) {
            $form->setData(
                array_merge(
                    $this->getRequest()->getPost(),
                    [
                        'instance' => $instance,
                        'author'   => $author
                    ]
                )
            );
            if ($form->isValid()) {
                $forum = $this->params('forum');

                $this->getDiscussionManager()->startDiscussion($forum, $form);
                $this->getDiscussionManager()->flush();
                return $this->redirect()->toUrl($this->referer()->fromStorage());
            }
        } else {
            $this->referer()->store();
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
