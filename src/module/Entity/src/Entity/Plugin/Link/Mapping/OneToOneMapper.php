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
use Entity\Plugin\Link;

class OneToOneMapper extends AbstractMapper
{

    public static function add(EntityServiceInterface $from, EntityServiceInterface $to, $fromScope, $toScope)
    {        
        $domesticType = $from->getType()->getName();
        $foreignType = $to->getType()->getName();    
        $fromOwning = $from->$fromScope()->getOption('types')[$foreignType]['owning'];
        $toOwning = $to->$toScope()->getOption('types')[$domesticType]['owning'];    
        
        
        if (! ($fromOwning ^ $toOwning))
            throw new Link\Exception\RuntimeException(sprintf('One side should be owning and one shouldn\t.'));
        
        $fromMethods = self::getMethods($from, $fromScope, $fromOwning);
        $toMethods = self::getMethods($to, $toScope, $toOwning);

        if($to->$toScope()->$toMethods['has']((array) $domesticType)){
            throw new Link\Exception\RuntimeException('Object found on one-to-one association. Can\'t associate another object');
        } else {
            $to->$toScope()->getLinkService()->$toMethods['add']($from->getEntity());
        }
        
        return true;
    }
}