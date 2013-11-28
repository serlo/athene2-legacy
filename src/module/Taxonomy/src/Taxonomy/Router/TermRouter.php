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
namespace Taxonomy\Router;

use Taxonomy\Exception;
use Zend\Mvc\Router\RouteMatch;

class TermRouter implements TermRouterInterface
{
    use \Common\Traits\ConfigAwareTrait,\Zend\ServiceManager\ServiceLocatorAwareTrait,\Taxonomy\Manager\SharedTaxonomyManagerAwareTrait,\Common\Traits\RouterAwareTrait;

    protected function getDefaultConfig()
    {
        return array(
            'routes' => array()
        );
    }

    public function assemble($id)
    {
        $routeMatch = $this->route($id);
        return rawurldecode($this->getRouter()->assemble($routeMatch->getParams(), array(
            'name' => $routeMatch->getMatchedRouteName()
        )));
    }

    public function route($id)
    {
        $termService = $this->getSharedTaxonomyManager()->getTerm($id);
        $type = $termService->getTaxonomy()->getName();
        
        if (! array_key_exists($type, $this->getOption('routes')))
            throw new Exception\RuntimeException(sprintf('No route found for `%s`', $type));
        
        $options = $this->getOption('routes')[$type];
        /* @var $provider ParamProviderInterface */
        $provider = $this->getServiceLocator()->get($options['param_provider']);
        $provider->setObject($termService);
        
        $routeMatch = new RouteMatch($provider->getParams());
        $routeMatch->setMatchedRouteName($options['route']);
        return $routeMatch;
        return rawurldecode($this->getRouter()->assemble($provider->getParams(), $options['route']));
    }
}