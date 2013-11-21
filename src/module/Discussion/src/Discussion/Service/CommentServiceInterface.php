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

use User\Service\UserServiceInterface;
use Language\Service\LanguageServiceInterface;
use Uuid\Entity\UuidInterface;

interface CommentServiceInterface
{

    /**
     *
     * @return UuidInterface
     */
    public function getObject();

    /**
     *
     * @param UuidInterface $uuid            
     * @return $this
     */
    public function setObject(UuidInterface $uuid);

    /**
     *
     * @param LanguageServiceInterface $language            
     * @return $this
     */
    public function setLanguage(LanguageServiceInterface $language);

    /**
     *
     * @return LanguageServiceInterface
     */
    public function getLanguage();

    /**
     *
     * @return UserServiceInterface
     */
    public function getAuthor();

    /**
     *
     * @param UserServiceInterface $user            
     * @return $this
     */
    public function setAuthor(UserServiceInterface $user);

    /**
     *
     * @param CommentServiceInterface $comment            
     * @return $this
     */
    public function setParent(CommentServiceInterface $comment);

    /**
     *
     * @return CommentServiceInterface
     */
    public function getParent();

    /**
     *
     * @return Collection
     */
    public function getChildren();

    /**
     *
     * @param CommentServiceInterface $comment            
     * @return $this
     */
    public function addChild(CommentServiceInterface $comment);

    /**
     *
     * @return string
     */
    public function getTitle();

    /**
     *
     * @param string $title            
     * @return $this
     */
    public function setTitle($title);

    /**
     *
     * @return string
     */
    public function getContent();

    /**
     *
     * @param string $content            
     * @return $this
     */
    public function setContent($content);

    /**
     *
     * @return boolean
     */
    public function hasParent();

    /**
     *
     * @return int
     */
    public function getVotes();


    /**
     *
     * @return int
     */
    public function countUpVotes ();

    /**
     *
     * @return int
     */    
    public function countDownVotes ();

    /**
     *
     * @return bool
     */
    public function getArchived();

    /**
     *
     * @param bool $archived            
     * @return $this
     */
    public function setArchived($archived);

    /**
     *
     * @param UserServiceInterface $user  
     * @return $this
     */
    public function upVote(UserServiceInterface $user);

    /**
     *
     * @param UserServiceInterface $user
     * @return $this
     */
    public function downVote(UserServiceInterface $user);
    
    /**
     * 
     * @param UserServiceInterface $user
     * @return $this
     */
    public function hasUserVoted(UserServiceInterface $user);
}