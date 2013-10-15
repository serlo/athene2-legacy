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
use LearningResource\Plugin\Link;

class ManyToOneMapper extends AbstractMapper
{

    public static function add(EntityServiceInterface $from, EntityServiceInterface $to, $fromScope, $toScope)
    {        
        $domesticType = $from->getType()->getName();
        $foreignType = $to->getType()->getName();        
        
        $fromMethods = self::getMethods($from, $fromScope, false);
        $toMethods = self::getMethods($to, $toScope, true);
        
        $to->$toScope()->getLinkService()->$toMethods['add']($from->getEntity());
        
        return true;
    }
}