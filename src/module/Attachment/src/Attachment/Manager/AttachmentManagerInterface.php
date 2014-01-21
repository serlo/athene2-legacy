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

use Attachment\Entity\AttachmentInterface;
use Attachment\Entity\FileInterface;

interface AttachmentManagerInterface
{
    /**
     * @param array $post
     * @param int   $appendId
     * @return AttachmentInterface
     */
    public function attach(array $post, $appendId = null);

    /**
     * @param $attachmentId $id
     * @param $fileId       $id
     * @return FileInterface
     */
    public function getFile($attachmentId, $fileId = null);

    /**
     * @param int $id
     * @return AttachmentInterface
     */
    public function getAttachment($id);
}
