<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author    Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license   LGPL-3.0
 * @license   http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link      https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */

namespace Alias\Factory;


use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class UrlHelperFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $helperPluginManager
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $helperPluginManager)
    {
        $serviceLocator = $helperPluginManager->getServiceLocator();
        $view_helper    = new \Alias\View\Helper\Url();

        $router = \Zend\Console\Console::isConsole() ? 'HttpRouter' : 'Router';
        $view_helper->setRouter($serviceLocator->get($router));

        $view_helper->setAliasManager($serviceLocator->get('Alias\AliasManager'));
        $view_helper->setInstanceManager($serviceLocator->get('Instance\Manager\InstanceManager'));

        $match = $serviceLocator->get('application')->getMvcEvent()->getRouteMatch();

        $interface = 'Zend\Mvc\Router\\' . (\Zend\Console\Console::isConsole() ? 'Console' :
                'Http') . '\RouteMatch';

        if ($match instanceof $interface) {
            $view_helper->setRouteMatch($match);
        }

        return $view_helper;
    }
}
