<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright   Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Mailman;

use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @codeCoverageIgnore
 */
return array(
    'mailman'         => array(
        'adapters' => array(
            'Mailman\Adapter\ZendMailAdapter'
        )
    ),
    'service_manager' => array(
        'factories' => array(
            'Mailman\Mailman' => function (ServiceLocatorInterface $sm) {
                    $mailman = new \Mailman\Mailman();
                    $mailman->setConfig($sm->get('config')['mailman']);
                    $mailman->setServiceLocator($sm);

                    return $mailman;
                }
        )
    ),
    'di'              => array(
        'definition' => array(
            'class' => array(
                'Mailman\Listener\UserControllerListener' => array(
                    'setMailman'    => array(
                        'required' => true
                    ),
                    'setTranslator' => array(
                        'required' => true
                    ),
                    'setRenderer'   => array(
                        'required' => true
                    )
                ),
                'Mailman\Listener\AuthenticationControllerListener' => array(
                    'setMailman'    => array(
                        'required' => true
                    ),
                    'setTranslator' => array(
                        'required' => true
                    ),
                    'setRenderer'   => array(
                        'required' => true
                    )
                ),
                'Mailman\Adapter\ZendMailAdapter'         => array(
                    'setSmtpOptions' => array(
                        'required' => true
                    )
                )
            )
        ),
        'instance'   => array(
            'preferences' => array(
                'Mailman\MailmanInterface' => 'Mailman\Mailman'
            )
        )
    )
);
