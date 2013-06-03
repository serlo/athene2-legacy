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

namespace Entity;

return array(
    'di' => array(
        'allowed_controllers' => array(
            'Application\LearningObject\Exercise\Controller\TextExerciseController',
            'Application\LearningObject\Solution\Controller\TextSolutionController',
        ),
        'definition' => array(
            'class' => array(
                'Application\LearningObject\Solution\Controller\TextSolutionController' => array(
                    'setEntityManager' => array(
                        'required' => 'true'
                    ),
                ),
                'Application\LearningObject\Exercise\Controller\TextExerciseController' => array(
                    'setEntityManager' => array(
                        'required' => 'true'
                    ),
                ),
            )
        ),
        'instance' => array(
            'preferences' => array(
                'Entity\EntityManagerInterface' => 'Entity\EntityManager'
            ),
        )
    ),
    'acl' => array(
        'Application\LearningObject\Exercise\Controller\TextExerciseController' => array(
            'guest' => 'deny',
            'login' => 'allow',
            'login' => array(
                'purge-revisions' => 'deny',
            ),
        )
    ),
    'router' => array(
        'routes' => array(
            'Application\LearningObject\Exercise\TextExercise' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/entity/exercise/text/:action[/:id[/:revisionId]]',
                    'defaults' => array(
                        'controller' => 'Application\LearningObject\Exercise\Controller\TextExerciseController',
                        'action' => 'index'
                    ),
                ),
            ),
            'Application\LearningObject\Solution\TextSolution' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/entity/solution/text/:action[/:id[/:revisionId]]',
                    'defaults' => array(
                        'controller' => 'Application\LearningObjects\Solution\Controller\TextSolutionController',
                        'action' => 'index'
                    ),
                )
            ),
        )
    ),
);