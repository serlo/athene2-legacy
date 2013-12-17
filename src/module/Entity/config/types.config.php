<?php
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
                        'link' => [
                            'text-solution' => [
                                'multiple' => false
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]
];