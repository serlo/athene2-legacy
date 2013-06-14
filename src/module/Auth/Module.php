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
namespace Auth;

use Zend\ModuleManager\ModuleManager;

class Module
{

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
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
        /*$moduleManager->getEventManager()
            ->getSharedManager()
            ->attach('Zend\Mvc\Controller\AbstractActionController', 'dispatch', array(
            $this,
            'mvcPreDispatch'
        ), 100);*/
    }

    public function mvcPreDispatch($event)
    {
        $application = $event->getParam('application');
        $modules = $event->getParam('modules');
        
        $sm = $application->getServiceManager();
        
        $route = $event->getRouteMatch();
        $controller = $route->getParam('controller');
        $action = $route->getParam('action');
        
        $auth = $sm->get('Auth\Service\AuthService');
        
        $auth->setController($event->getTarget());
        $resource = $controller . 'Controller';
        $privilege = $action . 'Action';
        
        $config = $sm->get('config');
        $auth->addPermissions($config['acl']);
        
        $hasAccess = false;
        if ($auth->hasResource($controller)) {
            $hasAccess = $auth->hasAccess($controller, $privilege);
        } else 
            if ($auth->hasResource($resource)) {
                $hasAccess = $auth->hasAccess($resource, $privilege);
            } else {
                $hasAccess = true;
            }
        
        if ($hasAccess == FALSE) {
            if ($auth->loggedIn()) {
                $event->getTarget()
                    ->getResponse()
                    ->setStatusCode(403);
                throw new \Exception('Du hast nicht die erforderlichen Rechte, um diese Seite zu sehen.');
            } else {
                $event->getTarget()
                    ->flashMessenger()
                    ->addSuccessMessage("Um diese Aktion auszuführen, musst du eingeloggt sein!");
                
                
                $uri = $event->getRequest()->getServer('REQUEST_URI');
                $url = $event->getTarget()
                    ->url()
                    ->fromRoute('login');
                
                $response = $event->getResponse();
                
                $response = $event->getResponse();
                $response->getHeaders()->addHeaderLine('Location', $url.'?ref='.$uri);
                $response->setStatusCode(302);
                $response->sendHeaders();
                exit();
            }
        }
    }
}