<?php

namespace Faq;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\MvcEvent;

class Module implements AutoloaderProviderInterface
{
    public function getViewHelperConfig() {
        return array(
            'factories' => array(
                'faqFooter' => function ($sm) {
                    $faq = $sm->getServiceLocator()->get('Faq\Model\Faq');
                    return new \Faq\View\Helper\FaqFooter($faq);
                },
            )
        );
    }

    public function getServiceConfig()
    {
        return array(
            'invokables' => array(
                'FaqAdmin\Service\FaqService' => 'FaqAdmin\Service\FaqService',
                'FaqAdmin\Service\FeedbackService' => 'FaqAdmin\Service\FeedbackService',
                'Faq\Model\FeedbackService' => 'Faq\Model\FeedbackService',
            ),
            'initializers' => array(
                function ($instance, $sm) {
                    if ($instance instanceof \Zend\Db\Adapter\AdapterAwareInterface) {
                        $instance->setDbAdapter($sm->get('Zend\Db\Adapter\Adapter'));
                    }
                }
            ),
            'factories' => array(
                'Faq\Model\Faq' => function ($sm) {
                    $faq = new \Faq\Model\Faq();
                    return $faq->setId(1);
                },
            )
        );
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__           => __DIR__ . '/src/' . __NAMESPACE__,
                    __NAMESPACE__ . 'Admin' => __DIR__ . '/src/Admin',
                )
            )
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}