<?php
namespace Core;

return array(
    'view_helpers' => array(
        'invokables' => array(
            'modal' => __NAMESPACE__ . '\View\Helper\Modal',
            'renderTitle' => __NAMESPACE__ . '\View\Helper\Title',
            'dateFormat' => __NAMESPACE__ . '\View\Helper\DateFormat'
        )
    ),
    'di' => array(
        'definition' => array(
            'class' => array(
                'Core\Service\NonDefaultLanguageService' => array(
                ),
                'Core\Service\LanguageManager' => array(
                    'setEntityManager' => array(
                        'required' => 'true'
                    ),
                )
            )
        ),
        'instance' => array(
            'alias' => array(
                'ServiceManager' => 'Zend\ServiceManager\ServiceManager',
            ),
        )
    ),
    'service_manager' => array(
        'invokables' => array(
            'Core\Service\SubjectService' => 'Core\Service\SubjectService'
        ),
    	'factories' => array(
            'Core\Service\LanguageService' => function($sm){
            	$lm = $sm->get('Core\Service\LanguageManager');
            	return $lm->getRequestLanguage();
            }
    	)
    ),
    'controller_plugins' => array(
        'invokables' => array(
            'getParam' => 'Core\Controller\Plugin\GetParam',
            'getParams' => 'Core\Controller\Plugin\GetParams',
            'getDateFormat' => 'Core\Controller\Plugin\DateFormat',
            'translate' => 'Core\Controller\Plugin\Translate',
            'goBack' => 'Core\Controller\Plugin\GoBack',
            'title' => 'Core\Controller\Plugin\Title'
        )
    ),
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    __DIR__ . '/../src/' . __NAMESPACE__ . '/Entity'
                )
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                )
            )
        )
    )
);