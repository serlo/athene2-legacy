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
namespace User\View\Helper;

use Zend\View\Helper\AbstractHelper;
use ZfcRbac\Service\Rbac as ZfcRbac;
use Zend\Mvc\Router\RouteInterface;
use Zend\Http\Request;

class Rbac extends AbstractHelper
{

    /**
     *
     * @var RouteInterface
     */
    protected $router;

    /**
     *
     * @var ZfcRbac
     */
    protected $rbac;

    /**
     *
     * @return ZfcRbac $rbac
     */
    public function getRbac()
    {
        return $this->rbac;
    }

    /**
     *
     * @param ZfcRbac $rbac            
     * @return $this
     */
    public function setRbac(ZfcRbac $rbac)
    {
        $this->rbac = $rbac;
        return $this;
    }

    /**
     *
     * @return RouteInterface $router
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     *
     * @param RouteInterface $router            
     * @return $this
     */
    public function setRouter(RouteInterface $router)
    {
        $this->router = $router;
        return $this;
    }

    public function __invoke()
    {
        return $this;
    }

    public function hasAccessToUrl($url)
    {
        $request = new Request();
        $request->setUri($url);
        
        $routeMatch = $this->getRouter()->match($request);
        return $this->getRbac()
            ->getFirewall('controller')
            ->isGranted($routeMatch->getParam('controller', '') . ':' . $routeMatch->getParam('action', ''));
    }

    public function hasAccessToController($controller, $action)
    {
        return $this->getRbac()
            ->getFirewall('controller')
            ->isGranted($controller . ':' . $action);
    }

    public function hasAccessToRoute($route)
    {
        return $this->getRbac()
            ->getFirewall('route')
            ->isGranted($route);
    }
}