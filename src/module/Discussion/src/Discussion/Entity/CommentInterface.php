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
namespace Discussion\Entity;

use Uuid\Entity\UuidInterface;
use Language\Entity\LanguageInterface;
use User\Entity\UserInterface;
use Doctrine\Common\Collections\Collection;

interface CommentInterface
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
     * @param LanguageInterface $language            
     * @return $this
     */
    public function setLanguage(LanguageInterface $language);

    /**
     *
     * @return LanguageInterface
     */
    public function getLanguage();

    /**
     *
     * @return UserInterface
     */
    public function getAuthor();

    /**
     *
     * @param UserInterface $user            
     * @return $this
     */
    public function setAuthor(UserInterface $user);

    /**
     *
     * @param CommentInterface $comment            
     * @return $this
     */
    public function setParent(CommentInterface $comment);

    /**
     *
     * @return CommentInterface
     */
    public function getParent();

    /**
     *
     * @return Collection
     */
    public function getChildren();

    /**
     *
     * @param CommentInterface $comment            
     * @return $this
     */
    public function addChild(CommentInterface $comment);

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
     * @param UserInterface $user  
     * @return $this
     */
    public function upVote(UserInterface $user);

    /**
     *
     * @param UserInterface $user
     * @return $this
     */
    public function downVote(UserInterface $user);
    
    /**
     * 
     * @param UserInterface $user
     * @return $this
     */
    public function hasUserVoted(UserInterface $user);
}