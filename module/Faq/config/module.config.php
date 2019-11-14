<?php
return [
    'controllers' => [
        'invokables' => [
            'Faq\Controller\Faq' => 'Faq\Controller\FaqController',
            'Admin\Controller\Faq' => 'FaqAdmin\Controller\FaqController',
        ],
    ],
    'router' => [
        'routes' => [
            'faq' => [
                'type'    => 'segment',
                'priority' => 500,
                'options' => [
                    'route'    => '/faq[/:action][/:id]/',
                    'constraints' => [
                        'locale' => '[a-z]{2}',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[a-zA-Z][a-zA-Z0-9_-]+',
                    ],
                    'defaults' => [
                        'module'     => 'Faq',
                        'section'    => 'Faq',
                        'controller' => 'Faq\Controller\Faq',
                        'action'     => 'index',
                    ],
                ],
            ],
            'adminFaq' => [
                'type'    => 'segment',
                'priority' => 600,
                'options' => [
                    'route'    => '/admin/faq/faq[/:action][/:id]/',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'module'     => 'Faq',
                        'section'    => 'Faq',
                        'controller' => 'Admin\Controller\Faq',
                        'action'     => 'index',
                        'side'       => 'admin'
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'faq' => __DIR__ . '/../view',
            'admin' => __DIR__ . '/../view',
        ],
    ],
];