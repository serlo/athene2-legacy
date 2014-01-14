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

use User\Entity\UserInterface;
use Uuid\Entity\UuidInterface;
use Discussion\Entity\CommentInterface;
use Language\Entity\LanguageInterface;
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
     * @param LanguageInterface $language
     * @param UserInterface $author
     * @param int $forum
     * @param string $title
     * @param string $content
     * @param CommentInterface $parent
     * @return CommentServiceInterface
     */
    public function startDiscussion(UuidInterface $object, LanguageInterface $language, UserInterface $author, $forum, $title, $content);
    
    /**
     * 
     * @param CommentInterface $discussion
     * @param LanguageInterface $language
     * @param UserInterface $author
     * @param string $content
     * @return CommentServiceInterface
     */
    public function commentDiscussion(CommentInterface $discussion, LanguageInterface $language, UserInterface $author, $content);

    /**
     *
     * @param int $commentId
     * @return void
     */
    public function toggleArchived($commentId);
    
    /**
     * 
     * @param UserInterface $user
     * @return CommentServiceInterface[]
     */
    public function findParticipatedDiscussions(UserInterface $user);
    
    /**
     * 
     * @param LanguageInterface $language
     * @return CommentServiceInterface[]
     */
    public function findDiscussionsByLanguage(LanguageInterface $language);
}