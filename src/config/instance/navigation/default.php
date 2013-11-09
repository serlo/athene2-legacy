<?php
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