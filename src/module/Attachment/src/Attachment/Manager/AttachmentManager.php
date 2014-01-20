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

use Attachment\Entity\Attachment;
use Attachment\Entity\File;
use Attachment\Exception;
use Zend\Filter\File\RenameUpload;

class AttachmentManager implements AttachmentManagerInterface
{
    use \Uuid\Manager\UuidManagerAwareTrait, \ClassResolver\ClassResolverAwareTrait, \Common\Traits\ConfigAwareTrait,
        \Common\Traits\ObjectManagerAwareTrait, \Language\Manager\LanguageManagerAwareTrait;

    public function getDefaultConfig()
    {
        return array(
            'path'    => self::findParentPath('public/uploads'),
            'webpath' => '/uploads'
        );
    }

    public function attach(array $file)
    {
        $filename = $file['name'];
        $size     = $file['size'];
        $type     = $file['type'];
        $tmp      = $file['tmp_name'];
        $pathinfo = pathinfo($filename);
        $hash     = uniqid() . '_' . hash('ripemd160', $filename) . '.' . $pathinfo['extension'];

        $location    = $this->getOption('path') . '/' . $hash;
        $webLocation = $this->getOption('webpath') . '/' . $hash;
        $filter      = new RenameUpload($location);
        $filter->filter($file);

        return $this->addAttachment($filename, $webLocation, $size, $type);
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

    protected function addAttachment($filename, $location, $size, $type)
    {
        $attachment = new Attachment();
        $this->getUuidManager()->injectUuid($attachment);
        $attachment->setLanguage(
            $this->getLanguageManager()->getLanguageFromRequest()
        );

        $file = new File();
        $file->setFilename($filename);
        $file->setLocation($location);
        $file->setSize($size);
        $file->setType($type);

        $attachment->addFile($file);
        $this->getObjectManager()->persist($attachment);
        $this->getObjectManager()->persist($file);

        return $attachment;
    }
}