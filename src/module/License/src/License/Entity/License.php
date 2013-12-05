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
namespace License\Entity;

use Doctrine\ORM\Mapping as ORM;
use Language\Entity\LanguageEntityInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="license")
 */
class License implements LicenseInterface
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @ORM\Column(type="string")
     */
    protected $url;

    /**
     * @ORM\Column(type="string")
     */
    protected $content;

    /**
     * @ORM\Column(type="string", name="icon_href")
     */
    protected $iconHref;

    /**
     * @ORM\ManyToOne(targetEntity="Language\Entity\LanguageEntity")
     */
    protected $language;

    /**
     *
     * @return field_type $iconHref
     */
    public function getIconHref()
    {
        return $this->iconHref;
    }

    /**
     *
     * @return field_type $language
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     *
     * @return field_type $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @return field_type $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     *
     * @return field_type $url
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     *
     * @return field_type $content
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     *
     * @param field_type $title            
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     *
     * @param field_type $url            
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     *
     * @param field_type $content            
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     *
     * @param field_type $iconHref            
     * @return $this
     */
    public function setIconHref($iconHref)
    {
        $this->iconHref = $iconHref;
        return $this;
    }

    /**
     *
     * @param field_type $language            
     * @return $this
     */
    public function setLanguage(LanguageEntityInterface $language)
    {
        $this->language = $language;
        return $this;
    }
}