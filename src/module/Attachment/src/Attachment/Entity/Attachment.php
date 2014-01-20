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

use Doctrine\ORM\Mapping as ORM;
use Language\Entity\LanguageInterface;
use Uuid\Entity\UuidEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="attachment")
 */
class Attachment extends UuidEntity implements AttachmentInterface
{

    /**
     * @ORM\Id
     * @ORM\OneToOne(targetEntity="Uuid\Entity\Uuid", inversedBy="attachment", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="id", referencedColumnName="id")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Language\Entity\Language")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id")
     */
    protected $language;

    /**
     * @ORM\OneToMany(targetEntity="File", mappedBy="attachment")
     * @ORM\OrderBy({"id" = "ASC"})
     */
    protected $files;

    public function getId()
    {
        return $this->id;
    }

    public function getLanguage()
    {
        return $this->language;
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

    public function setLanguage(LanguageInterface $language)
    {
        $this->language = $language;

        return $this;
    }
}
