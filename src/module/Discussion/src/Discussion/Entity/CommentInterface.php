<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace Discussion\Entity;

use Doctrine\Common\Collections\Collection;
use Instance\Entity\InstanceAwareInterface;
use Taxonomy\Entity\TaxonomyTermAwareInterface;
use User\Entity\UserInterface;
use Uuid\Entity\UuidInterface;

interface CommentInterface extends TaxonomyTermAwareInterface, InstanceAwareInterface
{

    /**
     * @return UuidInterface
     */
    public function getObject();

    /**
     * @param UuidInterface $uuid
     * @return self
     */
    public function setObject(UuidInterface $uuid);

    /**
     * @return DateTime
     */
    public function getTimestamp();

    /**
     * @return UserInterface
     */
    public function getAuthor();

    /**
     * @param UserInterface $user
     * @return self
     */
    public function setAuthor(UserInterface $user);

    /**
     * @param CommentInterface $comment
     * @return self
     */
    public function setParent(CommentInterface $comment);

    /**
     * @return CommentInterface
     */
    public function getParent();

    /**
     * @return Collection
     */
    public function getChildren();

    /**
     * @param CommentInterface $comment
     * @return self
     */
    public function addChild(CommentInterface $comment);

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @param string $title
     * @return self
     */
    public function setTitle($title);

    /**
     * @return string
     */
    public function getContent();

    /**
     * @param string $content
     * @return self
     */
    public function setContent($content);

    /**
     * @return boolean
     */
    public function hasParent();

    /**
     * @return int
     */
    public function countUpVotes();

    /**
     * @return int
     */
    public function countDownVotes();

    /**
     * @return bool
     */
    public function getArchived();

    /**
     * @param bool $archived
     * @return self
     */
    public function setArchived($archived);

    /**
     * @param UserInterface $user
     * @return self
     */
    public function upVote(UserInterface $user);

    /**
     * @param UserInterface $user
     * @return self
     */
    public function downVote(UserInterface $user);

    /**
     * @param UserInterface $user
     * @return self
     */
    public function hasUserVoted(UserInterface $user);
}
