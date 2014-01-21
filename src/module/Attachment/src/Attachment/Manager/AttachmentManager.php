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
use Attachment\Exception;
use ClassResolver\ClassResolverAwareTrait;
use Common\Traits\ConfigAwareTrait;
use Common\Traits\ObjectManagerAwareTrait;
use Doctrine\Common\Collections\Criteria;
use Language\Manager\LanguageManagerAwareTrait;
use Uuid\Manager\UuidManagerAwareTrait;
use Zend\Filter\File\RenameUpload;

class AttachmentManager implements AttachmentManagerInterface
{
    use UuidManagerAwareTrait, ClassResolverAwareTrait;
    use ConfigAwareTrait, ObjectManagerAwareTrait;
    use LanguageManagerAwareTrait;

    public function getDefaultConfig()
    {
        return array(
            'path'    => self::findParentPath('public/uploads'),
            'webpath' => '/uploads'
        );
    }

    public function attach(array $file, $appendId = null)
    {
        $filename = $file['name'];
        $size     = $file['size'];
        $type     = $file['type'];
        $pathinfo = pathinfo($filename);
        $hash     = uniqid() . '_' . hash('ripemd160', $filename) . '.' . $pathinfo['extension'];

        $location    = $this->getOption('path') . '/' . $hash;
        $webLocation = $this->getOption('webpath') . '/' . $hash;
        $filter      = new RenameUpload($location);
        $filter->filter($file);

        if ($appendId) {
            $attachment = $this->getAttachment($appendId);
        } else {
            $attachment = $this->createAttachment();
        }

        return $this->attachFile($attachment, $filename, $webLocation, $size, $type);
    }

    public function getFile($attachmentId, $fileId = null)
    {
        $attachment = $this->getAttachment($attachmentId);

        if ($fileId) {
            $matching = $attachment->getFiles()->matching(
                Criteria::create()->where(Criteria::expr()->eq('id', $fileId))
            );
            $file     = $matching->first();

            if (!is_object($file)) {
                throw new Exception\FileNotFoundException(sprintf('Attachment found, but file id does not exist.'));
            }

            return $file;
        } else {
            return $attachment->getFirstFile();
        }
    }

    public function getAttachment($id)
    {
        /* @var $entity \Attachment\Entity\AttachmentInterface */
        $entity = $this->getObjectManager()->find(
            $this->getClassResolver()->resolveClassName('Attachment\Entity\AttachmentInterface'),
            $id
        );

        if (!is_object($entity)) {
            throw new Exception\AttachmentNotFoundException(sprintf('Upload "%s" not found', $id));
        }

        return $entity;
    }

    public static function findParentPath($path)
    {
        $dir         = __DIR__;
        $previousDir = '.';
        while (!is_dir($dir . '/' . $path) && !file_exists($dir . '/' . $path)) {
            $dir = dirname($dir);
            if ($previousDir === $dir) {
                return false;
            }
            $previousDir = $dir;
        }

        return $dir . '/' . $path;
    }

    protected function createAttachment()
    {
        $attachment = $this->getClassResolver()->resolve('Attachment\Entity\AttachmentInterface');
        $this->getUuidManager()->injectUuid($attachment);
        $attachment->setLanguage(
            $this->getLanguageManager()->getLanguageFromRequest()
        );
        $this->getObjectManager()->persist($attachment);

        return $attachment;
    }

    protected function attachFile(AttachmentInterface $attachment, $filename, $location, $size, $type)
    {
        $file = $this->getClassResolver()->resolve('Attachment\Entity\FileInterface');
        $file->setFilename($filename);
        $file->setLocation($location);
        $file->setSize($size);
        $file->setType($type);

        $file->setAttachment($attachment);
        $attachment->addFile($file);

        $this->getObjectManager()->persist($file);

        return $attachment;
    }
}
