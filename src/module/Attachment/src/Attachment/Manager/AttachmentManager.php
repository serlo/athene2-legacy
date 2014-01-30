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
use Attachment\Exception;
use ClassResolver\ClassResolverAwareTrait;
use Common\Traits\ConfigAwareTrait;
use Common\Traits\ObjectManagerAwareTrait;
use Doctrine\Common\Collections\Criteria;
use Instance\Manager\InstanceManagerAwareTrait;
use Type\TypeManagerAwareTrait;
use Uuid\Manager\UuidManagerAwareTrait;
use Zend\Filter\File\RenameUpload;

class AttachmentManager implements AttachmentManagerInterface
{
    use UuidManagerAwareTrait, ClassResolverAwareTrait;
    use ConfigAwareTrait, ObjectManagerAwareTrait;
    use InstanceManagerAwareTrait, TypeManagerAwareTrait;

    public function getDefaultConfig()
    {
        return array(
            'path'    => self::findParentPath('public/uploads'),
            'webpath' => '/uploads'
        );
    }

    public function attach(array $file, $type = 'file', $appendId = null)
    {
        $filename  = $file['name'];
        $size      = $file['size'];
        $filetype      = $file['type'];
        $pathinfo  = pathinfo($filename);
        $extension = isset($pathinfo['extension']) ? '.' . $pathinfo['extension'] : '';
        $hash      = uniqid() . '_' . hash('ripemd160', $filename) . $extension;

        $location    = $this->getOption('path') . '/' . $hash;
        $webLocation = $this->getOption('webpath') . '/' . $hash;
        $filter      = new RenameUpload($location);
        $filter->filter($file);

        if ($appendId) {
            $attachment = $this->getAttachment($appendId);
        } else {
            $attachment = $this->createAttachment();
            $type = $this->getTypeManager()->findTypeByName($type);
            $attachment->setType($type);
        }

        return $this->attachFile($attachment, $filename, $webLocation, $size, $filetype);
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
                throw new Exception\FileNotFoundException(sprintf('Container found, but file id does not exist.'));
            }

            return $file;
        } else {
            return $attachment->getFirstFile();
        }
    }

    public function getAttachment($id)
    {
        /* @var $entity \Attachment\Entity\ContainerInterface */
        $entity = $this->getObjectManager()->find(
            $this->getClassResolver()->resolveClassName('Attachment\Entity\ContainerInterface'),
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
        /* @var $attachment ContainerInterface */
        $attachment = $this->getClassResolver()->resolve('Attachment\Entity\ContainerInterface');
        $this->getUuidManager()->injectUuid($attachment);
        $attachment->setInstance(
            $this->getInstanceManager()->getInstanceFromRequest()
        );
        $this->getObjectManager()->persist($attachment);

        return $attachment;
    }

    protected function attachFile(ContainerInterface $attachment, $filename, $location, $size, $type)
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
