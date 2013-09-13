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
namespace AtheneTest;

use Zend\Loader\AutoloaderFactory;
use Zend\Mvc\Service\ServiceManagerConfig;
use Zend\ServiceManager\ServiceManager;
use RuntimeException;
use Zend\Stdlib\ArrayUtils;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
error_reporting(E_ALL | E_STRICT);
chdir(__DIR__);

/**
 * Test
 * bootstrap,
 * for
 * setting
 * up
 * autoloading
 */
class Bootstrap
{

    protected static $serviceManager;

    protected static $testingNamespaces = array(
        'VersioningTest' => 'Versioning/test/VersioningTest',
        'UserTest' => 'User/test/UserTest',
        'UuidTest' => 'Uuid/test/UuidTest',
        'TermTest' => 'Term/test/TermTest',
        'TaxonomyTest' => 'Taxonomy/test/TaxonomyTest'
    );

    protected static $modules = array(
        'Application',
        'AsseticBundle',
        'DoctrineModule',
        'DoctrineORMModule',
        'ZfcRbac',
        'TwbBundle',
        'ZfcBase',
        'Core',
        'Auth',
        'User',
        'Versioning',
        'Editor',
        'Log',
        'Entity',
        'TwbBundle',
        'Taxonomy',
        'Navigation',
        'Link',
        'Subject',
        'Term',
        'Admin',
        'Uuid',
        'Common',
        'ClassResolver',
        'LearningResource',
        'Language'
    );

    public static $dir;

    public static function init ()
    {
        static::$dir = __DIR__;
        
        $zf2ModulePaths = array(
            dirname(dirname(static::$dir))
        );
        if (($path = static::findParentPath('vendor'))) {
            $zf2ModulePaths[] = $path;
        }
        if (($path = static::findParentPath('module')) !== $zf2ModulePaths[0]) {
            $zf2ModulePaths[] = $path;
        }
        
        static::initAutoloader();
        
        // use
        // ModuleManager
        // to
        // load
        // this
        // module
        // and
        // it's
        // dependencies
        $config = array(
            'module_listener_options' => array(
                'module_paths' => $zf2ModulePaths,
                'config_glob_paths' => array(
                    static::findParentPath('config/autoload') . '/{,*.}{global,local}.php'
                )
            ),
            'modules' => self::$modules
        );
        
        $serviceManager = new ServiceManager(new ServiceManagerConfig());
        $serviceManager->setService('ApplicationConfig', $config);
        $serviceManager->get('ModuleManager')->loadModules();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setAlias('Doctrine\ORM\EntityManager', 'doctrine.entitymanager.orm_test');
        $serviceManager->setAllowOverride(false);
        
        static::$serviceManager = $serviceManager;
    }

    public static function chroot ()
    {
        $rootPath = dirname(static::findParentPath('module'));
        chdir($rootPath);
    }

    public static function getServiceManager ()
    {
        return static::$serviceManager;
    }

    protected static function initAutoloader ()
    {
        $vendorPath = static::findParentPath('vendor');
        
        $zf2Path = getenv('ZF2_PATH');
        if (! $zf2Path) {
            if (defined('ZF2_PATH')) {
                $zf2Path = ZF2_PATH;
            } elseif (is_dir($vendorPath . '/ZF2/library')) {
                $zf2Path = $vendorPath . '/ZF2/library';
            } elseif (is_dir($vendorPath . '/zendframework/zendframework/library')) {
                $zf2Path = $vendorPath . '/zendframework/zendframework/library';
            }
        }
        
        if (! $zf2Path) {
            throw new RuntimeException('Unable to load ZF2. Run `php composer.phar install` or' . ' define a ZF2_PATH environment variable.');
        }
        
        if (file_exists($vendorPath . '/autoload.php')) {
            include $vendorPath . '/autoload.php';
        }
        
        include $zf2Path . '/Zend/Loader/AutoloaderFactory.php';
        
        $namespaces = array(
            __NAMESPACE__ => __DIR__
        );
        $modulePath = self::findParentPath('module');
        foreach (static::$testingNamespaces as $namespace => $path) {
            $namespaces[$namespace] = $modulePath . '/' . $path;
        }
        
        AutoloaderFactory::factory(array(
            'Zend\Loader\StandardAutoloader' => array(
                'autoregister_zf' => true,
                'namespaces' => $namespaces
            )
        ));
    }

    protected static function findParentPath ($path)
    {
        $dir = static::$dir;
        $previousDir = '.';
        while (! is_dir($dir . '/' . $path)) {
            $dir = dirname($dir);
            if ($previousDir === $dir)
                return false;
            $previousDir = $dir;
        }
        return $dir . '/' . $path;
    }
}

Bootstrap::init();
Bootstrap::chroot();