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
use ZfcRbac\Service\Rbac;

class AcListener
{
    /**
     * 
     * @var Rbac
     */
    protected static $rbac;
    

    /**
     * @return Rbac $rbac
     */
    public static function getRbacService ($default = NULL)
    {
        if(!self::$rbac){
            return $default;
        }
        return self::$rbac;
    }

	/**
     * @param Rbac $rbac
     * @return $this
     */
    public static function setRbacService (Rbac $rbac)
    {
        self::$rbac = $rbac;
    }

	public static function accept(Event $event)
    {
        
        $event->stopPropagation();
        
        $serviceLocator = $event->getTarget()
            ->getServiceLocator()
            ->getServiceLocator();
        
        $accepted = true;
        
        /* @var $rbacService Rbac */
        $rbacService = self::getRbacService($serviceLocator->get('ZfcRbac\Service\Rbac'));
        
        $params = $event->getParams();
        $page = $params['page'];
        
        $permission = $page->getPermission();
        
        if ($permission) {
            $accepted = $rbacService->isGranted($permission);
        }
        
        return $accepted;
    }
}