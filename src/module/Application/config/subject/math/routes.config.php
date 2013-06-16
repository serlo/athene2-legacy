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
    'math' => array(
        'may_terminate' => true,
        'type' => 'Zend\Mvc\Router\Http\Segment',
        'options' => array(
            'route' => 'math[/]',
            'defaults' => array(
                'controller' => 'Subject\Math\Controller\IndexController',
                'action' => 'index',
                'subject' => 'math'
            )
        ),
        'child_routes' => array(
            'home' => array(
                'may_terminate' => true,
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => 'home[/]',
                    'defaults' => array(
                        'controller' => 'Application\Subject\DefaultSubject\Controller\TopicController',
                        'action' => 'index'
                    ),
                )
            ),
            'topic' => array(
                'may_terminate' => true,
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => 'topic/:path',
                    'defaults' => array(
                        'controller' => 'Application\Subject\DefaultSubject\Controller\TopicController',
                        'action' => 'index'
                    ),
                    'constraints' => array(
                        'path' => '(.)+'
                    )
                )
            ),
            'exercise' => array(
                'may_terminate' => true,
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => 'exercise/:action[/:id[/:revisionId]]',
                    'defaults' => array(
                        'controller' => 'Application\Subject\DefaultSubject\Controller\TextExerciseController',
                        'action' => 'index'
                    )
                )
            )
        )
    )
);