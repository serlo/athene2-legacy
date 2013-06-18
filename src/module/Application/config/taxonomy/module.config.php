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
return array(
    'router' => array(
        'routes' => array(
            'taxonomy' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/taxonomy/',
                    'defaults' => array(
                        'controller' => 'Application\Taxonomy\Controller\404',
                        'action' => 'index'
                    )
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'taxonomy' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => ':action/:id',
                            'defaults' => array(
                                'controller' => 'Application\Taxonomy\Controller\TaxonomyController'
                            )
                        )
                    ),
                    'term' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => 'term/:action/[:id]',
                            'defaults' => array(
                                'controller' => 'Application\Taxonomy\Controller\TermController',
                                'action' => 'index'
                            )
                        )
                    )
                )
            )
        )
        
    ),
    'di' => array(
        'allowed_controllers' => array(
            'Application\Taxonomy\Controller\TermController',
            'Application\Taxonomy\Controller\TaxonomyController'
        ),
        'definition' => array(
            'class' => array(
                'Application\Taxonomy\Controller\TermController' => array(
                    'setSharedTaxonomyManager' => array(
                        'required' => 'true'
                    )
                ),
                'Application\Taxonomy\Controller\TaxonomyController' => array(
                    'setSharedTaxonomyManager' => array(
                        'required' => 'true'
                    )
                )
            )
        ),
        'instance' => array(
            'preferences' => array(
                'Taxonomy\SharedTaxonomyManagerInterface' => 'Taxonomy\SharedTaxonomyManager'
            )
        )
    )
);
