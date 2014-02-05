<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author    Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license   LGPL-3.0
 * @license   http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link      https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013-2014 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Navigation\Provider;

use Instance\Manager\InstanceManagerInterface;
use Navigation\Entity\PageInterface;
use Navigation\Entity\ParameterInterface;
use Navigation\Exception\ContainerNotFoundException;
use Navigation\Manager\NavigationManagerInterface;

class ContainerRepositoryContainerProvider implements ContainerProviderInterface
{
    /**
     * @var NavigationManagerInterface
     */
    protected $navigationManager;
    /**
     * @var InstanceManagerInterface
     */
    protected $instanceManager;

    public function __construct(
        InstanceManagerInterface $instanceManager,
        NavigationManagerInterface $navigationManager
    ) {
        $this->navigationManager = $navigationManager;
        $this->instanceManager   = $instanceManager;
    }

    public function provide($container)
    {
        $instance = $this->instanceManager->getInstanceFromRequest();
        $pages = [];

        try {
            $container = $this->navigationManager->findContainerByNameAndInstance($container, $instance);
        } catch (ContainerNotFoundException $e) {
            return [];
        }

        foreach ($container->getPages() as $page) {
            $addPage = $this->buildPage($page);

            $hasUri = isset($options['uri']);
            $hasMvc = isset($options['action']) || isset($options['controller'])
                || isset($options['route']);
            $hasProvider = isset($options['provider']);

            if($hasMvc || $hasMvc || $hasProvider){
                $pages[] = $addPage;
            }
        }

        return $pages;
    }

    protected function buildPage(PageInterface $page)
    {
        $config = [];

        foreach ($page->getChildren() as $child) {
            $config['pages'][] = $this->buildPage($child);
        }

        foreach ($page->getParameters() as $parameter) {
            $config = array_merge($config, $this->buildParameter($parameter));
        }

        return $config;
    }

    protected function buildParameter(ParameterInterface $parameter)
    {
        $config = [];
        $key    = $parameter->getKey() !== null ? (string)$parameter->getKey() : $parameter->getId();

        if ($parameter->hasChildren()) {
            foreach ($parameter->getChildren() as $child) {
                $config[$key] = $this->buildParameter($child);
            }
        } else {
            $config[$key] = $parameter->getValue();
        }

        return $config;
    }
}
