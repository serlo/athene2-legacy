<?php
namespace Auth;

use Zend\ModuleManager\ModuleManager;

class Module
{

    public function getConfig ()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig ()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                )
            )
        );
    }
    
    public function init(ModuleManager $moduleManager)
    {
    	$moduleManager->getEventManager()->getSharedManager()->attach('Zend\Mvc\Controller\AbstractActionController', 'dispatch', array($this, 'mvcPreDispatch'), 100);
    }
    
    public function mvcPreDispatch($event)
    {        
        
    	$application = $event->getParam('application');
    	$modules     = $event->getParam('modules');
    	
    	$sm = $application->getServiceManager();
    	
    	$route = $event->getRouteMatch();
    	$controller = $route->getParam('controller');
    	$action = $route->getParam('action');

    	$auth = $sm->get('Auth\Service\AuthService');
    	$acl = $sm->get('ACL');

    	$auth->setController($event->getTarget());
    	$resource = $controller . 'Controller';
    	$privilege = $action.'Action';
    	
    	$config = $sm->get('config');
    	$auth->addPermissions($config['acl']);
    	
    	if ( $acl->hasResource( $resource ) ) {
    	    $auth->hasAccess($resource, $privilege);
    	}
    }
}
?>

