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
use Discussion\Service;
use Discussion\Entity;
use Discussion\Collection\CommentCollection;
use Doctrine\Common\Collections\ArrayCollection;
use Language\Service\LanguageServiceInterface;
use Taxonomy\Service\TermServiceInterface;

class DiscussionManager extends AbstractDiscussionManager implements DiscussionManagerInterface
{

    protected $serviceInterface = 'Discussion\Service\CommentServiceInterface';

    protected $entityInterface = 'Discussion\Entity\CommentInterface';
    
    use \Common\Traits\ObjectManagerAwareTrait,\Uuid\Manager\UuidManagerAwareTrait,\Taxonomy\Manager\SharedTaxonomyManagerAwareTrait;

    protected function getDefaultConfig()
    {
        return array();
    }
    
    public function getDiscussion($id){
        return $this->getComment($id);
    }
    
    public function getComment($id){
        if (! is_numeric($id))
            throw new Exception\InvalidArgumentException(sprintf('Expected int but got `%s`', gettype($id)));
        
        if (! $this->hasInstance($id)) {
            $comment = $this->getObjectManager()->find($this->getClassResolver()
                ->resolveClassName($this->entityInterface), $id);
            
            if (! is_object($comment))
                throw new Exception\CommentNotFoundException(sprintf('Could not find a comment by the id of %s', $id));
            
            $this->addInstance($comment->getId(), $this->createService($comment));
        }
        
        return $this->getInstance($id);
    }
    
    public function removeComment($id){
        $comment = $this->getComment($id);            
        $this->removeInstance($comment->getId());
        $this->getObjectManager()->remove($comment->getEntity());        
        return $this;
    }
    
    /*
     * (non-PHPdoc) @see \Discussion\DiscussionManagerInterface::findDiscussionsOn()
     */
    public function findDiscussionsByLanguage(LanguageServiceInterface $language)
    {
        $discussions = $this->getObjectManager()
            ->getRepository($this->getClassResolver()
            ->resolveClassName($this->entityInterface))
            ->findAll(array(
            'language' => $language->getId()
        ));
        $collection = new ArrayCollection($discussions);
        return new CommentCollection($collection, $this);
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
        $collection = new ArrayCollection($discussions);
        return new CommentCollection($collection, $this);
    }
    
    /*
     * (non-PHPdoc) @see \Discussion\DiscussionManagerInterface::discuss()
     */
    public function startDiscussion(\Uuid\Entity\UuidInterface $object,\Language\Service\LanguageServiceInterface $language,\User\Service\UserServiceInterface $author, $forum, $title, $content)
    {
        if ($object->is('comment'))
            throw new Exception\RuntimeException(sprintf('You can\'t discuss a comment!'));
        
        $forum = $this->getSharedTaxonomyManager()->getTerm((int) $forum);
        
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
        $forum->associate('comments', $comment);
        
        $this->getObjectManager()->persist($comment);
        
        return $this->createService($comment);
    }
    
    /*
     * (non-PHPdoc) @see \Discussion\DiscussionManagerInterface::comment()
     */
    public function commentDiscussion(\Discussion\Service\CommentServiceInterface $discussion,\Language\Service\LanguageServiceInterface $language,\User\Service\UserServiceInterface $author, $content)
    {
        if ($discussion->hasParent())
            throw new Exception\RuntimeException(sprintf('You are trying to comment on a comment, but only commenting a discussion is allowed (comments have parents whilst discussions do not).'));
        
        $className = $this->getClassResolver()->resolveClassName($this->entityInterface);
        $comment = new $className();
        $this->getUuidManager()->injectUuid($comment);
        /* @var $comment Entity\CommentInterface */
        $comment->setParent($discussion->getEntity());
        $comment->setLanguage($language->getEntity());
        $comment->setAuthor($author->getEntity());
        $comment->setContent($content);
        $comment->setStatus(1);
        
        $service = $this->createService($comment);
        
        $discussion->addChild($service);
        
        $this->getObjectManager()->persist($comment);
        
        return $service;
    }
    
    /*
     * (non-PHPdoc) @see \Discussion\DiscussionManagerInterface::findParticipatedDiscussions()
     */
    public function findParticipatedDiscussions(\User\Service\UserServiceInterface $user)
    {
        // TODO Auto-generated method stub
    }

    protected function createService(Entity\CommentInterface $comment)
    {
        $service = $this->createInstance($this->serviceInterface);
        $service->setEntity($comment);
        $service->setDiscussionManager($this);
        return $service;
    }
}