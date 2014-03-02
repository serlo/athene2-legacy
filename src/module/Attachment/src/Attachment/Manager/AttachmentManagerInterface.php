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
namespace Attachment\Manager;

use Attachment\Entity\ContainerInterface;
use Attachment\Entity\FileInterface;
use Attachment\Form\AttachmentFieldsetProvider;
use Common\ObjectManager\Flushable;

interface AttachmentManagerInterface extends Flushable
{
    /**
     * @param AttachmentFieldsetProvider $form
     * @param string                     $type
     * @param int                        $appendId
     * @return ContainerInterface
     */
    public function attach(AttachmentFieldsetProvider $form, $type = 'file', $appendId = null);

    /**
     * @param int $id
     * @return ContainerInterface
     */
    public function getAttachment($id);

    /**
     * @param $attachmentId $id
     * @param $fileId       $id
     * @return FileInterface
     */
    public function getFile($attachmentId, $fileId = null);
}
