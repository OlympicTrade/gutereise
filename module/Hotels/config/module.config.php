<?php
return [
    'controllers' => [
        'invokables' => [
            'Hotels\Controller\Hotels'          => 'Hotels\Controller\HotelsController',
            'Hotels\Controller\MobileHotels'    => 'Hotels\Controller\MobileHotelsController',
            'HotelsAdmin\Controller\Hotels'     => 'HotelsAdmin\Controller\HotelsController',
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
                    'hotels' => [
                        'type' => 'literal',
                        'priority' => 500,
                        'options' => [
                            'route' => '/hotels',
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'list' => [
                                'type'    => 'segment',
                                'priority' => 500,
                                'options' => [
                                    'route'    => '[/:url]/',
                                    'constraints' => ['url' => '.*'],
                                    'defaults' => [
                                        'module'     => 'Hotels',
                                        'section'    => 'Hotels',
                                        'controller' => 'Hotels\Controller\MobileHotels',
                                        'action'     => 'index',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'hotels' => [
                'type' => 'literal',
                'priority' => 500,
                'options' => [
                    'route' => '/hotels',
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'list' => [
                        'type'    => 'segment',
                        'priority' => 500,
                        'options' => [
                            'route'    => '[/:url]/',
                            'constraints' => ['url' => '.*'],
                            'defaults' => [
                                'module'     => 'Hotels',
                                'section'    => 'Hotels',
                                'controller' => 'Hotels\Controller\Hotels',
                                'action'     => 'index',
                            ],
                        ],
                    ],
                ],
            ],
            'adminHotels' => [
                'type'    => 'segment',
                'priority' => 600,
                'options' => [
                    'route'    => '/admin/hotels',
                    'defaults' => [
                        'module'     => 'Hotels',
                        'section'    => 'Hotels',
                        'controller' => 'HotelsAdmin\Controller\Hotels',
                        'action'     => 'index',
                        'side'       => 'admin'
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'hotels' => [
                        'type'    => 'segment',
                        'priority' => 500,
                        'options' => [
                            'route'    => '/hotels[/:action][/:id]/',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'     => '[0-9]+',
                            ],
                            'defaults' => [
                                'module'     => 'Hotels',
                                'section'    => 'Hotels',
                                'controller' => 'HotelsAdmin\Controller\Hotels',
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
            'hotels' => __DIR__ . '/../view',
            'admin' => __DIR__ . '/../view',
        ],
    ],
];