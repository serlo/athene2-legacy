<?php
namespace Log;

use Log\Service\LogService;

return array(
    'service_manager' => array(
        'factories' => array(
            'Log\Service\LogManager' => function  ($sm)
            {
                $ls = new LogService();
                
                $ls->addLogger('userLog', $sm->get('User\Service\UserLogService'));
                
                return $ls;
            }
        )
    )
);