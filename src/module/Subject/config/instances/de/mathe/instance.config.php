<?php
return array(
    'subject' => array(
        'instances' => array(
            array(
                'name' => 'mathe',
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
                                ),
                                'video' => array(
                                    'labels' => array(
                                        'singular' => 'Video',
                                        'plural' => 'Videos'
                                    ),
                                    'template' => 'subject/plugin/taxonomy/entity/video'
                                ),
                                'module' => array(
                                    'labels' => array(
                                        'singular' => 'Modul',
                                        'plural' => 'Module'
                                    ),
                                    'template' => 'subject/plugin/taxonomy/entity/module'
                                )
                            )
                        )
                    ),
                    array(
                        'name' => 'curriculum',
                        'plugin' => 'taxonomy',
                        'options' => array(
                            'taxonomy' => 'school-type',
                            'taxonomy_parent' => 'subject',
                            'route' => 'subject/plugin/taxonomy/curriculum',
                            'templates' => array(
                                'index' => 'subject/plugin/taxonomy/templates/curriculum/index'
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