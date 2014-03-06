<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author         Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license        LGPL-3.0
 * @license        http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link           https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright      Copyright (c) 2013 Gesellschaft f√ºr freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Entity;

return [
    'entity' => [
        'types' => [
            'text-exercise'         => [
                'components' => [
                    'repository' => [
                        'form'   => __NAMESPACE__ . '\Form\TextExerciseForm',
                        'fields' => [
                            'content',
                            'changes'
                        ]
                    ],
                    'link'       => [
                        'children' => [
                            'text-solution' => [
                                'multiple' => false
                            ]
                        ]
                    ],
                    'license'    => [],
                    'taxonomy'   => []
                ]
            ],
            'text-exercise-group'   => [
                'components' => [
                    'repository' => [
                        'form'   => __NAMESPACE__ . '\Form\TextExerciseGroupForm',
                        'fields' => [
                            'content',
                            'changes'
                        ]
                    ],
                    'link'       => [
                        'children' => [
                            'grouped-text-exercise' => [
                                'multiple' => true
                            ]
                        ]
                    ],
                    'license'    => [],
                    'taxonomy'   => []
                ]
            ],
            'grouped-text-exercise' => [
                'components' => [
                    'repository' => [
                        'form'   => __NAMESPACE__ . '\Form\GroupedTextExerciseForm',
                        'fields' => [
                            'content',
                            'changes'
                        ]
                    ],
                    'link'       => [
                        'children' => [
                            'text-solution' => [
                                'multiple' => false
                            ]
                        ],
                        'parents'  => [
                            'text-exercise-group' => [
                                'multiple' => false
                            ]
                        ]
                    ],
                    'license'    => []
                ]
            ],
            'text-solution'         => [
                'components' => [
                    'repository' => [
                        'form'   => __NAMESPACE__ . '\Form\TextSolutionForm',
                        'fields' => [
                            'hint',
                            'content',
                            'changes'
                        ]
                    ],
                    'link'       => [
                        'parents' => [
                            'text-exercise'         => [
                                'multiple' => false
                            ],
                            'grouped-text-exercise' => [
                                'multiple' => false
                            ]
                        ]
                    ],
                    'license'    => []
                ]
            ],
            'video'                 => [
                'components' => [
                    'repository'      => [
                        'form'   => __NAMESPACE__ . '\Form\VideoForm',
                        'fields' => [
                            'title',
                            'description',
                            'content',
                            'reasoning',
                            'changes'
                        ]
                    ],
                    'license'         => [],
                    'taxonomy'        => [],
                    'related_content' => []
                ],
            ],
            'article'               => [
                'components' => [
                    'repository'      => [
                        'form'   => __NAMESPACE__ . '\Form\ArticleForm',
                        'fields' => [
                            'title',
                            'content',
                            'reasoning',
                            'changes'
                        ]
                    ],
                    'license'         => [],
                    'taxonomy'        => [],
                    'related_content' => []
                ]
            ],
            'module'                => [
                'components' => [
                    'repository'      => [
                        'form'   => __NAMESPACE__ . '\Form\ModuleForm',
                        'fields' => [
                            'title',
                            'reasoning',
                            'changes'
                        ]
                    ],
                    'link'            => [
                        'children' => [
                            'module-page' => [
                                'multiple' => true
                            ]
                        ]
                    ],
                    'license'         => [],
                    'taxonomy'        => [],
                    'related_content' => []
                ]
            ],
            'module-page'           => [
                'components' => [
                    'repository' => [
                        'form'   => __NAMESPACE__ . '\Form\ModulePageForm',
                        'fields' => [
                            'title',
                            'content',
                            'changes'
                        ]
                    ],
                    'link'       => [
                        'parents' => [
                            'module' => [
                                'multiple' => false
                            ]
                        ]
                    ],
                    'license'    => []
                ]
            ]
        ]
    ]
];