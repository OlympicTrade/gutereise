<?php

namespace Museums;

use MuseumsAdmin\Model\Museum;
use MuseumsAdmin\Model\Attraction;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;

class Module implements AutoloaderProviderInterface
{
    public function getViewHelperConfig() {
        return [
            'invokables' => [
                'MuseumsList'   => 'Museums\View\Helper\MuseumsList',
                'MuseumsAttractionsList' => 'Museums\View\Helper\MuseumsAttractionsList',
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
                'MuseumsAdmin\Service\AttractionsService' => function ($sm) {
                    return new \MuseumsAdmin\Service\AttractionsService(new Attraction());
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