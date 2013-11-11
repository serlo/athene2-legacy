<?php

return array(
    'zfcrbac' => array(
        'firewalls' => array(
            'ZfcRbac\Firewall\Controller' => array(
                array(
                    'controller' => 'Discussion\Controller\DiscussionController',
                    'actions' => array(
                        'start',
                        'comment',
                        'vote'
                    ),
                    'roles' => 'login'
                ),
                array(
                    'controller' => 'Discussion\Controller\DiscussionController',
                    'actions' => array(
                        'archive',
                        'trash'
                    ),
                    'roles' => 'moderator'
                ),
            )
        )
    )
);