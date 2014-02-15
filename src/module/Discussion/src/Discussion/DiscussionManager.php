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
namespace Discussion;

use Authorization\Service\AuthorizationAssertionTrait;
use ClassResolver\ClassResolverAwareTrait;
use Common\Traits\FlushableTrait;
use Common\Traits\ObjectManagerAwareTrait;
use Discussion\Entity\CommentInterface;
use Discussion\Exception;
use Discussion\Hydrator\CommentHydrator;
use Doctrine\Common\Collections\ArrayCollection;
use Instance\Entity\InstanceInterface;
use Taxonomy\Manager\TaxonomyManagerAwareTrait;
use User\Entity\UserInterface;
use Uuid\Entity\UuidInterface;
use Uuid\Manager\UuidManagerAwareTrait;
use Zend\EventManager\EventManagerAwareTrait;

class DiscussionManager implements DiscussionManagerInterface
{
    use EventManagerAwareTrait, ObjectManagerAwareTrait;
    use TaxonomyManagerAwareTrait;
    use ClassResolverAwareTrait, AuthorizationAssertionTrait;
    use FlushableTrait;

    /**
     * @var string
     */
    protected $serviceInterface = 'Discussion\Service\DiscussionServiceInterface';

    /**
     * @var string
     */
    protected $entityInterface = 'Discussion\Entity\CommentInterface';

    public function getDiscussion($id)
    {
        return $this->getComment($id);
    }

    public function getComment($id)
    {
        $className = $this->getClassResolver()->resolveClassName($this->entityInterface);
        $comment   = $this->getObjectManager()->find($className, $id);

        if (!is_object($comment)) {
            throw new Exception\CommentNotFoundException(sprintf('Could not find a comment by the id of %s', $id));
        }

        return $comment;
    }

    public function findDiscussionsByInstance(InstanceInterface $instance)
    {
        $className        = $this->getClassResolver()->resolveClassName($this->entityInterface);
        $objectRepository = $this->getObjectManager()->getRepository($className);
        $discussions      = $objectRepository->findAll(
            array(
                'instance' => $instance->getId()
            )
        );

        return new ArrayCollection($discussions);
    }

    public function findDiscussionsOn(UuidInterface $uuid, $archived = false)
    {
        $className        = $this->getClassResolver()->resolveClassName($this->entityInterface);
        $objectRepository = $this->getObjectManager()->getRepository($className);
        $discussions      = $objectRepository->findBy(
            array(
                'object'   => $uuid->getId(),
                'archived' => $archived
            )
        );

        return new ArrayCollection($discussions);
    }

    public function startDiscussion(
        UuidInterface $object,
        InstanceInterface $instance,
        UserInterface $author,
        $forum,
        $title,
        $content,
        $data = []
    ) {
        if ($object->is('comment')) {
            throw new Exception\RuntimeException(sprintf('You can\'t discuss a comment!'));
        }

        $forum = $this->getTaxonomyManager()->getTerm($forum);
        $this->assertGranted('discussion.create', $forum);

        /* @var $comment Entity\CommentInterface */
        $className = $this->getClassResolver()->resolveClassName($this->entityInterface);
        $comment   = new $className();

        $hydrator = new CommentHydrator();
        $hydrator->hydrate(
            [
                'object'   => $object,
                'instance' => $instance,
                'author'   => $author,
                'title'    => $title,
                'content'  => $content
            ],
            $comment
        );

        $this->getTaxonomyManager()->associateWith($forum->getId(), 'comments', $comment);

        $this->getEventManager()->trigger(
            'start',
            $this,
            [
                'author'     => $author,
                'on'         => $object,
                'discussion' => $comment,
                'instance'   => $instance,
                'data'       => $data
            ]
        );

        $this->getObjectManager()->persist($comment);

        return $comment;
    }

    public function commentDiscussion(
        CommentInterface $discussion,
        InstanceInterface $instance,
        UserInterface $author,
        $content,
        $data = []
    ) {
        $this->assertGranted('discussion.comment.create', $discussion);

        if ($discussion->hasParent()) {
            throw new Exception\RuntimeException(sprintf(
                'You are trying to comment on a comment,
                                        but only commenting a discussion is allowed (comments have parents whilst discussions do not).'
            ));
        }

        /* @var $comment Entity\CommentInterface */
        $className = $this->getClassResolver()->resolveClassName($this->entityInterface);
        $comment   = new $className();

        $hydrator = new CommentHydrator();
        $hydrator->hydrate(
            [
                'parent'   => $discussion,
                'instance' => $instance,
                'author'   => $author,
                'content'  => $content
            ],
            $comment
        );

        $discussion->addChild($comment);

        $this->getEventManager()->trigger(
            'comment',
            $this,
            [
                'author'     => $author,
                'comment'    => $comment,
                'discussion' => $discussion,
                'instance'   => $instance,
                'data'       => $data
            ]
        );

        $this->getObjectManager()->persist($comment);

        return $comment;
    }

    /*
     * (non-PHPdoc) @see \Discussion\DiscussionManagerInterface::comment()
     */

    public function toggleArchived($commentId)
    {
        $comment = $this->getComment($commentId);
        $this->assertGranted('discussion.archive', $comment);

        $comment->setArchived(!$comment->getArchived());
        $this->getObjectManager()->persist($comment);
    }

    public function findParticipatedDiscussions(\User\Entity\UserInterface $user)
    {
        // TODO Auto-generated method stub
    }

    public function removeComment($id)
    {
        $comment = $this->getComment($id);
        $this->assertGranted('discussion.comment.remove', $comment);

        $this->removeInstance($comment->getId());
        $this->getObjectManager()->remove($comment);
    }
}