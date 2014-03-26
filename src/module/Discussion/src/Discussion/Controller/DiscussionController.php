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
    use InstanceManagerAwareTrait, UserManagerAwareTrait;

    /**
     * @var \Discussion\Form\CommentForm
     */
    protected $commentForm;

    /**
     * @var \Discussion\Form\DiscussionForm
     */
    protected $discussionForm;

    public function __construct(CommentForm $commentForm, DiscussionForm $discussionForm)
    {
        $this->commentForm    = $commentForm;
        $this->discussionForm = $discussionForm;
    }

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
        $form       = $this->commentForm;
        $this->assertGranted('discussion.comment.create', $discussion);

        if ($this->getRequest()->isPost()) {
            $data = [
                'instance' => $this->getInstanceManager()->getInstanceFromRequest(),
                'parent'   => $this->params('discussion'),
                'author'   => $this->getUserManager()->getUserFromAuthenticator()
            ];
            $form->setData(array_merge($this->params()->fromPost(), $data));
            if ($form->isValid()) {
                $this->getDiscussionManager()->commentDiscussion($form);
                $this->getDiscussionManager()->flush();
                return $this->redirect()->toReferer();
            }
        }

        $view = new ViewModel(['form' => $form, 'discussion' => $discussion]);
        $view->setTemplate('discussion/discussion/comment');

        return $view;
    }

    public function showAction()
    {
        $discussion = $this->getDiscussion();
        $view       = new ViewModel([
            'discussion' => $discussion,
            'user'       => $this->getUserManager()->getUserFromAuthenticator()
        ]);
        $view->setTemplate('discussion/discussion/index');

        return $view;
    }

    protected function getDiscussion()
    {
        return $this->getDiscussionManager()->getComment($this->params('id'));
    }

    public function startAction()
    {
        $form     = $this->discussionForm;
        $view     = new ViewModel(['form' => $form]);
        $instance = $this->getInstanceManager()->getInstanceFromRequest();
        $author   = $this->getUserManager()->getUserFromAuthenticator();
        $this->assertGranted('discussion.create', $instance);

        if ($this->getRequest()->isPost()) {
            $data = [
                'instance' => $instance,
                'author'   => $author,
                'terms'    => $this->params('forum'),
                'object'   => $this->params('on')
            ];
            $form->setData(array_merge($this->params()->fromPost(), $data));
            if ($form->isValid()) {
                $this->getDiscussionManager()->startDiscussion($form);
                $this->getDiscussionManager()->flush();
                return $this->redirect()->toReferer();
            }
        }

        $view->setTemplate('discussion/discussion/start');

        return $view;
    }

    public function voteAction()
    {
        $discussion = $this->getDiscussionManager()->getComment($this->params('comment'));
        $user       = $this->getUserManager()->getUserFromAuthenticator();
        $this->assertGranted('discussion.vote', $discussion);

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
        return $this->redirect()->toReferer();
    }
}
