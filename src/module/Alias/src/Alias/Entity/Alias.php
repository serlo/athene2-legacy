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
namespace Alias\Entity;

use Doctrine\ORM\Mapping as ORM;
use Language\Entity\LanguageInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="alias")
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
     *
     * @return int $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @return string $alias
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     *
     * @return string $source
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     *
     * @return LanguageInterface $language
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     *
     * @param string $alias            
     * @return $this
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
        return $this;
    }

    /**
     *
     * @param string $source            
     * @return $this
     */
    public function setSource($source)
    {
        $this->source = $source;
        return $this;
    }

    /**
     *
     * @param LanguageInterface $language            
     * @return $this
     */
    public function setLanguage(LanguageInterface $language)
    {
        $this->language = $language;
        return $this;
    }
}