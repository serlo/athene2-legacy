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

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getRouteName()
    {
        return $this->routeName;
    }

    public function getRouteParams()
    {
        return $this->routeParams;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getPreview()
    {
        return $this->preview;
    }

    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param string $routeName
     * @return self
     */
    public function setRouteName($routeName)
    {
        $this->routeName = $routeName;

        return $this;
    }

    /**
     * @param multitype : $routeParams
     * @return self
     */
    public function setRouteParams($routeParams)
    {
        $this->routeParams = $routeParams;

        return $this;
    }

    /**
     * @param string $title
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param string $preview
     * @return self
     */
    public function setPreview($preview)
    {
        $this->preview = $preview;

        return $this;
    }

    /**
     * @param DateTime $timestamp
     * @return self
     */
    public function setTimestamp(DateTime $timestamp)
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * @param string $content
     * @return self
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }
}