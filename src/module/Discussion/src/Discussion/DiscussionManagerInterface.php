<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright   Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Discussion;

use Common\ObjectManager\Flushable;
use Discussion\Entity\CommentInterface;
use Language\Entity\LanguageInterface;
use User\Entity\UserInterface;
use Uuid\Entity\UuidInterface;

interface DiscussionManagerInterface extends Flushable
{
    /**
     * @param CommentInterface  $discussion
     * @param LanguageInterface $language
     * @param UserInterface     $author
     * @param string            $content
     * @param array             $data
     * @return CommentInterface
     */
    public function commentDiscussion(
        CommentInterface $discussion,
        LanguageInterface $language,
        UserInterface $author,
        $content,
        $data = []
    );

    /**
     * @param LanguageInterface $language
     * @return CommentInterface[]
     */
    public function findDiscussionsByLanguage(LanguageInterface $language);

    /**
     * Finds discussions on a uuid
     *
     * @param UuidInterface $uuid
     * @return CommentInterface[]
     */
    public function findDiscussionsOn(UuidInterface $uuid);

    /**
     * @param UserInterface $user
     * @return CommentInterface[]
     */
    public function findParticipatedDiscussions(UserInterface $user);

    /**
     * Returns a comment
     *
     * @param int $id
     * @return CommentInterface
     */
    public function getComment($id);

    /**
     * @param UuidInterface     $object
     * @param LanguageInterface $language
     * @param UserInterface     $author
     * @param                   $forum
     * @param                   $title
     * @param                   $content
     * @param array             $data
     * @return CommentInterface
     */
    public function startDiscussion(
        UuidInterface $object,
        LanguageInterface $language,
        UserInterface $author,
        $forum,
        $title,
        $content,
        $data = []
    );

    /**
     * @param int $commentId
     * @return void
     */
    public function toggleArchived($commentId);
}
