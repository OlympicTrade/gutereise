<?php
return [
    'controllers' => [
        'invokables' => [
            'Comments\Controller\Comments'      => 'Comments\Controller\CommentsController',
            'CommentsAdmin\Controller\Comments' => 'CommentsAdmin\Controller\CommentsController',
        ],
    ],
    'router' => [
        'routes' => [
            'mobile' => [
                'type' => 'Hostname',
                'priority' => 600,
                'options' => [
                    'route' => 'm.:domain',
                    'constraints' => ['domain' => '.*',],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'addComment' => [
                        'type'    => 'segment',
                        'priority' => 500,
                        'options' => [
                            'route'    => '/comments/add/',
                            'defaults' => [
                                'module'     => 'Comments',
                                'section'    => 'Comments',
                                'controller' => 'Comments\Controller\Comments',
                                'action'     => 'addComment',
                            ],
                        ],
                    ],
                ],
            ],

            'addComment' => [
                'type'    => 'segment',
                'priority' => 500,
                'options' => [
                    'route'    => '/comments/add/',
                    'defaults' => [
                        'module'     => 'Comments',
                        'section'    => 'Comments',
                        'controller' => 'Comments\Controller\Comments',
                        'action'     => 'addComment',
                    ],
                ],
            ],
            'adminComments' => [
                'type'    => 'segment',
                'priority' => 600,
                'options' => [
                    'route'    => '/admin/comments/comments[/:action][/:id]/',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'module'     => 'Comments',
                        'section'    => 'Comments',
                        'controller' => 'CommentsAdmin\Controller\Comments',
                        'action'     => 'index',
                        'side'       => 'admin'
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'comments' => __DIR__ . '/../view',
            'admin' => __DIR__ . '/../view',
        ],
    ],
];