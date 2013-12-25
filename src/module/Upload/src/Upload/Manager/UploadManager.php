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
namespace Upload\Manager;

use Upload\Exception;
use Zend\Filter\File\RenameUpload;
use Upload\Entity\Upload;

class UploadManager implements UploadManagerInterface
{
    use \Uuid\Manager\UuidManagerAwareTrait,\ClassResolver\ClassResolverAwareTrait,\Common\Traits\ConfigAwareTrait,\Common\Traits\ObjectManagerAwareTrait;

    public function getDefaultConfig()
    {
        return array(
            'path' => self::findParentPath('public/uploads'),
            'webpath' => '/uploads'
        );
    }

    public function upload(array $file)
    {
        $filename = $file['name'];
        $size = $file['size'];
        $type = $file['type'];
        $tmp = $file['tmp_name'];
        $pathinfo = pathinfo($filename);
        $hash = uniqid() . '_' . hash('ripemd160', $filename) . '.' . $pathinfo['extension'];
        
        $location = $this->getOption('path') . '/' . $hash;
        $webLocation = $this->getOption('webpath') . '/' . $hash;
        $filter = new RenameUpload($location);
        $filter->filter($file);
        
        return $this->addUpload($filename, $webLocation, $size, $type);
    }

    public function getUpload($id)
    {
        /* @var $entity \Upload\Entity\UploadInterface */
        $entity = $this->getObjectManager()->find($this->getClassResolver()
            ->resolveClassName('Upload\Entity\UploadInterface'), $id);
        
        if (! is_object($entity)) {
            throw new Exception\UploadNotFoundException(sprintf('Upload "%s" not found', $id));
        }
        return $entity
    }

    public static function findParentPath($path)
    {
        $dir = __DIR__;
        $previousDir = '.';
        while (! is_dir($dir . '/' . $path) && ! file_exists($dir . '/' . $path)) {
            $dir = dirname($dir);
            if ($previousDir === $dir)
                return false;
            $previousDir = $dir;
        }
        return $dir . '/' . $path;
    }

    protected function addUpload($filename, $location, $size, $type)
    {
        $entity = new Upload();
        $this->getUuidManager()->injectUuid($entity);
        $entity->setFilename($filename);
        $entity->setLocation($location);
        $entity->setSize($size);
        $entity->setType($type);
        $this->getObjectManager()->persist($entity);
        return $entity;
    }
}