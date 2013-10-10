<?php
/**
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Ui;

/**
 * @codeCoverageIgnore
 */
class Module
{

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap(\Zend\Mvc\MvcEvent $e)
    {
        $e->getApplication()
            ->getEventManager()
            ->getSharedManager()
            ->attach('Zend\View\Helper\Navigation\AbstractHelper', 'isAllowed', array(
            'Ui\Listener\AcListener',
            'accept'
        ));
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                )
            )
        );
    }
}
