<?php
return [
    'controllers' => [
        'invokables' => [
            'Transports\Controller\Transports'      => 'Transports\Controller\TransportsController',
            'TransportsAdmin\Controller\Transports' => 'TransportsAdmin\Controller\TransportsController',
        ],
    ],
    'router' => [
        'routes' => [
            'transports' => [
                'type' => 'literal',
                'priority' => 500,
                'options' => [
                    'route' => '/transport',
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'list' => [
                        'type'    => 'literal',
                        'priority' => 500,
                        'options' => [
                            'route'    => '/',
                            'defaults' => [
                                'module'     => 'Transports',
                                'section'    => 'Transports',
                                'controller' => 'Transports\Controller\Transports',
                                'action'     => 'index',
                            ],
                        ],
                    ],
                    'item' => [
                        'type'    => 'segment',
                        'priority' => 500,
                        'options' => [
                            'route'    => '/:url/',
                            'constraints' => ['url' => '.*'],
                            'defaults' => [
                                'module'     => 'Transports',
                                'section'    => 'Transports',
                                'controller' => 'Transports\Controller\Transports',
                                'action'     => 'transport',
                            ],
                        ],
                    ],
                ],
            ],
            'adminTransports' => [
                'type'    => 'segment',
                'priority' => 600,
                'options' => [
                    'route'    => '/admin/transports/transports[/:action][/:id]/',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'module'     => 'Transports',
                        'section'    => 'Transports',
                        'controller' => 'TransportsAdmin\Controller\Transports',
                        'action'     => 'index',
                        'side'       => 'admin'
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'transports' => __DIR__ . '/../view',
            'admin' => __DIR__ . '/../view',
        ],
    ],
];