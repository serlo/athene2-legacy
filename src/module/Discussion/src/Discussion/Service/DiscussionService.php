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
namespace Discussion\Service;

use Uuid\Entity\UuidInterface;
use Language\Service\LanguageServiceInterface;
use User\Service\UserServiceInterface;
use Discussion\Entity\CommentInterface;
use Discussion\Form\CommentForm;
use Discussion\Form\DiscussionForm;
use Discussion\Collection\CommentCollection;
use Normalize\Normalizable;
use Normalize\Normalized;

class DiscussionService extends AbstractComment implements DiscussionServiceInterface, Normalizable
{
    use\Discussion\DiscussionManagerAwareTrait;

    /**
     *
     * @var CommentForm
     */
    protected $form;

    /**
     *
     * @return CommentInterface
     */
    public function getEntity()
    {
        return parent::getEntity();
    }

    public function isDiscussion()
    {
        return ! $this->hasParent();
    }

    public function getForm()
    {
        if (! is_object($this->form)) {
            if ($this->isDiscussion()) {
                $this->form = new DiscussionForm();
            } else {
                $this->form = new CommentForm();
            }
        }
        return $this->form;
    }

    public function getId()
    {
        return $this->getEntity()->getId();
    }

    public function getObject()
    {
        return $this->getEntity()->getUuid();
    }

    public function getParent()
    {
        return $this->getDiscussionManager()->getComment($this->getEntity()
            ->getParent()
            ->getId());
    }

    public function getLanguage()
    {
        return $this->getEntity()->getLanguage();
    }

    public function getAuthor()
    {
        return $this->getEntity()->getauthor();
    }

    public function getDate()
    {
        return $this->getEntity()->getDate();
    }

    public function getTitle()
    {
        return $this->getEntity()->getTitle();
    }

    public function getContent()
    {
        return $this->getEntity()->getContent();
    }

    public function getVotes()
    {
        return $this->getEntity()->getVotes();
    }

    public function setObject(UuidInterface $uuid)
    {
        $this->getEntity()->setUuid($uuid);
        $this->getObjectManager()->persist($this->getEntity());
        return $this;
    }

    public function setParent(DiscussionServiceInterface $comment)
    {
        $this->getEntity()->setParent($comment->getEntity());
        $this->getObjectManager()->persist($this->getEntity());
        return $this;
    }

    public function setLanguage(LanguageServiceInterface $language)
    {
        $this->getEntity()->setLanguage($language->getEntity());
        $this->getObjectManager()->persist($this->getEntity());
        return $this;
    }

    public function setAuthor(UserServiceInterface $author)
    {
        $this->getEntity()->setAuthor($author->getEntity());
        $this->getObjectManager()->persist($this->getEntity());
        return $this;
    }

    public function setTitle($title)
    {
        $this->getEntity()->setTitle($title);
        $this->getObjectManager()->persist($this->getEntity());
        return $this;
    }

    public function setContent($content)
    {
        $this->getEntity()->setContent($content);
        $this->getObjectManager()->persist($this->getEntity());
        return $this;
    }

    public function getChildren()
    {
        return new CommentCollection($this->getEntity()->getChildren(), $this->getDiscussionManager());
    }

    public function addChild(DiscussionServiceInterface $comment)
    {
        $this->getEntity()->addChild($comment->getEntity());
        $this->getObjectManager()->persist($this->getEntity());
        return $this;
    }

    public function hasParent()
    {
        return $this->getEntity()->getParent();
    }

    public function countUpVotes()
    {
        return $this->getEntity()->countUpVotes();
    }

    public function countDownVotes()
    {
        return $this->getEntity()->countDownVotes();
    }

    public function getArchived()
    {
        return $this->getEntity()->getArchived();
    }

    public function setArchived($archived)
    {
        $this->getEntity()->setArchived($archived);
        $this->getObjectManager()->persist($this->getEntity());
        return $this;
    }

    public function upVote(UserServiceInterface $user)
    {
        $this->getEntity()->upVote($user->getEntity());
        $this->getObjectManager()->persist($this->getEntity());
        return $this;
    }

    public function downVote(UserServiceInterface $user)
    {
        $this->getEntity()->downVote($user->getEntity());
        $this->getObjectManager()->persist($this->getEntity());
        return $this;
    }

    public function hasUserVoted(UserServiceInterface $user = NULL)
    {
        if ($user === NULL)
            return false;
        return $this->getEntity()->hasUserVoted($user->getEntity());
    }
    
    public function normalize(){
        $normalized = new Normalized();
        $normalized->setTitle($this->getTitle());
        $normalized->setContent($this->getContent());
        $normalized->setTimestamp($this->getDate());
        $normalized->setRouteName('discussion/view');
        $normalized->setRouteParams(array(
            'id'=> $this->getId()
        ));
        return $normalized;
    }
}