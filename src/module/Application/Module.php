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
namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\I18n\Translator\Translator;
use Zend\EventManager\Event;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $app      = $e->getTarget();
        $serviceManager       = $app->getServiceManager();
        
        // View Exception
        //$serviceManager->get('Zend\Mvc\View\Http\ExceptionStrategy')->attach($app->getEventManager(), 1);
        //$serviceManager->get('Zend\Mvc\View\Http\InjectViewModelListener')->attach($app->getEventManager(), -100);
        
        
        // Load translator
        
        //$lm = $e->getApplication()->getServiceManager()->get('Language\Manager\LanguageManager');
        //$code = $lm->getRequestLanguage()->getCode();
        
        $translator = $serviceManager->get('translator');
        $translator->addTranslationFile('PhpArray', __DIR__.'/language/routes/de.php', 'default', 'de');
        $translator->setLocale('de');
        
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        // Route translator
        $app->getEventManager()->attach('route', array($this, 'onPreRoute'), 4);
    }
    
    public function onPreRoute($e){
        $app      = $e->getTarget();
        $serviceManager       = $app->getServiceManager();
        $serviceManager->get('router')->setTranslator($serviceManager->get('translator'));
    }

    public function getConfig()
    {
        $config = array_merge_recursive(
            include __DIR__ . '/config/module.config.php',
            //include __DIR__ . '/config/subject/module.config.php',
            include __DIR__ . '/config/taxonomy/module.config.php',
            include __DIR__ . '/config/entity/module.config.php'
        );
        return $config; 
    }
    

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
