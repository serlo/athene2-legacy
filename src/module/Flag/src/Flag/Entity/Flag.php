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
namespace Flag\Entity;

use Doctrine\ORM\Mapping as ORM;
use Uuid\Entity\UuidInterface;
use User\Entity\UserInterface;
use Language\Entity\LanguageInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="flag")
 */
class Flag implements FlagInterface
{
    use\Type\Entity\TypeAwareTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Language\Entity\Language")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id")
     */
    protected $language;

    /**
     * @ORM\ManyToOne(targetEntity="Uuid\Entity\Uuid", inversedBy="flags")
     * @ORM\JoinColumn(name="uuid_id", referencedColumnName="id")
     */
    protected $object;

    /**
     * @ORM\ManyToOne(targetEntity="User\Entity\User")
     */
    protected $reporter;

    /**
     * @ORM\Column(type="string")
     */
    protected $content;

    /**
     * @ORM\Column(type="datetime", name="`timestamp`", options={"default"="CURRENT_TIMESTAMP"})
     */
    protected $timestamp;
    
    public function getLanguage()
    {
        return $this->language;
    }

    public function getTimestamp()
    {
        return $this->timestamp;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getObject()
    {
        return $this->object;
    }

    public function getReporter()
    {
        return $this->reporter;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setObject(UuidInterface $object)
    {
        $this->object = $object;
        return $this;
    }

    public function setReporter(UserInterface $reporter)
    {
        $this->reporter = $reporter;
        return $this;
    }

    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }
    
    public function setLanguage(LanguageInterface $language)
    {
        $this->language = $language;
        return $this;
    }
}