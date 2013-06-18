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
    /*'navigation' => array(
        'default' => array(
            array(
                'label' => 'Mathe',
                'uri' => '#',
                'class' => 'Subject\Provider\SubjectProvider',
                'options' => array(
                    'subject' => 'math',
                    'inject' => 'default',
                    'path' => __DIR__
                ),
            )
        ),        
    ),*/
    'di' => array(
        'allowed_controllers' => array(
            'Application\Subject\DefaultSubject\Controller\TopicController',
            'Application\Subject\DefaultSubject\Controller\TextExerciseController'
        ),
        'definition' => array(
            'class' => array(
                'Application\Subject\DefaultSubject\Controller\TopicController' => array(
                    'setSubjectManager' => array(
                        'required' => 'true'
                    )
                ),
                'Application\Subject\DefaultSubject\Controller\TextExerciseController' => array(
                    'setSubjectManager' => array(
                        'required' => 'true'
                    ),
                    'setServiceLocator' => array(
                        'required' => 'true'
                    ),
                    'setEntityManager' => array(
                        'required' => 'true'
                    )
                )
            )
        )
    ),
);