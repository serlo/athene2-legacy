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

use User\Service\UserServiceInterface;
use Uuid\Entity\UuidInterface;
use Discussion\Service\CommentInterface;
use Language\Service\LanguageServiceInterface;
use Discussion\Service\CommentServiceInterface;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;

interface DiscussionManagerInterface extends ObjectManagerAwareInterface
{
    /**
     * Returns a comment
     * 
     * @param int $id
     * @return CommentServiceInterface
     */
    public function getComment($id);
    
    /**
     * Finds discussions on a uuid
     * 
     * @param UuidInterface $uuid
     * @return CommentServiceInterface[]
     */
    public function findDiscussionsOn(UuidInterface $uuid);
    
    /**
     * 
     * @param UuidInterface $object
     * @param LanguageServiceInterface $language
     * @param UserServiceInterface $author
     * @param string $title
     * @param string $content
     * @param CommentInterface $parent
     * @return CommentServiceInterface
     */
    public function startDiscussion(UuidInterface $object, LanguageServiceInterface $language, UserServiceInterface $author, $title, $content);
    
    /**
     * 
     * @param CommentInterface $discussion
     * @param LanguageServiceInterface $language
     * @param UserServiceInterface $author
     * @param string $content
     * @return CommentServiceInterface
     */
    public function commentDiscussion(CommentServiceInterface $discussion, LanguageServiceInterface $language, UserServiceInterface $author, $content);
    
    /**
     * 
     * @param UserServiceInterface $user
     * @return CommentServiceInterface[]
     */
    public function findParticipatedDiscussions(UserServiceInterface $user);
    
    /**
     * 
     * @param LanguageServiceInterface $language
     * @return CommentServiceInterface[]
     */
    public function findDiscussionsByLanguage(LanguageServiceInterface $language);
}