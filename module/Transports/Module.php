<?php

namespace Transports;

use TransportsAdmin\Model\Transport;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;

class Module implements AutoloaderProviderInterface
{
    public function getViewHelperConfig() {
        return [
            'invokables' => [
                'TransportsList'       => 'Transports\View\Helper\TransportsList',
                'MobileTransportsList' => 'Transports\View\Helper\MobileTransportsList',
            ],
        ];
    }

    public function getServiceConfig()
    {
        return [
            'invokables' => [
                'Transports\Service\TransportsService'  => 'Transports\Service\TransportsService',
            ],
            'initializers' => [
                function ($instance, $sm) {
                    if ($instance instanceof \Zend\ServiceManager\ServiceLocatorAwareInterface) {
                        $instance->setServiceLocator($sm);
                    }
                }
            ],
            'factories' => [
                'TransportsAdmin\Service\TransportsService' => function ($sm) {
                    return new \TransportsAdmin\Service\TransportsService(new Transport());
                },
            ]
        ];
    }

    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__           => __DIR__ . '/src/' . __NAMESPACE__,
                    __NAMESPACE__ . 'Admin' => __DIR__ . '/src/Admin',
                ]
            ]
        ];
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}