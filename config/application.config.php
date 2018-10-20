<?php
$develop = getenv('APPLICATION_ENV') == 'dev';

$modules = array(
    'Zf2Whoops',
    'Application',
    'User',
    'Contacts',
    'Reviews',
    'Museums',
    'Excursions',
    'Transports',
    'Comments',
    'Sync',
);

return array(
    'modules' => $modules,
    'session' => array(
        'config' => array(
            'class' => 'Zend\Session\Config\SessionConfig',
        ),
        'storage' => 'Zend\Session\Storage\SessionArrayStorage',
        'validators' => array(
            array(
                'Zend\Session\Validator\RemoteAddr',
                'Zend\Session\Validator\HttpUserAgent',
            ),
        ),
    ),
    'module_listener_options' => array(
        'config_glob_paths'    => array(
            'config/autoload/{,*.}{global,local}.php',
        ),
        'module_paths' => array(
            './module',
            './vendor',
        ),
    ),
);
