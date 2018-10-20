<?php
return [
    'controllers' => [
        'invokables' => [
            'Excursions\Controller\Excursions'      => 'Excursions\Controller\ExcursionsController',
            'ExcursionsAdmin\Controller\Excursions' => 'ExcursionsAdmin\Controller\ExcursionsController',
            'ExcursionsAdmin\Controller\Types'      => 'ExcursionsAdmin\Controller\TypesController',
        ],
    ],
    'router' => [
        'routes' => [
            'excursions' => [
                'type' => 'literal',
                'priority' => 500,
                'options' => [
                    'route' => '/excursions',
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
                                'module'     => 'Excursions',
                                'section'    => 'Excursions',
                                'controller' => 'Excursions\Controller\Excursions',
                                'action'     => 'index',
                            ],
                        ],
                    ],
                    'price' => [
                        'type'    => 'literal',
                        'priority' => 600,
                        'options' => [
                            'route'    => '/get-price/',
                            'defaults' => [
                                'module'     => 'Excursions',
                                'section'    => 'Excursions',
                                'controller' => 'Excursions\Controller\Excursions',
                                'action'     => 'getPrice',
                            ],
                        ],
                    ],
                    'order' => [
                        'type'    => 'literal',
                        'priority' => 600,
                        'options' => [
                            'route'    => '/order/',
                            'defaults' => [
                                'module'     => 'Excursions',
                                'section'    => 'Excursions',
                                'controller' => 'Excursions\Controller\Excursions',
                                'action'     => 'order',
                            ],
                        ],
                    ],
                ],
            ],
            'adminExcursions' => [
                'type'    => 'segment',
                'priority' => 600,
                'options' => [
                    'route'    => '/admin/excursions',
                    'defaults' => [
                        'module'     => 'Excursions',
                        'section'    => 'Excursions',
                        'controller' => 'ExcursionsAdmin\Controller\Excursions',
                        'action'     => 'index',
                        'side'       => 'admin'
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'excursions' => [
                        'type'    => 'segment',
                        'priority' => 500,
                        'options' => [
                            'route'    => '/excursions[/:action][/:id]/',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'     => '[0-9]+',
                            ],
                            'defaults' => [
                                'module'     => 'Excursions',
                                'section'    => 'Excursions',
                                'controller' => 'ExcursionsAdmin\Controller\Excursions',
                                'action'     => 'index',
                                'side'       => 'admin'
                            ],
                        ],
                    ],
                    'types' => [
                        'type'    => 'segment',
                        'priority' => 500,
                        'options' => [
                            'route'    => '/types[/:action][/:id]/',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'     => '[0-9]+',
                            ],
                            'defaults' => [
                                'module'     => 'Excursions',
                                'section'    => 'Types',
                                'controller' => 'ExcursionsAdmin\Controller\Types',
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
            'excursions' => __DIR__ . '/../view',
            'admin' => __DIR__ . '/../view',
        ],
    ],
];