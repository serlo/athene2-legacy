<?php
return array(
    'navigation' => array(
        'top-center' => array(
            array(
                'label' => 'Fächer',
                'uri' => '#',
                'pages' => array(
                    array(
                        'label' => 'Mathe',
                        'route' => 'subject',
                        'params' => array(
                            'subject' => 'mathe'
                        )
                    )
                )
            ),
            array(
                'label' => 'Labor',
                'uri' => '#',
                'pages' => array(
                    array(
                        'label' => 'Physik',
                        'route' => 'subject',
                        'params' => array(
                            'subject' => 'physik'
                        )
                    ),
                    array(
                        'label' => 'Permakultur',
                        'route' => 'subject',
                        'params' => array(
                            'subject' => 'permakultur'
                        )
                    )
                )
            ),
            array(
                'label' => 'Community',
                'uri' => '#',
                'pages' => array(
                    array(
                        'label' => 'Diskussionen',
                        'route' => 'discussion/discussions',
                        'params' => array()
                    )
                )
            )
        )
    )
);