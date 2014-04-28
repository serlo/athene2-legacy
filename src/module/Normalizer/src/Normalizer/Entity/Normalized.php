<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace Normalizer\Entity;

use DateTime;

class Normalized implements NormalizedInterface
{

    /**
     * @var string
     */
    protected $routeName;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var array
     */
    protected $routeParams;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $preview;

    /**
     * @var DateTime
     */
    protected $timestamp;

    /**
     * @var string
     */
    protected $content;

    /**
     * @var int
     */
    protected $id;

    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return void
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    public function getPreview()
    {
        return $this->preview;
    }

    /**
     * @param string $preview
     * @return void
     */
    public function setPreview($preview)
    {
        $this->preview = $preview;
    }

    public function getRouteName()
    {
        return $this->routeName;
    }

    /**
     * @param string $routeName
     * @return void
     */
    public function setRouteName($routeName)
    {
        $this->routeName = $routeName;
    }

    public function getRouteParams()
    {
        return $this->routeParams;
    }

    /**
     * @param multitype : $routeParams
     * @return void
     */
    public function setRouteParams($routeParams)
    {
        $this->routeParams = $routeParams;
    }

    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param DateTime $timestamp
     * @return void
     */
    public function setTimestamp(DateTime $timestamp)
    {
        $this->timestamp = $timestamp;
    }

    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }
}