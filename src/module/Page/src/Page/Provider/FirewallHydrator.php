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
namespace Page\Provider;

use Page\Entity\PageRepositoryInterface;
use Page\Exception;
use Page\Manager\PageManager;
use Zend\Http\Request;
use Language\Manager\LanguageManager;

class FirewallHydrator
{
    
    use \Page\Manager\PageManagerAwareTrait;
    use  \Language\Manager\LanguageManagerAwareTrait;

    public function getRoles()
    {
        /*
        $language_id = $this->getLanguageManager()->getLanguageFromRequest()
            ->getId();
        
        
        $request = new Request();
        $request->setMethod(Request::METHOD_GET);
        $request->setUri($source);
        
        $routeMatch = $router
        ->match($request);
        $this->getServiceLocator()->get('Application')->getMvcEvent()->setRouteMatch($routeMatch);
        
        if ($routeMatch === NULL)
            throw new Alias\Exception\RuntimeException(sprintf('Could not match a route for `%s`', $source));
        
        $params = $routeMatch->getParams();
        
        $slug = $params['slug'];
        
        
        
        
        $this->getPageManager()->findPageRepositoryBySlug($slug, $language_id);
        */
        return array(
            'sysadmin'
        // 'roles' => $this->getObject()->getRoles(),
                );
    }

    protected function validObject($object)
    {
        if (! $object instanceof PageRepositoryInterface)
            throw new Exception\InvalidArgumentException(sprintf('Expected PostInterface but got `%s`', get_class($object)));
    }
}