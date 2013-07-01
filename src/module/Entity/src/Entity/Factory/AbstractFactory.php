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
namespace Entity\Factory;

use Core\Decorator\GraphDecoratorInterface;
use Entity\Service\EntityServiceInterface;

abstract class AbstractFactory
{
    /**
     * 
     * @param EntityServiceInterface $entityService
     * @returns EntityServiceInterface;
     */
    abstract public function build(EntityServiceInterface $entityService);
	
	/**
	 * 
	 * @param GraphDecorator $decorator
	 * @param EntityServiceInterface $entityService
	 * @throws \Exception
	 * @return GraphDecorator
	 */
    protected function inject(GraphDecoratorInterface $decorator, EntityServiceInterface $entityService){
        if($entityService instanceof GraphDecoratorInterface)
            throw new \Exception('Ouch, this could get really really messy. Stop whatever you are doing and go to bed.');
            
        $decorator->addComponent($entityService);
        return $decorator;
    }
}