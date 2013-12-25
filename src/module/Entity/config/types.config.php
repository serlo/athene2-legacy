<?php
/**
 * 
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author	    Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license	    LGPL-3.0
 * @license	    http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link		https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright   Copyright (c) 2013 Gesellschaft f√ºr freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Entity;

return [
    'entity' => [
        'types' => [
            'text-exercise' => [
                'components' => [
                    'repository' => [
                        'form' => __NAMESPACE__ . '\Form\TextExerciseForm',
                        'fields' => [
                            'content'
                        ]
                    ],
                    'license' => [],
                    'taxonomy' => [],
                    'link' => [
                        'children' => [
                            'text-solution' => [
                                'multiple' => true
                            ]
                        ]
                    ]
                ]
            ],
            'exercise-group' => [
                'components' => [
                    'repository' => [
                        'form' => __NAMESPACE__ . '\Form\TextExerciseGroupForm',
                        'fields' => [
                            'content'
                        ]
                    ],
                    'license' => [],
                    'taxonomy' => [],
                    'link' => [
                        'children' => [
                            'grouped-text-exercise' => [
                                'multiple' => true
                            ]
                        ]
                    ]
                ]
            ],
            'grouped-text-exercise' => [
                'components' => [
                    'repository' => [
                        'form' => __NAMESPACE__ . '\Form\GroupedTextExerciseForm',
                        'fields' => [
                            'content'
                        ]
                    ],
                    'license' => [],
                    'link' => [
                        'children' => [
                            'text-solution' => [
                                'multiple' => true
                            ],
                            'text-solution' => [
                                'multiple' => false
                            ]
                        ],
                        'parents' => [
                            'exercise-group' => [
                                'multiple' => false
                            ]
                        ]
                    ]
                ]
            ],
            'text-solution' => [
                'components' => [
                    'repository' => [
                        'form' => __NAMESPACE__ . '\Form\TextSolutionForm',
                        'fields' => [
                            'hint',
                            'content'
                        ]
                    ],
                    'license' => [],
                    'link' => [
                        'parents' => [
                            'text-exercise' => [
                                'multiple' => false
                            ],
                            'grouped-text-exercise' => [
                                'multiple' => false
                            ]
                        ]
                    ]
                ]
            ],
            'video' => [
                'components' => [
                    'repository' => [
                        'form' => __NAMESPACE__ . '\Form\VideoForm',
                        'fields' => [
                            'title',
                            'content',
                            'reasoning'
                        ]
                    ],
                    'license' => [],
                    'taxonomy' => []
                ]
            ],
            'article' => [
                'components' => [
                    'repository' => [
                        'form' => __NAMESPACE__ . '\Form\ArticleForm',
                        'fields' => [
                            'title',
                            'reasoning',
                            'content'
                        ]
                    ],
                    'license' => [],
                    'taxonomy' => []
                ]
            ],
            'module' => [
                'components' => [
                    'repository' => [
                        'revision_form' => __NAMESPACE__ . '\Form\ModuleForm',
                        'fields' => [
                            'title'
                        ]
                    ],
                    'license' => [],
                    'taxonomy' => [],
                    'link' => [
                        'children' => [
                            'module-page' => [
                                'multiple' => true
                            ]
                        ]
                    ]
                ]
            ],
            'module-page' => [
                'components' => [
                    'repository' => [
                        'form' => __NAMESPACE__ . '\Form\ModulePageForm',
                        'fields' => [
                            'title',
                            'reasoning',
                            'content'
                        ]
                    ],
                    'license' => [],
                    'link' => [
                        'parents' => [
                            'module' => [
                                'multiple' => false
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]
];