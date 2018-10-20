<?php
return [
    'controllers' => [
        'invokables' => [
            'Museums\Controller\Museums'      => 'Museums\Controller\MuseumsController',
            'MuseumsAdmin\Controller\Museums' => 'MuseumsAdmin\Controller\MuseumsController',
            'MuseumsAdmin\Controller\Points'  => 'MuseumsAdmin\Controller\PointsController',
        ],
    ],
    'router' => [
        'routes' => [
            'museums' => [
                'type' => 'literal',
                'priority' => 500,
                'options' => [
                    'route' => '/museums',
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
                    'point' => [
                        'type'    => 'segment',
                        'priority' => 600,
                        'options' => [
                            'route'    => '/point/:url/',
                            'constraints' => ['url' => '.*'],
                            'defaults' => [
                                'module'     => 'Museums',
                                'section'    => 'Museums',
                                'controller' => 'Museums\Controller\Museums',
                                'action'     => 'point',
                            ],
                        ],
                    ],
                    'pointsMap' => [
                        'type'    => 'segment',
                        'priority' => 600,
                        'options' => [
                            'route'    => '/get-map-points/',
                            'constraints' => ['url' => '.*'],
                            'defaults' => [
                                'module'     => 'Museums',
                                'section'    => 'Museums',
                                'controller' => 'Museums\Controller\Museums',
                                'action'     => 'getMapPoints',
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
                    'points' => [
                        'type'    => 'segment',
                        'priority' => 500,
                        'options' => [
                            'route'    => '/points[/:action][/:id]/',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'     => '[0-9]+',
                            ],
                            'defaults' => [
                                'module'     => 'Museums',
                                'section'    => 'Points',
                                'controller' => 'MuseumsAdmin\Controller\Points',
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