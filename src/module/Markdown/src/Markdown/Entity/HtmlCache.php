<?php
/**
 * 
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author	    Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license	    LGPL-3.0
 * @license	    http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link		https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright	Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Markdown\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="html_cache")
 */
class HtmlCache implements CacheInterface
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
    protected $guid;

    /**
     * @ORM\Column(type="string")
     */
    protected $content;

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
     * @return string $guid
     */
    public function getGuid()
    {
        return $this->guid;
    }

    /**
     *
     * @return string $content
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     *
     * @param string $guid            
     * @return self
     */
    public function setGuid($guid)
    {
        $this->guid = $guid;
        return $this;
    }

    /**
     *
     * @param string $content            
     * @return self
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }
}