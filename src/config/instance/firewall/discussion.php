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