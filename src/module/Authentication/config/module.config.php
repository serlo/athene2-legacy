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
namespace Authentication;

return array(
    'service_manager' => array(
        'factories' => array(
            'Zend\Authentication\AuthenticationService'   => __NAMESPACE__ . '\Factory\AuthenticationServiceFactory',
            __NAMESPACE__ . '\Storage\UserSessionStorage' => __NAMESPACE__ . '\Factory\UserSessionStorageFactory',
            __NAMESPACE__ . '\HashService'                => __NAMESPACE__ . '\Factory\HashServiceFactory'
        )
    ),
    'di'              => array(
        'instance' => array(
            'preferences' => array(
                __NAMESPACE__ . '\HashServiceInterface'     => __NAMESPACE__ . '\HashService',
                __NAMESPACE__ . '\Adapter\AdapterInterface' => __NAMESPACE__ . '\Adapter\UserAuthAdapter',
            )
        )
    )
);
