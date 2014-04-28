<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace Search\Result;

class Result implements ResultInterface
{

    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var array
     */
    protected $routeParams;

    /**
     * @var string
     */
    protected $routeName;

    /**
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
     * @param string $name
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param number $id
     * @return void
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param array $routeParams
     * @return void
     */
    public function setRouteParams(array $routeParams)
    {
        $this->routeParams = $routeParams;
    }

    /**
     * @param string $routeName
     * @return void
     */
    public function setRouteName($routeName)
    {
        $this->routeName = $routeName;
    }

    /**
     * @param mixed $object
     * @return void
     */
    public function setObject($object)
    {
        $this->object = $object;
    }
}
