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
namespace Entity\Plugin\Link\Mapping;

use Entity\Service\EntityServiceInterface;

abstract class AbstractMapper implements Mapper
{
    protected static function getMethods(EntityServiceInterface $entity, $scope, $owning)
    {
        if ($owning) {
            return array(
                'has' => 'hasChild',
                'add' => 'addChild',
                'remove' => 'removeChild'
            );
        } else {
            return array(
                'has' => 'hasParent',
                'add' => 'addParent',
                'remove' => 'removeParent'
            );
        }
    }

    public static function remove(EntityServiceInterface $entity, EntityServiceInterface $from, $fromScope, $toScope)
    {        
        $domesticType = $entity->getType()->getName();
        $foreignType = $from->getType()->getName();        
        
        $fromMethods = self::getMethods($entity, $fromScope, false);
        $toMethods = self::getMethods($from, $toScope, true);
        
        $entity->$fromScope()->getLinkService()->$fromMethods['remove']($from->getEntity());
        
        return true;
    }
}