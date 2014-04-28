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

use User\Entity\UserInterface;

interface VoteInterface
{

    /**
     * @param UserInterface $user
     * @return self
     */
    public function setUser(UserInterface $user);

    /**
     * @return UserInterface
     */
    public function getUser();

    /**
     * @param CommentInterface $comment
     * @return self
     */
    public function setComment(CommentInterface $comment);

    /**
     * @return CommentInterface
     */
    public function getComment();

    /**
     * @param int $type
     * @return self
     */
    public function setVote($type);

    /**
     * @return int
     */
    public function getVote();
}