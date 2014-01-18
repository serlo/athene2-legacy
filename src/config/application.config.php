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
return array(
    // This should be an array of module namespaces used in the application.
    'modules'                 => array(
        'DoctrineModule',
        'DoctrineORMModule',
        'ZfcBase',
        'ZfcRbac',
        'TwbBundle',
        'ZendDeveloperTools',
        'AsseticBundle',
        'Common',
        'ClassResolver',
        'Application',
        'Ui',
        'Admin',
        'User',
        'Versioning',
        'Entity',
        'Link',
        'Subject',
        'Term',
        'Uuid',
        'Language',
        'Event',
        'Mailman',
        'Alias',
        'Token',
        'Discussion',
        'Page',
        'Blog',
        'Upload',
        'RelatedContent',
        'Contexter',
        'Flag',
        'Search',
        'Metadata',
        'License',
        'Normalizer',
        'Type',
        'Markdown',
        'Authorization',
        'Taxonomy',
        'Notification'
    ),
    // These are various options for the listeners attached to the ModuleManager
    'module_listener_options' => array(
        // This should be an array of paths in which modules reside.
        // If a string key is provided, the listener will consider that a module
        // namespace, the value of that key the specific path to that module's
        // Module class.
        'module_paths'      => array(
            __DIR__ . '/../module',
            __DIR__ . '/../vendor'
        ),
        // An array of paths from which to glob configuration files after
        // modules are loaded. These effectively overide configuration
        // provided by modules themselves. Paths may use GLOB_BRACE notation.
        'config_glob_paths' => array(
            'config/autoload/{,*.}{global,local}.php',
            'config/instance/{,*.}{global,local}.php',
            'config/instance/navigation/*.php',
            'config/instance/firewall/*.php',
        ),
        // Whether or not to enable a configuration cache.
        // If enabled, the merged configuration will be cached and used in
        // subsequent requests.
        //'config_cache_enabled' => $booleanValue,

        // The key used to create the configuration cache file name.
        //'config_cache_key' => $stringKey,

        // Whether or not to enable a module class map cache.
        // If enabled, creates a module class map cache which will be used
        // by in future requests, to reduce the autoloading process.
        //'module_map_cache_enabled' => $booleanValue,

        // The key used to create the class map cache file name.
        //'module_map_cache_key' => $stringKey,

        // The path in which to cache merged configuration.
        //'cache_dir' => $stringPath,

        // Whether or not to enable modules dependency checking.
        // Enabled by default, prevents usage of modules that depend on other modules
        // that weren't loaded.
        // 'check_dependencies' => true,
    ),
    // Used to create an own service manager. May contain one or more child arrays.
    //'service_listener_options' => array(
    //     array(
    //         'service_manager' => $stringServiceManagerName,
    //         'config_key'      => $stringConfigKey,
    //         'interface'       => $stringOptionalInterface,
    //         'method'          => $stringRequiredMethodName,
    //     ),
    // )

    // Initial configuration with which to seed the ServiceManager.
    // Should be compatible with Zend\ServiceManager\Config.
    // 'service_manager' => array(),
);
