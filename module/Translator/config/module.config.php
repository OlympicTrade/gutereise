<?php
return [
    'controllers' => [
        'invokables' => [
            'TranslatorAdmin\Controller\Translator' => 'TranslatorAdmin\Controller\TranslatorController',
        ],
    ],
    'router' => [
        'routes' => [
            'adminTranslator' => [
                'type'    => 'segment',
                'priority' => 600,
                'options' => [
                    'route'    => '/admin/translator/translator[/:action][/:id]/',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'module'     => 'Translator',
                        'section'    => 'Translator',
                        'controller' => 'TranslatorAdmin\Controller\Translator',
                        'action'     => 'index',
                        'side'       => 'admin'
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'translator' => __DIR__ . '/../view',
            'admin' => __DIR__ . '/../view',
        ],
    ],
];