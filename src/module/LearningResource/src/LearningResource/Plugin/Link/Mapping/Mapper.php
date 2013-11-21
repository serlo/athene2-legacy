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
namespace LearningResource\Plugin\Link\Mapping;

use Entity\Service\EntityServiceInterface;

interface Mapper
{

    /**
     *
     * @param EntityServiceInterface $from            
     * @param EntityServiceInterface $to            
     * @param string $fromScope            
     * @param string $toScope            
     * @return bool
     */
    public static function add(EntityServiceInterface $from, EntityServiceInterface $to, $fromScope, $toScope);
    public static function remove(EntityServiceInterface $entity, EntityServiceInterface $from, $fromScope, $toScope);
}