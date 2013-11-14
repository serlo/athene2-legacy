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
namespace Common;

return array (
		'di' => array (
				'allowed_controllers' => array (
						__NAMESPACE__ . '\Controller\IndexController' 
				),
				'definition' => array (
						'class' => array (
								'Common\Firewall\HydratableController' => array (
										'setServiceLocator' => array (
												'required' => true 
										)
								) 
						) 
				) 
		),
		'view_helpers' => array (
				'invokables' => array () 
		),
		'controller_plugins' => array (
				'invokables' => array (
						'referer' => 'Common\Controller\Plugin\RefererProvider',
						'redirect' => 'Common\Controller\Plugin\RedirectHelper' 
				) 
		) 
);