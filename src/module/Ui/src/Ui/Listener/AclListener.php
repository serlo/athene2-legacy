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
namespace Ui\Listener;

use Zend\EventManager\Event;

class AcListener
{

    public function accept(Event $event)
    {
        $event->stopPropagation();
        
        $serviceLocator = $event->getTarget()
            ->getServiceLocator()
            ->getServiceLocator();
        
        $accepted = true;
        
        /* @var $rbacService \ZfcRbac\Service\Rbac */
        $rbacService = $serviceLocator->get('ZfcRbac\Service\Rbac');
        
        $params = $event->getParams();
        $page = $params['page'];
        
        $permission = $page->getPermission();
        
        if ($permission) {
            $accepted = $rbacService->isGranted($permission);
        }
        
        return $accepted;
    }
}