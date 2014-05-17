<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace Discussion;

use Common\ObjectManager\Flushable;
use Discussion\Entity\CommentInterface;
use Instance\Entity\InstanceInterface;
use Uuid\Entity\UuidInterface;
use Zend\Form\FormInterface;
use Doctrine\Common\Collections\Collection;

interface DiscussionManagerInterface extends Flushable
{
    /**
     * @param FormInterface $form
     * @return CommentInterface
     */
    public function commentDiscussion(FormInterface $form);

    /**
     * @param InstanceInterface $instance
     * @return CommentInterface[]|Collection
     */
    public function findDiscussionsByInstance(InstanceInterface $instance);

    /**
     * Finds discussions on a uuid
     *
     * @param UuidInterface $uuid
     * @return CommentInterface[]|Collection
     */
    public function findDiscussionsOn(UuidInterface $uuid);

    /**
     * Returns a comment
     *
     * @param int $id
     * @return CommentInterface
     */
    public function getComment($id);

    /**
     * @param FormInterface $form
     * @return CommentInterface
     */
    public function startDiscussion(FormInterface $form);

    /**
     * @param int $commentId
     * @return void
     */
    public function toggleArchived($commentId);
}
