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
namespace Alias\Entity;

use Doctrine\ORM\Mapping as ORM;
use Language\Entity\LanguageInterface;
use Uuid\Entity\UuidInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="url_alias")
 */
class Alias implements AliasInterface
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    public $id;

    /**
     * @ORM\Column(type="text",length=255)
     */
    protected $alias;

    /**
     * @ORM\Column(type="text",length=255)
     */
    protected $source;

    /**
     * @ORM\ManyToOne(targetEntity="Language\Entity\Language")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id")
     */
    protected $language;

    /**
     * @ORM\OneToOne(targetEntity="Uuid\Entity\Uuid")
     * @ORM\JoinColumn(name="uuid_id", referencedColumnName="id")
     */
    protected $uuid;

    public function getLanguage()
    {
        return $this->language;
    }

    public function getObject()
    {
        return $this->uuid;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAlias()
    {
        return $this->alias;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }

    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    public function setObject(UuidInterface $uuid)
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function setLanguage(LanguageInterface $language)
    {
        $this->language = $language;

        return $this;
    }
}