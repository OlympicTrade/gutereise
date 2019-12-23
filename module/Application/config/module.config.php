<?php
namespace Application;

$develop = getenv('APPLICATION_ENV') == 'dev';

return [
    'controllers' => [
        'invokables' => [
            'Application\Controller\Mobile'         => 'Application\Controller\MobileController',
            'Application\Controller\Index'          => 'Application\Controller\IndexController',
            'Application\Controller\Error'          => 'Application\Controller\ErrorController',
            'ApplicationAdmin\Controller\Index'     => 'ApplicationAdmin\Controller\IndexController',
            'ApplicationAdmin\Controller\Service'   => 'ApplicationAdmin\Controller\ServiceController',
            'ApplicationAdmin\Controller\Page'      => 'ApplicationAdmin\Controller\PageController',
            'ApplicationAdmin\Controller\Settings'  => 'ApplicationAdmin\Controller\SettingsController',
            'ApplicationAdmin\Controller\Menu'      => 'ApplicationAdmin\Controller\MenuController',
            'ApplicationAdmin\Controller\MenuItems' => 'ApplicationAdmin\Controller\MenuItemsController',
            'ApplicationAdmin\Controller\Content'   => 'ApplicationAdmin\Controller\ContentController',
            'ApplicationAdmin\Controller\Countries' => 'ApplicationAdmin\Controller\CountriesController',
            'ApplicationAdmin\Controller\About'     => 'ApplicationAdmin\Controller\AboutController',
        ],
    ],
    'asset_manager' => [
        'resolver_configs' => [
            'paths' => [
                __DIR__ . '/../public',
            ],
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
                    'home' => [
                        'type' => 'literal',
                        'priority' => 500,
                        'options' => [
                            'route' => '/',
                            'defaults' => [
                                'controller' => 'Application\Controller\Mobile',
                                'action' => 'index',
                            ],
                        ],
                    ],
                    'about' => [
                        'type' => 'literal',
                        'priority' => 500,
                        'options' => [
                            'route'    => '/about/',
                            'defaults' => [
                                'module'     => 'Application',
                                'section'    => 'Page',
                                'controller' => 'Application\Controller\Mobile',
                                'action'     => 'about',
                            ],
                        ],
                    ],
                    'textPage' => [
                        'type' => 'segment',
                        'priority' => 100,
                        'options' => [
                            'route' => '/:path',
                            'constraints' => [
                                'path' => '.*',
                            ],
                            'defaults' => [
                                'controller' => 'Application\Controller\Mobile',
                                'action'     => 'page',
                            ]
                        ]
                    ],
                    'greeting' => [
                        'type' => 'Literal',
                        'priority' => 500,
                        'options' => [
                            'route' => '/greeting/',
                            'defaults' => [
                                'controller' => 'Application\Controller\Index',
                                'action'     => 'greeting',
                            ]
                        ]
                    ],
                ],
            ],
            'home' => [
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'priority' => 500,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                        'module'     => 'Application',
                        'section'    => 'Page',
                    ],
                ],
            ],
            'about' => [
                'type' => 'literal',
                'priority' => 500,
                'options' => [
                    'route'    => '/about/',
                    'defaults' => [
                        'module'     => 'Application',
                        'section'    => 'Page',
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'about',
                    ],
                ],
            ],
            'redirect' => [
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'priority' => 500,
                'options' => [
                    'route'    => '/redirect/',
                    'defaults' => [
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'redirect',
                        'module'     => 'Application',
                        'section'    => 'Page',
                    ],
                ],
            ],
            'sitemap' => [
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'priority' => 500,
                'options' => [
                    'route'    => '/sitemap/',
                    'defaults' => [
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'sitemap',
                        'module'     => 'Application',
                        'section'    => 'Page',
                    ],
                ],
            ],
            'robots' => [
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'priority' => 500,
                'options' => [
                    'route'    => '/robots/',
                    'defaults' => [
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'robots',
                        'module'     => 'Application',
                        'section'    => 'Page',
                    ],
                ],
            ],
            'adminIndex' => [
                'type'    => 'literal',
                'priority' => 100,
                'options' => [
                    'route'    => '/admin/',
                    'defaults' => [
                        'module'     => 'Application',
                        'section'    => 'Index',
                        'controller' => 'ApplicationAdmin\Controller\Index',
                        'action'     => 'index',
                        'side'       => 'admin',
                    ],
                ],
            ],
            'adminApplication' => [
                'type'    => 'segment',
                'priority' => 600,
                'options' => [
                    'route'    => '/admin/application',
                    'defaults' => [
                        'module'     => 'Application',
                        'section'    => 'Application',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'about' => [
                        'type'    => 'segment',
                        'priority' => 500,
                        'options' => [
                            'route'    => '/about[/:action][/:id]/',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'     => '[0-9]+',
                            ],
                            'defaults' => [
                                'module'     => 'Application',
                                'section'    => 'About',
                                'controller' => 'ApplicationAdmin\Controller\About',
                                'action'     => 'index',
                                'side'       => 'admin'
                            ],
                        ],
                    ],
                    'page' => [
                        'type'    => 'segment',
                        'priority' => 500,
                        'options' => [
                            'route'    => '/page[/:action][/:id]/',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'     => '[0-9]+',
                            ],
                            'defaults' => [
                                'module'     => 'Application',
                                'section'    => 'Page',
                                'controller' => 'ApplicationAdmin\Controller\Page',
                                'action'     => 'index',
                                'side'       => 'admin'
                            ],
                        ],
                    ],
                    'countries' => [
                        'type'    => 'segment',
                        'priority' => 500,
                        'options' => [
                            'route'    => '/countries[/:action][/:id]/',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'     => '[0-9]+',
                            ],
                            'defaults' => [
                                'module'     => 'Application',
                                'section'    => 'Countries',
                                'controller' => 'ApplicationAdmin\Controller\Countries',
                                'action'     => 'index',
                                'side'       => 'admin'
                            ],
                        ],
                    ],
                    'settings' => [
                        'type'    => 'segment',
                        'priority' => 500,
                        'options' => [
                            'route'    => '/settings[/:action][/:id]/',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'     => '[0-9]+',
                            ],
                            'defaults' => [
                                'module'     => 'Application',
                                'section'    => 'Settings',
                                'controller' => 'ApplicationAdmin\Controller\Settings',
                                'action'     => 'index',
                                'side'       => 'admin'
                            ],
                        ],
                    ],
                    'content' => [
                        'type'    => 'segment',
                        'priority' => 500,
                        'options' => [
                            'route'    => '/content[/:action]/',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ],
                            'defaults' => [
                                'module'     => 'Application',
                                'section'    => 'Content',
                                'controller' => 'ApplicationAdmin\Controller\Content',
                                'action'     => 'index',
                                'side'       => 'admin'
                            ],
                        ],
                    ],
                    'service' => [
                        'type'    => 'segment',
                        'priority' => 500,
                        'options' => [
                            'route'    => '/service[/:action][/:id]/',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'     => '[0-9]+',
                            ],
                            'defaults' => [
                                'module'     => 'Application',
                                'section'    => 'Service',
                                'controller' => 'ApplicationAdmin\Controller\Service',
                                'action'     => 'index',
                                'side'       => 'admin'
                            ],
                        ],
                    ],
                ],
            ],
            'admin' => [
                'type'    => 'segment',
                'priority' => 400,
                'options' => [
                    'route'    => '/admin[/:module][/:section][/:action][/:id]/',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => 'ApplicationAdmin\Controller\Index',
                        'action'     => 'index',
                        'side'       => 'admin',
                    ],
                ],
            ],
            'error' => [
                'type' => 'Literal',
                'priority' => 200,
                'options' => [
                    'route' => '/error/',
                    'defaults' => [
                        'controller' => __NAMESPACE__ . '\Controller\Error',
                        'action'     => 'index',
                    ]
                ]
            ],
            'textPage' => [
                'type' => 'segment',
                'priority' => 100,
                'options' => [
                    'route' => '/:path',
                    'constraints' => [
                        'path' => '.*',
                    ],
                    'defaults' => [
                        'controller' => __NAMESPACE__ . '\Controller\Index',
                        'action'     => 'page',
                    ]
                ]
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [
            'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
        ],
    ],
    'translator' => [
        'locale' => 'ru',
        'translation_file_patterns' => [
            [
                'type'     => 'phparray',
                'base_dir' => __DIR__ . '/../languages',
                'pattern'  => '%s.php',
            ],
            /*[
                'type'     => 'phparray',
                'base_dir' => __DIR__ . '/../languages/',
                'pattern'  => '%s.php',
                'text_domain' => 'Forms',
            ],
            [
                'type'     => 'phparray',
                'base_dir' => __DIR__ . '/../languages/' . __NAMESPACE__ . '/',
                'pattern'  => '%s.php',
                'text_domain' => __NAMESPACE__,
            ],
            [
                'type'     => 'phparray',
                'base_dir' => __DIR__ . '/../languages/Forms/',
                'pattern'  => '%s.php',
                'text_domain' => 'Forms',
            ],*/
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => $develop,
        'display_exceptions'       => $develop,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/not-found',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/main.phtml',
            'pagination-slide'        => __DIR__ . '/../view/pagination/slide.phtml',
            'pagination-slide-auto'   => __DIR__ . '/../view/pagination/slide-auto.phtml',
            'mobile-pagination-slide' => __DIR__ . '/../view/pagination/mobile-slide.phtml',
            'admin-pagination-slide'  => __DIR__ . '/../view/pagination/admin-slide.phtml',

            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
];
