<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace Attachment\Entity;

use Instance\Entity\InstanceAwareInterface;
use Type\Entity\TypeAwareInterface;

interface ContainerInterface extends InstanceAwareInterface, TypeAwareInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return FileInterface[]
     */
    public function getFiles();

    /**
     * @param FileInterface $file
     * @return void
     */
    public function addFile(FileInterface $file);

    /**
     * @return FileInterface
     */
    public function getFirstFile();
}
