<?php

namespace Excursions;

use ExcursionsAdmin\Model\Excursion;
use ExcursionsAdmin\Model\Tags;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;

class Module implements AutoloaderProviderInterface
{
    public function getViewHelperConfig() {
        return [
            'invokables' => [
                'CommonForm'            => 'Excursions\View\Helper\CommonForm',
                'MobileExcursionsList'  => 'Excursions\View\Helper\MobileExcursionsList',
                'ExcursionsList'        => 'Excursions\View\Helper\ExcursionsList',
                'ExcursionsWidgets'     => 'Excursions\View\Helper\ExcursionsWidgets',
                'ExcursionsRecoWidget'  => 'Excursions\View\Helper\ExcursionsRecoWidget',
            ],
        ];
    }

    public function getServiceConfig()
    {
        return [
            'invokables' => [
                'Excursions\Service\ExcursionsService'  => 'Excursions\Service\ExcursionsService',
                'Excursions\Service\ToursService'       => 'Excursions\Service\ToursService',
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
                'ExcursionsAdmin\Service\TagsService' => function ($sm) {
                    return new \ExcursionsAdmin\Service\TagsService(new Tags());
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