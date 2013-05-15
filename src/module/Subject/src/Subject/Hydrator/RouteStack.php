<?php
namespace Subject\Hydrator;

use Zend\Mvc\Router\RouteStackInterface;
use Subject\SubjectManagerAwareInterface;

class RouteStack implements HydratorInterface, SubjectManagerAwareInterface
{

    protected $subjectManager;
    
    /*
     * (non-PHPdoc) @see \Subject\SubjectManagerAwareInterface::getSubjectManager()
     */
    public function getSubjectManager()
    {
        return $this->subjectManager;
    }
    
    /*
     * (non-PHPdoc) @see \Subject\SubjectManagerAwareInterface::setSubjectManager()
     */
    public function setSubjectManager(\Subject\SubjectManagerInterface $subjectManager)
    {
        $this->subjectManager = $subjectManager;
        return $this;
    }

    public function hydrate($router)
    {
        if (! $router instanceof RouteStackInterface)
            throw new \InvalidArgumentException('Requires `RouteStackInterface` but got' . get_class($object));
        
        $routes = array();
        foreach ($this->getSubjectManager()->getAllSubjects() as $subject) {
            $routes = array_merge($routes, include __DIR__ . '/../../../config/routes/' . $subject->getName() . '.config.php');
        }
        $routes = array(
            'subject' => array(
                'may_terminate' => true,
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/subject[/]',
                    'defaults' => array(
                        'controller' => 'Subject\Controller\404',
                        'action' => 'index'
                    )
                ),
                'child_routes' => $routes
            )
        );
        $router->addRoutes($routes);
    }
}