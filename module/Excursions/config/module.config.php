<?php
return [
    'controllers' => [
        'invokables' => [
            'Excursions\Controller\Excursions'          => 'Excursions\Controller\ExcursionsController',
            'Excursions\Controller\MobileExcursions'    => 'Excursions\Controller\MobileExcursionsController',
            'Excursions\Controller\Tours'               => 'Excursions\Controller\ToursController',
            'Excursions\Controller\MobileTours'         => 'Excursions\Controller\MobileToursController',
            'ExcursionsAdmin\Controller\Excursions'     => 'ExcursionsAdmin\Controller\ExcursionsController',
            'ExcursionsAdmin\Controller\Tags'           => 'ExcursionsAdmin\Controller\TagsController',
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
                                        'controller' => 'Excursions\Controller\MobileExcursions',
                                        'action'     => 'index',
                                    ],
                                ],
                            ],
                            'price' => [
                                'type'    => 'segment',
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
                ],
            ],
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
                    'tags' => [
                        'type'    => 'segment',
                        'priority' => 600,
                        'options' => [
                            'route'    => '/category/[:tag]/',
                            'constraints' => ['url' => '.*'],
                            'defaults' => [
                                'module'     => 'Excursions',
                                'section'    => 'Excursions',
                                'controller' => 'Excursions\Controller\Excursions',
                                'action'     => 'tags',
                            ],
                        ],
                    ],
                    'price' => [
                        'type'    => 'segment',
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
            'tours' => [
                'type' => 'literal',
                'priority' => 500,
                'options' => [
                    'route' => '/tours',
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
                                'section'    => 'Tours',
                                'controller' => 'Excursions\Controller\Tours',
                                'action'     => 'index',
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
                                'module'     => 'Excursions',
                                'section'    => 'Tours',
                                'controller' => 'Excursions\Controller\Tours',
                                'action'     => 'tags',
                            ],
                        ],
                    ],
                    'price' => [
                        'type'    => 'segment',
                        'priority' => 600,
                        'options' => [
                            'route'    => '/get-price/',
                            'defaults' => [
                                'module'     => 'Excursions',
                                'section'    => 'Tours',
                                'controller' => 'Excursions\Controller\Tours',
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
                                'section'    => 'Tours',
                                'controller' => 'Excursions\Controller\Tours',
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
                                'module'     => 'Excursions',
                                'section'    => 'Tags',
                                'controller' => 'ExcursionsAdmin\Controller\Tags',
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