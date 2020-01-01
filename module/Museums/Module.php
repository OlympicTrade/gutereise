<?php

namespace Museums;

use MuseumsAdmin\Model\Museum;
use MuseumsAdmin\Model\Tags;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;

class Module implements AutoloaderProviderInterface
{
    public function getViewHelperConfig() {
        return [
            'invokables' => [
                'museumsList'       => 'Museums\View\Helper\MuseumsList',
                'mobileMuseumsList' => 'Museums\View\Helper\MobileMuseumsList',
            ],
        ];
    }

    public function getServiceConfig()
    {
        return array(
            'invokables' => [
                'Museums\Service\SystemService'   => 'Museums\Service\SystemService',
                'Museums\Service\MuseumsService'  => 'Museums\Service\MuseumsService',
            ],
            'initializers' => [
                function ($instance, $sm) {
                    if ($instance instanceof \Zend\ServiceManager\ServiceLocatorAwareInterface) {
                        $instance->setServiceLocator($sm);
                    }
                }
            ],
            'factories' => [
                'MuseumsAdmin\Service\MuseumsService' => function ($sm) {
                    return new \MuseumsAdmin\Service\MuseumsService(new Museum());
                },
                'MuseumsAdmin\Service\TagsService' => function ($sm) {
                    return new \MuseumsAdmin\Service\TagsService(new Tags());
                },
            ]
        );
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => array(
                    __NAMESPACE__           => __DIR__ . '/src/' . __NAMESPACE__,
                    __NAMESPACE__ . 'Admin' => __DIR__ . '/src/Admin',
                )
            ]
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}