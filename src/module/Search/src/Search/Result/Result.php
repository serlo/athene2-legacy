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
namespace Search\Result;

class Result implements ResultInterface
{

    /**
     *
     * @var string
     */
    protected $name;

    /**
     *
     * @var int
     */
    protected $id;

    /**
     *
     * @var array
     */
    protected $routeParams;

    /**
     *
     * @var string
     */
    protected $routeName;

    /**
     *
     * @var mixed
     */
    protected $object;

    public function getRouteParams()
    {
        return $this->routeParams;
    }

    public function getRouteName()
    {
        return $this->routeName;
    }

    public function getObject()
    {
        return $this->object;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @param string $name            
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     *
     * @param number $id            
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     *
     * @param array $routeParams            
     * @return $this
     */
    public function setRouteParams(array $routeParams)
    {
        $this->routeParams = $routeParams;
        return $this;
    }

    /**
     *
     * @param string $routeName            
     * @return $this
     */
    public function setRouteName($routeName)
    {
        $this->routeName = $routeName;
        return $this;
    }

    public function setObject($object)
    {
        $this->object = $object;
        return $this;
    }
}