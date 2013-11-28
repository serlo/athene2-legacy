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
namespace Entity\Plugin\Aggregate\Aggregator;

use Entity\Service\EntityServiceInterface;

interface AggregatorInterface
{
    /**
     * 
     * @return string
     */
    public function getName();
    
    /**
     * 
     * @param EntityServiceInterface $entityService
     * @return $this
     */
    public function setObject(EntityServiceInterface $entityService);
    
    /**
     * 
     * @return ResultInterface[]
     */
    public function aggregate();
}