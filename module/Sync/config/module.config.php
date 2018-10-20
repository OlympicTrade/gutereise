<?php
return [
    'controllers' => [
        'invokables' => [
            'Sync\Controller\Sync'      => 'Sync\Controller\SyncController',
            'SyncAdmin\Controller\Sync' => 'SyncAdmin\Controller\SyncController',
        ],
    ],
    'router' => [
        'routes' => [
            'sync' => [
                'type'    => 'segment',
                'priority' => 500,
                'options' => [
                    'route'    => '/sync[/:action][/:id]/',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[a-zA-Z0-9_-]+',
                    ],
                    'defaults' => [
                        'module'     => 'Sync',
                        'section'    => 'Sync',
                        'controller' => 'Sync\Controller\Sync',
                        'action'     => 'index',
                    ],
                ],
            ],
            'adminSync' => [
                'type'    => 'segment',
                'priority' => 600,
                'options' => [
                    'route'    => '/admin/sync/sync[/:action][/:id]/',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'module'     => 'Sync',
                        'section'    => 'Sync',
                        'controller' => 'SyncAdmin\Controller\Sync',
                        'action'     => 'index',
                        'side'       => 'admin'
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'sync' => __DIR__ . '/../view',
            'admin' => __DIR__ . '/../view',
        ],
    ],
];