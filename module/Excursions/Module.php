<?php

namespace Excursions;

use ExcursionsAdmin\Model\Excursion;
use ExcursionsAdmin\Model\ExcursionType;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;

class Module implements AutoloaderProviderInterface
{
    public function getViewHelperConfig() {
        return [
            'invokables' => [
                'ExcursionsList'       => 'Excursions\View\Helper\ExcursionsList',
                'ExcursionsWidgets'    => 'Excursions\View\Helper\ExcursionsWidgets',
                'CommonForm'           => 'Excursions\View\Helper\CommonForm',
            ],
        ];
    }

    public function getServiceConfig()
    {
        return [
            'invokables' => [
                'Excursions\Service\ExcursionsService'  => 'Excursions\Service\ExcursionsService',
            ],
            'initializers' => [
                function ($instance, $sm) {
                    if ($instance instanceof \Zend\ServiceManager\ServiceLocatorAwareInterface) {
                        $instance->setServiceLocator($sm);
                    }
                }
            ],
            'factories' => [
                'ExcursionsAdmin\Service\ExcursionsService' => function ($sm) {
                    return new \ExcursionsAdmin\Service\ExcursionsService(new Excursion());
                },
                'ExcursionsAdmin\Service\TypesService' => function ($sm) {
                    return new \ExcursionsAdmin\Service\TypesService(new ExcursionType());
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