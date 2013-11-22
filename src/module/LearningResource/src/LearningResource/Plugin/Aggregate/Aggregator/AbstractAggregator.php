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
namespace LearningResource\Plugin\Aggregate\Aggregator;

use Entity\Service\EntityServiceInterface;

abstract class AbstractAggregator implements AggregatorInterface
{

    /**
     *
     * @var EntityServiceInterface
     */
    protected $object;

    /**
     *
     * @return EntityServiceInterface $reference
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     *
     * @param EntityServiceInterface $reference            
     * @return $this
     */
    public function setObject(EntityServiceInterface $object)
    {
        $this->object = $object;
        return $this;
    }
}