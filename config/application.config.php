<?php
$develop = getenv('APPLICATION_ENV') == 'dev';

$modules = [
    'Zf2Whoops',
    'Application',
    'User',
    'Contacts',
    'Reviews',
    'Museums',
    'Excursions',
    'Transports',
    'Translator',
    'Comments',
    'Sync',
];

return [
    'modules' => $modules,
    'session' => [
        'config' => [
            'class' => 'Zend\Session\Config\SessionConfig',
        ],
        'storage' => 'Zend\Session\Storage\SessionArrayStorage',
        'validators' => [
            [
                'Zend\Session\Validator\RemoteAddr',
                'Zend\Session\Validator\HttpUserAgent',
            ],
        ],
    ],
    'module_listener_options' => [
        'config_glob_paths'    => [
            'config/autoload/{,*.}{global,local}.php',
        ],
        'module_paths' => [
            './module',
            './vendor',
        ],
    ],
];
