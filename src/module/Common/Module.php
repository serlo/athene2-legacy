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
namespace Common;

use Zend\Mvc\MvcEvent;

class Module {
	public function getConfig() {
		return include __DIR__ . '/config/module.config.php';
	}
	public function getAutoloaderConfig() {
		return array (
				'Zend\Loader\StandardAutoloader' => array (
						'namespaces' => array (
								__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__ 
						) 
				) 
		);
	}
	
	/**
	 * Deprecated due to ZFCRbac Service
	 */
	/*public function onBootstrap(MvcEvent $e) {
		$app = $e->getTarget ();
		$sm = $app->getServiceManager ();
		$rbacService = $sm->get ( 'ZfcRbac\Service\Rbac' );
		$strategy = $sm->get ( 'ZfcRbac\View\UnauthorizedStrategy' );
		$config = $sm->get ( 'config' );
		
		
		if ($rbacService->getOptions ()->getFirewallController ()) {
			$app->getEventManager ()->attach ( 'route', array (
					'Common\Firewall\Listener\HydratableController',
					'onRoute' 
			), - 1000 );
		}
		
		$app->getEventManager ()->attach ( $strategy );
	}*/
}


