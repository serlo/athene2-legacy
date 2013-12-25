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
namespace Entity;

return array(
    'entity' => array(
        'types' => array(
            'text-exercise' => array(
                'plugins' => array(
                    'repository' => array(
                        'plugin' => 'repository',
                        'options' => array(
                            'revision_form' => __NAMESPACE__ . '\Form\TextExerciseForm',
                            'fields' => array(
                                'content'
                            )
                        )
                    ),
                    'learningResource' => array(
                        'plugin' => 'learningResource'
                    ),
                    'metadata' => array(
                        'plugin' => 'metadata'
                    ),
                    'license' => array(
                        'plugin' => 'license'
                    ),
                    'taxonomy' => array(
                        'plugin' => 'taxonomy'
                    ),
                    'solution' => array(
                        'plugin' => 'link',
                        'options' => array(
                            'types' => array(
                                'text-solution' => array(
                                    'inversed_by' => 'exercise',
                                    'owning' => true
                                )
                            ),
                            'type' => 'link',
                            'association' => 'one-to-one'
                        )
                    ),
                    'provider' => array(
                        'plugin' => 'provider',
                        'options' => array()
                    ),
                    'page' => array(
                        'plugin' => 'page',
                        'options' => array(
                            'template' => 'entity/plugin/page/text-exercise'
                        )
                    )
                )
            ),
            'exercise-group' => array(
                'plugins' => array(
                    'repository' => array(
                        'plugin' => 'repository',
                        'options' => array(
                            'revision_form' => __NAMESPACE__ . '\Form\TextExerciseGroupForm',
                            'fields' => array(
                                'content'
                            )
                        )
                    ),
                    'learningResource' => array(
                        'plugin' => 'learningResource'
                    ),
                    'license' => array(
                        'plugin' => 'license'
                    ),
                    'metadata' => array(
                        'plugin' => 'metadata'
                    ),
                    'taxonomy' => array(
                        'plugin' => 'taxonomy'
                    ),
                    'exercises' => array(
                        'plugin' => 'link',
                        'options' => array(
                            'types' => array(
                                'grouped-text-exercise' => array(
                                    'inversed_by' => 'group',
                                )
                            ),
                            'type' => 'link',
                            'association' => 'one-to-many'
                        )
                    ),
                    'provider' => array(
                        'plugin' => 'provider',
                        'options' => array()
                    ),
                    'page' => array(
                        'plugin' => 'page',
                        'options' => array(
                            'template' => 'entity/plugin/page/exercise-group'
                        )
                    )
                )
            ),
            'grouped-text-exercise' => array(
                'plugins' => array(
                    'repository' => array(
                        'plugin' => 'repository',
                        'options' => array(
                            'revision_form' => __NAMESPACE__ . '\Form\GroupedTextExerciseForm',
                            'fields' => array(
                                'content'
                            )
                        )
                    ),
                    'license' => array(
                        'plugin' => 'license'
                    ),
                    'learningResource' => array(
                        'plugin' => 'learningResource'
                    ),
                    'metadata' => array(
                        'plugin' => 'metadata'
                    ),
                    'group' => array(
                        'plugin' => 'link',
                        'options' => array(
                            'types' => array(
                                'exercise-group' => array(
                                    'inversed_by' => 'exercises'
                                )
                            ),
                            'type' => 'link',
                            'association' => 'many-to-one'
                        )
                    ),
                    'page' => array(
                        'plugin' => 'page',
                        'options' => array(
                            'template' => 'entity/plugin/page/grouped-text-exercise'
                        )
                    ),
                    'solution' => array(
                        'plugin' => 'link',
                        'options' => array(
                            'types' => array(
                                'text-solution' => array(
                                    'inversed_by' => 'exercise',
                                    'owning' => true
                                )
                            ),
                            'type' => 'link',
                            'association' => 'one-to-one'
                        )
                    )
                )
            ),
            'text-solution' => array(
                'plugins' => array(
                    'repository' => array(
                        'plugin' => 'repository',
                        'options' => array(
                            'revision_form' => __NAMESPACE__ . '\Form\TextSolutionForm',
                            'fields' => array(
                                'hint',
                                'content'
                            )
                        )
                    ),
                    'license' => array(
                        'plugin' => 'license'
                    ),
                    'learningResource' => array(
                        'plugin' => 'learningResource'
                    ),
                    'page' => array(
                        'plugin' => 'page',
                        'options' => array(
                            'template' => 'entity/plugin/page/text-solution'
                        )
                    ),
                    'metadata' => array(
                        'plugin' => 'metadata'
                    ),
                    'exercise' => array(
                        'plugin' => 'link',
                        'options' => array(
                            'types' => array(
                                'text-exercise' => array(
                                    'inversed_by' => 'solution',
                                    'owning' => false
                                ),
                                'grouped-text-exercise' => array(
                                    'inversed_by' => 'solution',
                                    'owning' => false
                                )
                            ),
                            'type' => 'link',
                            'association' => 'one-to-one'
                        )
                    )
                )
            ),
            'video' => array(
                'plugins' => array(
                    'repository' => array(
                        'plugin' => 'repository',
                        'options' => array(
                            'revision_form' => __NAMESPACE__ . '\Form\VideoForm',
                            'fields' => array(
                                'title',
                                'content',
                                'reasoning'
                            )
                        )
                    ),
                    'license' => array(
                        'plugin' => 'license'
                    ),
                    'learningResource' => array(
                        'plugin' => 'learningResource'
                    ),
                    'metadata' => array(
                        'plugin' => 'metadata'
                    ),
                    'taxonomy' => array(
                        'plugin' => 'taxonomy'
                    ),
                    'page' => array(
                        'plugin' => 'page',
                        'options' => array(
                            'template' => 'entity/plugin/page/video'
                        )
                    ),
                    'pathauto' => array(
                        'plugin' => 'pathauto',
                        'options' => array(
                            'tokenize' => '{subject}/{type}/{title}'
                        )
                    ),
                    'provider' => array(
                        'plugin' => 'provider'
                    )
                )
            ),
            'article' => array(
                'plugins' => array(
                    'repository' => array(
                        'plugin' => 'repository',
                        'options' => array(
                            'revision_form' => __NAMESPACE__ . '\Form\ArticleForm',
                            'fields' => array(
                                'title',
                                'reasoning',
                                'content'
                            )
                        )
                    ),
                    'license' => array(
                        'plugin' => 'license'
                    ),
                    'metadata' => array(
                        'plugin' => 'metadata'
                    ),
                    'learningResource' => array(
                        'plugin' => 'learningResource'
                    ),
                    'aggregator' => array(
                        'plugin' => 'aggregator',
                        'options' => array(
                            'aggregators' => array(
                                'topic'
                            )
                        )
                    ),
                    'taxonomy' => array(
                        'plugin' => 'taxonomy'
                    ),
                    'page' => array(
                        'plugin' => 'page',
                        'options' => array(
                            'template' => 'entity/plugin/page/article'
                        )
                    ),
                    'pathauto' => array(
                        'plugin' => 'pathauto',
                        'options' => array(
                            'tokenize' => '{subject}/{type}/{title}'
                        )
                    ),
                    'provider' => array(
                        'plugin' => 'provider'
                    )
                )
            ),
            'module' => array(
                'plugins' => array(
                    'repository' => array(
                        'plugin' => 'repository',
                        'options' => array(
                            'revision_form' => __NAMESPACE__ . '\Form\ModuleForm',
                            'fields' => array(
                                'title'
                            )
                        )
                    ),
                    'license' => array(
                        'plugin' => 'license'
                    ),
                    'metadata' => array(
                        'plugin' => 'metadata'
                    ),
                    'learningResource' => array(
                        'plugin' => 'learningResource'
                    ),
                    'taxonomy' => array(
                        'plugin' => 'taxonomy'
                    ),
                    'pathauto' => array(
                        'plugin' => 'pathauto',
                        'options' => array(
                            'tokenize' => '{subject}/{type}/{title}'
                        )
                    ),
                    'pages' => array(
                        'plugin' => 'link',
                        'options' => array(
                            'types' => array(
                                'module-page' => array(
                                    'inversed_by' => 'module'
                                )
                            ),
                            'type' => 'link',
                            'association' => 'one-to-many'
                        )
                    ),
                    'page' => array(
                        'plugin' => 'page',
                        'options' => array(
                            'template' => 'entity/plugin/page/module'
                        )
                    ),
                    'provider' => array(
                        'plugin' => 'provider'
                    )
                )
            ),
            'module-page' => array(
                'plugins' => array(
                    'repository' => array(
                        'plugin' => 'repository',
                        'options' => array(
                            'revision_form' => __NAMESPACE__ . '\Form\ModulePageForm',
                            'fields' => array(
                                'title',
                                'reasoning',
                                'content'
                            )
                        )
                    ),
                    'metadata' => array(
                        'plugin' => 'metadata'
                    ),
                    'license' => array(
                        'plugin' => 'license'
                    ),
                    'learningResource' => array(
                        'plugin' => 'learningResource'
                    ),
                    'module' => array(
                        'plugin' => 'link',
                        'options' => array(
                            'types' => array(
                                'module' => array(
                                    'inversed_by' => 'pages'
                                )
                            ),
                            'type' => 'link',
                            'association' => 'many-to-one'
                        )
                    ),
                    'provider' => array(
                        'plugin' => 'provider'
                    ),
                    'page' => array(
                        'plugin' => 'page',
                        'options' => array(
                            'template' => 'entity/plugin/page/module-page'
                        )
                    )
                )
            )
        )
    )
);