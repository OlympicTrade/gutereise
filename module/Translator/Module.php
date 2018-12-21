<?php

namespace Translator;

use TranslatorAdmin\Model\Translator;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;

class Module implements AutoloaderProviderInterface
{
    public function getViewHelperConfig() {
        return [
            'invokables' => [
                'TranslatorList'       => 'Translator\View\Helper\TranslatorList',
            ],
        ];
    }

    public function getServiceConfig()
    {
        return [
            'invokables' => [
                'Translator\Service\TranslatorService'  => 'Translator\Service\TranslatorService',
            ],
            'initializers' => [
                function ($instance, $sm) {
                    if ($instance instanceof \Zend\ServiceManager\ServiceLocatorAwareInterface) {
                        $instance->setServiceLocator($sm);
                    }
                }
            ],
            'factories' => [
                'TranslatorAdmin\Service\TranslatorService' => function ($sm) {
                    return new \TranslatorAdmin\Service\TranslatorService(new Translator());
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