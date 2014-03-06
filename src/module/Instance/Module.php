<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright   Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Instance;

use Zend\I18n\Translator\Translator;
use Zend\I18n\Translator\TranslatorAwareInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{

    public function getAutoloaderConfig()
    {
        $autoloader = [];

        $autoloader['Zend\Loader\StandardAutoloader'] = [
            'namespaces' => [
                __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
            ]
        ];

        if (file_exists(__DIR__ . '/autoload_classmap.php')) {
            return [
                'Zend\Loader\ClassMapAutoloader' => [
                    __DIR__ . '/autoload_classmap.php',
                ]
            ];

        }

        return $autoloader;
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap(MvcEvent $e)
    {
        $app            = $e->getTarget();
        $serviceManager = $app->getServiceManager();

        $app->getEventManager()->attach('route', [$this, 'onPreRoute'], 4);
    }

    public function onPreRoute($e)
    {
        $app            = $e->getTarget();
        $serviceManager = $app->getServiceManager();

        /* @var $translator Translator */
        $translator = $serviceManager->get('translator');
        $router     = $serviceManager->get('router');

        /* @var $instanceManager Manager\InstanceManager */
        $instanceManager = $serviceManager->get('Instance\Manager\InstanceManager');
        $instance        = $instanceManager->getInstanceFromRequest();
        $language        = $instance->getLanguage();
        $code            = $language->getCode();
        $locale          = $language->getLocale() . '.UTF-8';

        if ($router instanceof TranslatorAwareInterface) {
            $router->setTranslator($translator);
        }

        setlocale(LC_ALL, $locale);
        $translator->addTranslationFile('PhpArray', __DIR__ . '/language/routes/' . $code . '.php', 'default', $code);
        $translator->setLocale($code);
        $translator->setFallbackLocale('default');

        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }
}