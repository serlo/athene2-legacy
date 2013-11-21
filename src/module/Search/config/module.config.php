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
namespace Search;

use Zend\ServiceManager\ServiceLocatorInterface;
return array(
    'service_manager' => array('factories' => array(
        'Foolz\SphinxQL\Connection' => function(ServiceLocatorInterface $serviceLocator){
            $config = $serviceLocator->get('config');
            $config = $config['sphinx'];
            $instance = new \Foolz\SphinxQL\Connection();
            $instance->setConnectionParams($config['host'], $config['port']);
            return $connection;
        }
    ))
);