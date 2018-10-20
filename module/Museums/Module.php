<?php

namespace Museums;

use MuseumsAdmin\Model\Museum;
use MuseumsAdmin\Model\Point;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;

class Module implements AutoloaderProviderInterface
{
    public function getViewHelperConfig() {
        return [
            'invokables' => [
                'MuseumsList'   => 'Museums\View\Helper\MuseumsList',
                'MuseumsPointsList' => 'Museums\View\Helper\MuseumsPointsList',
            ],
        ];
    }

    public function getServiceConfig()
    {
        return array(
            'invokables' => [
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
                'MuseumsAdmin\Service\PointsService' => function ($sm) {
                    return new \MuseumsAdmin\Service\PointsService(new Point());
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