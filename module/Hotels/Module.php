<?php

namespace Hotels;

use HotelsAdmin\Model\Hotel;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;

class Module implements AutoloaderProviderInterface
{
    public function getViewHelperConfig() {
        return [
            'invokables' => [
                //'CommonForm'            => 'Hotels\View\Helper\CommonForm',
                'MobileHotelsList'  => 'Hotels\View\Helper\MobileHotelsList',
                'HotelsList'        => 'Hotels\View\Helper\HotelsList',
                'HotelsWidgets'     => 'Hotels\View\Helper\HotelsWidgets',
            ],
        ];
    }

    public function getServiceConfig()
    {
        return [
            'invokables' => [
                'Hotels\Service\HotelsService'  => 'Hotels\Service\HotelsService',
            ],
            'initializers' => [
                function ($instance, $sm) {
                    if ($instance instanceof \Zend\ServiceManager\ServiceLocatorAwareInterface) {
                        $instance->setServiceLocator($sm);
                    }
                }
            ],
            'factories' => [
                'HotelsAdmin\Service\HotelsService' => function ($sm) {
                    return new \HotelsAdmin\Service\HotelsService(new Hotel());
                },
            ]
        ];
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