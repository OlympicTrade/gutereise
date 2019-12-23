<?php
return [
    'controllers' => [
        'invokables' => [
            'Museums\Controller\Museums'      => 'Museums\Controller\MuseumsController',
            'Museums\Controller\MuseumsMobile'      => 'Museums\Controller\MuseumsMobileController',
            'MuseumsAdmin\Controller\Museums' => 'MuseumsAdmin\Controller\MuseumsController',
            'MuseumsAdmin\Controller\Tags'    => 'MuseumsAdmin\Controller\TagsController',
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
                    'museums' => [
                        'type' => 'literal',
                        'priority' => 500,
                        'options' => [
                            'route' => '/attractions',
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'list' => [
                                'type'    => 'literal',
                                'priority' => 500,
                                'options' => [
                                    'route'    => '/',
                                    'defaults' => [
                                        'module'     => 'Museums',
                                        'section'    => 'Museums',
                                        'controller' => 'Museums\Controller\MuseumsMobile',
                                        'action'     => 'index',
                                    ],
                                ],
                            ],
                            'museum' => [
                                'type'    => 'segment',
                                'priority' => 500,
                                'options' => [
                                    'route'    => '/:url/',
                                    'constraints' => ['url' => '.*'],
                                    'defaults' => [
                                        'module'     => 'Museums',
                                        'section'    => 'Museums',
                                        'controller' => 'Museums\Controller\MuseumsMobile',
                                        'action'     => 'museum',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'museums' => [
                'type' => 'literal',
                'priority' => 500,
                'options' => [
                    'route' => '/attractions',
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'list' => [
                        'type'    => 'literal',
                        'priority' => 500,
                        'options' => [
                            'route'    => '/',
                            'defaults' => [
                                'module'     => 'Museums',
                                'section'    => 'Museums',
                                'controller' => 'Museums\Controller\Museums',
                                'action'     => 'index',
                            ],
                        ],
                    ],
                    'museum' => [
                        'type'    => 'segment',
                        'priority' => 500,
                        'options' => [
                            'route'    => '/:url/',
                            'constraints' => ['url' => '.*'],
                            'defaults' => [
                                'module'     => 'Museums',
                                'section'    => 'Museums',
                                'controller' => 'Museums\Controller\Museums',
                                'action'     => 'museum',
                            ],
                        ],
                    ],
                    'tags' => [
                        'type'    => 'segment',
                        'priority' => 600,
                        'options' => [
                            'route'    => '/category/[:tag]/',
                            'constraints' => ['url' => '.*'],
                            'defaults' => [
                                'module'     => 'Museums',
                                'section'    => 'Museums',
                                'controller' => 'Museums\Controller\Museums',
                                'action'     => 'tags',
                            ],
                        ],
                    ],
                ],
            ],
            'adminMuseums' => [
                'type'    => 'segment',
                'priority' => 600,
                'options' => [
                    'route'    => '/admin/museums',
                    'defaults' => [
                        'module'     => 'Museums',
                        'section'    => 'Museums',
                        'controller' => 'MuseumsAdmin\Controller\Museums',
                        'action'     => 'index',
                        'side'       => 'admin'
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'museums' => [
                        'type'    => 'segment',
                        'priority' => 500,
                        'options' => [
                            'route'    => '/museums[/:action][/:id]/',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'     => '[0-9]+',
                            ],
                            'defaults' => [
                                'module'     => 'Museums',
                                'section'    => 'Museums',
                                'controller' => 'MuseumsAdmin\Controller\Museums',
                                'action'     => 'index',
                                'side'       => 'admin'
                            ],
                        ],
                    ],
                    'tags' => [
                        'type'    => 'segment',
                        'priority' => 500,
                        'options' => [
                            'route'    => '/tags[/:action][/:id]/',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'     => '[0-9]+',
                            ],
                            'defaults' => [
                                'module'     => 'Museums',
                                'section'    => 'Tags',
                                'controller' => 'MuseumsAdmin\Controller\Tags',
                                'action'     => 'index',
                                'side'       => 'admin'
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'museums' => __DIR__ . '/../view',
            'admin' => __DIR__ . '/../view',
        ],
    ],
];