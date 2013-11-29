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
    'navigation' => array(
        'default' => array(
            'restricted' => array(
                'label' => 'Backend',
                'uri' => '#',
                'pages' => array(
                    array(
                        'label' => 'Home',
                        'icon' => 'home',
                        'route' => 'backend'
                    ),
                    array(
                        'label' => 'Pages',
                        'route' => 'page',
                        'icon' => 'paperclip',
                    ),
                    array(
                        'label' => 'Taxonomy',
                        'uri' => '#',
                        'icon' => 'book',
                        'pages' => array(
                            array(
                                'label' => 'Manage taxonomies',
                                'route' => 'taxonomy/term',
                                'params' => array(),
                                'pages' => array(
                                    array(
                                        'route' => 'taxonomy/term/action',
                                        'visible' => false
                                    )
                                )
                            )
                        )
                    ),
                    array(
                        'label' => 'Users',
                        'icon' => 'user',
                        'uri' => '#',
                        'pages' => array(
                            array(
                                'label' => 'Manage users',
                                'route' => 'users'
                            ),
                            array(
                                'label' => 'Manage roles',
                                'route' => 'users/roles'
                            )
                        )
                    ),
                    array(
                        'label' => 'Recycle bin',
                        'icon' => 'trash',
                        'route' => 'uuid/recycle-bin',
                    ),
                    array(
                        'label' => 'Flags',
                        'icon' => 'flag',
                        'route' => 'flag/manage',
                    ),
                    array(
                        'label' => 'Licenses',
                        'icon' => 'tags',
                        'route' => 'license/manage',
                    )
                ),
            ),
            'blog' => array(
                'uri' => '#',
                'pages' => array(
                    array(
                        'label' => 'Blogs',
                        'route' => 'blog',
                        'icon' => 'home'
                    ),
                    array(
                        'label' => 'Develop',
                        'route' => 'blog/view',
                        'params' => array(
                            'id' => 63
                        )
                    ),
                    array(
                        'route' => 'blog/post/create',
                        'visible' => false
                    ),
                    array(
                        'route' => 'blog/post/view',
                        'visible' => false
                    )
                )
            ),
            'community' => array(
                'label' => 'Community',
                'route' => 'discussion',
                'params' => array(),
                'pages' => array(
                    array(
                        'label' => 'Diskussionen',
                        'route' => 'discussion/discussions',
                        'icon' => 'comment'
                    )
                )
            )
        ),
    ),
);