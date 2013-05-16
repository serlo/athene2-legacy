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
namespace Subject\Application\Factory;

use Subject\Service\SubjectServiceInterface;
use Subject\Application\Decorator\DecoratorInterface;

class AbstractFactory
{
    public function build(DecoratorInterface $decorator, SubjectServiceInterface $subjectService){
        if($subjectService instanceof DecoratorInterface)
            throw new \Exception('Ouch, this could get really really messy. Stop whatever you are doing and go to bed.');
            
        $decorator->setConcreteComponent($subjectService);
        return $decorator;
    }
}