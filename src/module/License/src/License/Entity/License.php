<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace License\Entity;

use Doctrine\ORM\Mapping as ORM;
use Instance\Entity\InstanceAwareTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="license")
 */
class License implements LicenseInterface
{
    use InstanceAwareTrait;

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
     * @param mixed $iconHref
     * @return void
     */
    public function setIconHref($iconHref)
    {
        $this->iconHref = $iconHref;
    }

    /**
     * @return field_type $iconHref
     */
    public function getIconHref()
    {
        return $this->iconHref;
    }

    /**
     * @return field_type $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return field_type $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return field_type $url
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return field_type $content
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param field_type $title
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param field_type $url
     * @return self
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @param field_type $content
     * @return self
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }
}