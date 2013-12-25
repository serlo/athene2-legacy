<?php
/**
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org]
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL]
 */
namespace Versioning;

return [
    'class_resolver' => [
        'Versioning\Service\RepositoryServiceInterface' => 'Versioning\Service\RepositoryService'
    ],
    'di' => [
        'definition' => [
            'class' => [
                'Versioning\RepositoryManager' => [
                    'setServiceLocator' => [
                        'required' => true
                    ],
                    'setClassResolver' => [
                        'required' => true
                    ]
                ],
                'Versioning\Service\RepositoryService' => [
                    'setUuidManager' => [
                        'required' => true
                    ],
                    'setObjectManager' => [
                        'required' => true
                    ]
                ]
            ]
        ],
        'instance' => [
            'preferences' => [
                'Versioning\RepositoryManagerInterface' => 'Versioning\RepositoryManager'
            ],
            'Versioning\Service\RepositoryService' => [
                'shared' => false
            ]
        ]
    ]
];
