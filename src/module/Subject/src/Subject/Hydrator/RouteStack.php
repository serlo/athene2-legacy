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
namespace Subject\Hydrator;

use Zend\Mvc\Router\RouteStackInterface;

class RouteStack implements HydratorInterface
{
    
    use\Subject\Manager\SubjectManagerAwareTrait;

    public function hydrate($router)
    {
        if (! $router instanceof RouteStackInterface)
            throw new \InvalidArgumentException('Requires `RouteStackInterface` but got' . get_class($router));
        
        $routes = array();
        foreach ($this->getSubjectManager()->getAllSubjects() as $subject) {
            $routes = array_merge($routes, include $this->path . $subject->getName() . '/routes.config.php');
        }
        $router->addRoutes($routes);
    }
}