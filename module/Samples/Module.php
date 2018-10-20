<?php

namespace Samples;

use SamplesAdmin\Model\Sample;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;

class Module implements AutoloaderProviderInterface
{
    public function getViewHelperConfig() {
        return [
            'invokables' => [
                'SamplesList'       => 'Samples\View\Helper\SamplesList',
            ],
        ];
    }

    public function getServiceConfig()
    {
        return [
            'invokables' => [
                'Samples\Service\SamplesService'  => 'Samples\Service\SamplesService',
            ],
            'initializers' => [
                function ($instance, $sm) {
                    if ($instance instanceof \Zend\ServiceManager\ServiceLocatorAwareInterface) {
                        $instance->setServiceLocator($sm);
                    }
                }
            ],
            'factories' => [
                'SamplesAdmin\Service\SamplesService' => function ($sm) {
                    return new \SamplesAdmin\Service\SamplesService(new Sample());
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