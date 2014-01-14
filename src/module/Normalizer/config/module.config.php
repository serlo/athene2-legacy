<?php

/**
 * 
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author	Aeneas Rekkas (aeneas.rekkas@serlo.org]
 * @license	LGPL-3.0
 * @license	http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link		https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c] 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/]
 */
namespace Normalizer;

use Zend\ServiceManager\ServiceLocatorInterface;
use Normalizer\View\Helper\Normalize;
return [
    'view_helpers' => [
        'factories' => [
            'normalize' => function (ServiceLocatorInterface $serviceLocator)
            {
                $normalize = new Normalize();
                $normalize->setNormalizer($serviceLocator->getServiceLocator()
                    ->get('Normalizer\Normalizer'));
                return $normalize;
            }
        ]
    ],
    'di' => [
        'definition' => [
            'class' => [
                __NAMESPACE__ . '\Normalizer' => [
                    'setServiceLocator' => [
                        'required' => true
                    ]
                ]
            ]
        ],
        'instance' => [
            'preferences' => [
                __NAMESPACE__ . '\NormalizerInterface' => __NAMESPACE__ . '\Normalizer'
            ]
        ]
    ]
];