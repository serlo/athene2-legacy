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
namespace Discussion;

use Discussion\Exception;
use Doctrine\Common\Collections\ArrayCollection;
use Language\Entity\LanguageInterface;
use Discussion\Entity\CommentInterface;

class DiscussionManager implements DiscussionManagerInterface
{
    use\Common\Traits\ObjectManagerAwareTrait,\Uuid\Manager\UuidManagerAwareTrait,\Taxonomy\Manager\TaxonomyManagerAwareTrait,\ClassResolver\ClassResolverAwareTrait;

    protected $serviceInterface = 'Discussion\Service\DiscussionServiceInterface';

    protected $entityInterface = 'Discussion\Entity\CommentInterface';

    public function getDiscussion($id)
    {
        return $this->getComment($id);
    }

    public function getComment($id)
    {
        $comment = $this->getObjectManager()->find($this->getClassResolver()
            ->resolveClassName($this->entityInterface), $id);
        
        if (! is_object($comment))
            throw new Exception\CommentNotFoundException(sprintf('Could not find a comment by the id of %s', $id));
        
        return $comment;
    }

    public function removeComment($id)
    {
        $comment = $this->getComment($id);
        $this->removeInstance($comment->getId());
        $this->getObjectManager()->remove($comment);
        return $this;
    }
    
    /*
     * (non-PHPdoc) @see \Discussion\DiscussionManagerInterface::findDiscussionsOn()
     */
    public function findDiscussionsByLanguage(LanguageInterface $language)
    {
        $discussions = $this->getObjectManager()
            ->getRepository($this->getClassResolver()
            ->resolveClassName($this->entityInterface))
            ->findAll(array(
            'language' => $language->getId()
        ));
        return new ArrayCollection($discussions);
    }
    
    /*
     * (non-PHPdoc) @see \Discussion\DiscussionManagerInterface::findDiscussionsOn()
     */
    public function findDiscussionsOn(\Uuid\Entity\UuidInterface $uuid, $archived = false)
    {
        $discussions = $this->getObjectManager()
            ->getRepository($this->getClassResolver()
            ->resolveClassName($this->entityInterface))
            ->findBy(array(
            'uuid' => $uuid->getId(),
            'archived' => $archived
        ));
        return new ArrayCollection($discussions);
    }
    
    /*
     * (non-PHPdoc) @see \Discussion\DiscussionManagerInterface::discuss()
     */
    public function startDiscussion(\Uuid\Entity\UuidInterface $object, \Language\Entity\LanguageInterface $language, \User\Entity\UserInterface $author, $forum, $title, $content)
    {
        if ($object->is('comment')) {
            throw new Exception\RuntimeException(sprintf('You can\'t discuss a comment!'));
        }
        
        $forum = $this->getTaxonomyManager()->getTerm((int) $forum);
        
        $className = $this->getClassResolver()->resolveClassName($this->entityInterface);
        $comment = new $className();
        /* @var $comment Entity\CommentInterface */
        $this->getUuidManager()->injectUuid($comment);
        $comment->setObject($object);
        $comment->setLanguage($language->getEntity());
        $comment->setAuthor($author->getEntity());
        $comment->setTitle($title);
        $comment->setContent($content);
        $comment->setStatus(1);
        
        $this->getTaxonomyManager()->associateWith($forum->getId(), 'comments', $comment);
        
        $this->getObjectManager()->persist($comment);
        
        return $comment;
    }
    
    /*
     * (non-PHPdoc) @see \Discussion\DiscussionManagerInterface::comment()
     */
    public function commentDiscussion(CommentInterface $discussion, \Language\Entity\LanguageInterface $language, \User\Entity\UserInterface $author, $content)
    {
        if ($discussion->hasParent()) {
            throw new Exception\RuntimeException(sprintf('You are trying to comment on a comment, but only commenting a discussion is allowed (comments have parents whilst discussions do not).'));
        }
        
        $className = $this->getClassResolver()->resolveClassName($this->entityInterface);
        $comment = new $className();
        $this->getUuidManager()->injectUuid($comment);
        /* @var $comment Entity\CommentInterface */
        $comment->setParent($discussion);
        $comment->setLanguage($language->getEntity());
        $comment->setAuthor($author->getEntity());
        $comment->setContent($content);
        $comment->setStatus(1);
        
        $discussion->addChild($comment);
        
        $this->getObjectManager()->persist($comment);
        
        return $comment;
    }
    
    /*
     * (non-PHPdoc) @see \Discussion\DiscussionManagerInterface::findParticipatedDiscussions()
     */
    public function findParticipatedDiscussions(\User\Entity\UserInterface $user)
    {
        // TODO Auto-generated method stub
    }
}