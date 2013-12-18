<?php
/**
 * 
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author  Jakob Pfab (jakob.pfab@serlo.org)
 * @author	Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license	LGPL-3.0
 * @license	http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link		https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Page\Provider;

use Page\Entity\PageRepositoryInterface;
use Page\Exception;
use Page\Manager\PageManager;
use Zend\Http\Request;
use Language\Manager\LanguageManager;
use Zend\Mvc\Router\RouteMatch;
use Doctrine\Common\Collections\ArrayCollection;
use Zend\Mvc\MvcEvent;


class FirewallHydrator
{
    
    use \Page\Manager\PageManagerAwareTrait;
	use \Zend\ServiceManager\ServiceLocatorAwareTrait;
	

	protected $event;
	

	public function __construct(MvcEvent $event){
	    $this->event=$event;

	    
	}
	
	
	public function getRoles()
    {
        $this->setPageManager($this->getServiceLocator()->get('Page\Manager\PageManager'));
        $routeMatch = $this->event->getRouteMatch();
        $id = $routeMatch->getParam('repositoryid');
        if ($id==null) $id = $routeMatch->getParam('id');
        $pageService =  $this->getPageManager()->getPageRepository($id);
 
        
        $allRoles= $pageService->findAllRoles();
        $array = array();
        $i =  1;
        while ($i<=$pageService->countRoles()){
            if ($pageService->hasRole($pageService->getRoleById($i))) $array[]=$pageService->getRoleById($i)->getName();
            $i++;
        }
        
        return $array;
    }
    

}