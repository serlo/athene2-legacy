<?php
return array(
    'subject' => array(
        'instances' => array(
            array(
                'name' => 'physik',
                'language' => 'de',
                'plugins' => array(
                    array(
                        'name' => 'topic',
                        'plugin' => 'taxonomy',
                        'options' => array(
                            'taxonomy' => 'abstract-topic',
                            'taxonomy_parent' => 'subject',
                            'route' => 'subject/plugin/taxonomy/topic',
                            'templates' => array(
                                'index' => 'subject/plugin/taxonomy/templates/topic/index'
                            ),
                            'entity_types' => array(
                                'text-exercise' => array(
                                    'labels' => array(
                                        'singular' => 'Aufgabe',
                                        'plural' => 'Aufgaben'
                                    ),
                                    'template' => 'subject/plugin/taxonomy/entity/text-exercise'
                                ),
                                'article' => array(
                                    'labels' => array(
                                        'singular' => 'Artikel',
                                        'plural' => 'Artikel'
                                    ),
                                    'template' => 'subject/plugin/taxonomy/entity/article'
                                ),
                                'exercise-group' => array(
                                    'labels' => array(
                                        'singular' => 'Gruppenaufgabe',
                                        'plural' => 'Gruppenaufgaben'
                                    ),
                                    'template' => 'subject/plugin/taxonomy/entity/exercise-group'
                                )
                            )
                        )
                    ),
                    array(
                        'name' => 'entity',
                        'plugin' => 'entity'
                    )
                )
            )
        )
    )
);