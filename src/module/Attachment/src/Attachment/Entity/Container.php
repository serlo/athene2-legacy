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
namespace Attachment\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Instance\Entity\InstanceAwareTrait;
use Type\Entity\TypeAwareTrait;
use Uuid\Entity\UuidEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="attachment_container")
 */
class Container extends UuidEntity implements ContainerInterface
{
    use TypeAwareTrait;
    use InstanceAwareTrait;

    /**
     * @ORM\Id
     * @ORM\OneToOne(targetEntity="Uuid\Entity\Uuid", inversedBy="attachment", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="id", referencedColumnName="id")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="File", mappedBy="attachment")
     * @ORM\OrderBy({"id" = "ASC"})
     */
    protected $files;

    public function __construct()
    {
        $this->files = new ArrayCollection;
    }

    public function getFiles()
    {
        return $this->files;
    }

    public function getFirstFile()
    {
        return $this->files->first();
    }

    public function addFile(FileInterface $file)
    {
        $this->files->add($file);
    }
}
