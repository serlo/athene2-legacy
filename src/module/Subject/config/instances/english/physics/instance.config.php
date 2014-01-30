<?php
return array(
    'subject' => array(
        'instances' => array(
            array(
                'name' => 'physics',
                'instance' => 'english',
                'plugins' => array(
                    array(
                        'name' => 'topic',
                        'plugin' => 'taxonomy',
                        'options' => array(
                            'taxonomy' => 'abstract-topic',
                            'taxonomy_parent' => 'subject',
                            'route' => 'subject/plugin/taxonomy/topic',
                            'templates' => array(
                                'index' => 'subject/plugin/taxonomy/custom/topic/index'
                            ),
                            'entity_types' => array(
                                'text-exercise' => array(
                                    'labels' => array(
                                        'singular' => 'Exercise',
                                        'plural' => 'Exercises'
                                    ),
                                    'template' => 'subject/plugin/taxonomy/entity/text-exercise'
                                ),
                                'article' => array(
                                    'labels' => array(
                                        'singular' => 'Article',
                                        'plural' => 'Articles'
                                    ),
                                    'template' => 'subject/plugin/taxonomy/entity/article'
                                ),
                                'exercise-group' => array(
                                    'labels' => array(
                                        'singular' => 'Exercise group',
                                        'plural' => 'Exercise groups'
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
                                        'singular' => 'Module',
                                        'plural' => 'Modules'
                                    ),
                                    'template' => 'subject/plugin/taxonomy/entity/module'
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