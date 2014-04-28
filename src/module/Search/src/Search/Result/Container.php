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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Container implements ContainerInterface
{
    /**
     * @var Collection
     */
    protected $containers;

    /**
     * @var Collection
     */
    protected $results;

    /**
     * @var string
     */
    protected $name;

    public function getName()
    {
        return $this->name;
    }

    public function __construct()
    {
        $this->containers = new ArrayCollection();
        $this->results    = new ArrayCollection();
    }

    public function getContainers()
    {
        return $this->containers;
    }

    public function getResults()
    {
        return $this->results;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function addResult(ResultInterface $result)
    {
        $this->results->add($result);
    }

    public function addContainer(ContainerInterface $container)
    {
        $this->containers->add($container);
    }
}
