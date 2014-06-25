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
     * @var Collection|ResultInterface[]
     */
    protected $results;

    public function __construct()
    {
        $this->results = new ArrayCollection();
    }

    /**
     * {@inheritDoc}
     */
    public function addResult(ResultInterface $result)
    {
        $this->results->add($result);
    }

    /**
     * {@inheritDoc}
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        $array = [];
        foreach ($this->results as $result) {
            $array[] = $result->toArray();
        }
        return $array;
    }
}
