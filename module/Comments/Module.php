<?php

namespace Comments;

use CommentsAdmin\Model\Comment;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;

class Module implements AutoloaderProviderInterface
{
    public function getViewHelperConfig() {
        return [
            'invokables' => [
                'Comments'       => 'Comments\View\Helper\Comments',
                'CommentsList'   => 'Comments\View\Helper\CommentsList',
            ],
        ];
    }

    public function getServiceConfig()
    {
        return [
            'invokables' => [
                'Comments\Service\CommentsService'  => 'Comments\Service\CommentsService',
            ],
            'initializers' => [
                function ($instance, $sm) {
                    if ($instance instanceof \Zend\ServiceManager\ServiceLocatorAwareInterface) {
                        $instance->setServiceLocator($sm);
                    }
                }
            ],
            'factories' => [
                'CommentsAdmin\Service\CommentsService' => function ($sm) {
                    return new \CommentsAdmin\Service\CommentsService(new Comment());
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