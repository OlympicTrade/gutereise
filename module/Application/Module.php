<?php
namespace Application;

//use Translator\Model\Translator;
use Application\Model\Settings;
use Translator\Model\Translator;
use Aptero\Compressor\Compressor;
use Aptero\Mail\Mail;
use Zend\Mvc\MvcEvent;

//use Zend\Mvc\I18n\Translator;
//use Zend\Validator\AbstractValidator;

use Zend\Session\SessionManager;
use Zend\Session\Container;

use Zend\Db\TableGateway\Feature\GlobalAdapterFeature as StaticDbAdapter;
use Aptero\Cache\Feature\GlobalAdapterFeature as StaticCacheAdapter;

class Module
{
    public function onBootstrap(MvcEvent $mvcEvent)
    {
        $application   = $mvcEvent->getApplication();
        $sm = $application->getServiceManager();
        $eventManager = $mvcEvent->getApplication()->getEventManager();
        $sharedManager = $application->getEventManager()->getSharedManager();

        $side = substr($_SERVER['REQUEST_URI'], 0, 7) == '/admin/' ? 'admin' : 'public';
        $eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, function(MvcEvent $event) {
            $viewModel = $event->getViewModel();
            $viewModel->setTemplate('error/layout');
        }, -200);

        //Errors handler
        if ($side == 'admin') {
            $eventManager->attach(MvcEvent::EVENT_DISPATCH, array($this, 'errorDispatcherAdmin'), 100);
            $eventManager->attach(MvcEvent::EVENT_RENDER, array($this, 'onRenderAdmin'), 100);
        } else {
            $eventManager->attach(MvcEvent::EVENT_DISPATCH, array($this, 'errorDispatcher'), 100);
            $eventManager->attach(MvcEvent::EVENT_RENDER, array($this, 'onRenderPublic'), 100);
        }

        $eventManager->attach(MvcEvent::EVENT_DISPATCH, array($this, 'mvcPreDispatch'), 100);
        $eventManager->attach(MvcEvent::EVENT_DISPATCH, array($this, 'initMailSms'), 100);
        $eventManager->attach(MvcEvent::EVENT_ROUTE, array($this, 'initTranslate'));

        //Default Db Adapter
        StaticDbAdapter::setStaticAdapter($sm->get('Zend\Db\Adapter\Adapter'));
        StaticCacheAdapter::setStaticAdapter($sm->get('DataCache'), 'data');
        StaticCacheAdapter::setStaticAdapter($sm->get('HtmlCache'), 'html');

        //Errors log
        /*if(MODE == 'public') {
            $sharedManager->attach('Zend\Mvc\Application', 'dispatch.error', function ($e) use ($sm) {
                $mail = new Mail();
                $mail->setTemplate(
                    MODULE_DIR . '/Application/view/error/error-mail.phtml',
                    MODULE_DIR . '/Application/view/mail/error.phtml')
                    ->setHeader('Ошибка')
                    ->setVariables(['exception' => $e->getParam('exception')])
                    ->addTo('info@aptero.ru')
                    ->send();
            });
        }*/
    }

    public function onRenderAdmin(MvcEvent $mvcEvent)
    {

    }

    public function onRenderPublic(MvcEvent $mvcEvent)
    {
        $this->compressCssJs();
    }

    public function compressCssJs()
    {
        $compressor = new Compressor();

        $compressor->compress([
            PUBLIC_DIR . '/css/libs/reset.css',
            PUBLIC_DIR . '/css/libs/lightslider.css',
            PUBLIC_DIR . '/css/libs/lightgallery.css',
            PUBLIC_DIR . '/css/libs/fancybox.css',
            PUBLIC_DIR . '/css/libs/grid.css',
            PUBLIC_DIR . '/css/elements.scss',
            PUBLIC_DIR . '/css/main.scss',
        ],  'css');
		
		 $jsDesktop = [
             0  => PUBLIC_DIR . '/js/config.js',
             10 => PUBLIC_DIR . '/js/libs/languages.js',
             20 => PUBLIC_DIR . '/js/libs/fancybox/fancybox.js',
             25 => PUBLIC_DIR . '/js/libs/history.js',
             30 => PUBLIC_DIR . '/js/libs/inputmask.js',
             35 => PUBLIC_DIR . '/js/libs/lightslider.js',
             37 => PUBLIC_DIR . '/js/libs/lightgallery.js',
             45 => PUBLIC_DIR . '/js/libs/aptero.js',
             47 => PUBLIC_DIR . '/js/libs/parallax.js',
             50 => PUBLIC_DIR . '/js/libs/cookie.js',
             65 => PUBLIC_DIR . '/js/libs/form-validator.js',
             75 => PUBLIC_DIR . '/js/libs/counter.js',
             80 => PUBLIC_DIR . '/js/libs/sidebar.js',
             85 => PUBLIC_DIR . '/js/libs/youtube-bg.js',
             90 => PUBLIC_DIR . '/js/main.js',
             93 => PUBLIC_DIR . '/js/catalog.js',
             95 => PUBLIC_DIR . '/js/list.js',
        ];

        $compressor->compress($jsDesktop,  'js');

        //Mobile
        $compressor->compress([
            PUBLIC_DIR . '/css/libs/reset.css',
            PUBLIC_DIR . '/css/libs/lightslider.css',
            PUBLIC_DIR . '/css/libs/lightgallery.css',
            PUBLIC_DIR . '/css/libs/fancybox.css',
            PUBLIC_DIR . '/css/libs/grid.css',
            PUBLIC_DIR . '/mobile/css/elements.scss',
            PUBLIC_DIR . '/mobile/css/main.scss',
        ], 'css', 'mobile');

		$jsMobile = [
            0  => PUBLIC_DIR . '/mobile/js/config.js',
            10 => PUBLIC_DIR . '/js/libs/languages.js',
            20 => PUBLIC_DIR . '/js/libs/fancybox/fancybox.js',
            23 => PUBLIC_DIR . '/js/libs/lightslider.js',
            25 => PUBLIC_DIR . '/js/libs/history.js',
            30 => PUBLIC_DIR . '/js/libs/inputmask.js',
            40 => PUBLIC_DIR . '/js/libs/aptero.js',
            45 => PUBLIC_DIR . '/js/libs/cookie.js',
            50 => PUBLIC_DIR . '/mobile/js/libs/touchwipe.js',
            60 => PUBLIC_DIR . '/js/libs/form-validator.js',
            70 => PUBLIC_DIR . '/js/libs/counter.js',
            75 => PUBLIC_DIR . '/mobile/js/main.js',
        ];

        $compressor->compress($jsMobile, 'js', 'mobile');
    }

    public function mvcPreDispatch(MvcEvent $mvcEvent)
    {
        $module  = $mvcEvent->getRouteMatch()->getParam('module');
        $section = $mvcEvent->getRouteMatch()->getParam('section');

        $mvcEvent->getApplication()->getServiceManager()->get('Application\Model\Module')
            ->setModuleName($module)
            ->setSectionName($section)
            ->load();
    }

    public function errorDispatcherAdmin(MvcEvent $mvcEvent)
    {
        /** @var \Zend\Mvc\View\Http\ViewManager $viewManager */
        $viewManager = $mvcEvent->getApplication()->getServiceManager()->get('HttpViewManager');
    }

    public function errorDispatcher(MvcEvent $mvcEvent)
    {

    }

    public function initMailSms(MvcEvent $mvcEvent)
    {
        $settings = Settings::getInstance();
        Mail::setOptions([
            'sender'    => [
                'email' => $settings->get('mail_email'),
                'name'  => $settings->get('mail_sender'),
            ],
            'connection' => [
                'name' => $settings->get('mail_smtp'),
                'host' => $settings->get('mail_smtp'),
                'port' => 465,
                'connection_class' => 'login',
                'connection_config' => [
                    'username' => $settings->get('mail_email'),
                    'password' => $settings->get('mail_password'),
                    'ssl' => 'ssl'
                ],
            ]
        ]);

        /*Sms::setOptions([
            'login'     => 'myprotein',
            'password'  => 'Uriel1Uriel',
            'key'       => '2F254C72-6978-3E42-0ACD-C5D57C7FF11F',
            'sender'    => '79522872998',
            'flash'     => false,
            'viber'     => false,
        ]);*/
    }

    public function initTranslate(MvcEvent $mvcEvent)
    {
        Translator::getInstance()->detectLanguage();

        /*$translator = new Translator();
        echo $translator->translate('Экскурсии');
        die('zxczxc');*/
        /*$files = array(
            'en' => 'en',
            'de' => 'de',
            'ru' => 'ru',
        );

        $languages = Translator::getInstance();
        $lang = $languages->getLangCode();

        if(array_key_exists($lang, $files)) {
            \Locale::setDefault($files[$lang]);
        }

        $translator = $mvcEvent->getApplication()->getServiceManager()->get('translator')->setLocale(\Locale::getDefault());
        $mvcEvent->getApplication()->getServiceManager()->get('ViewHelperManager')->get('translate')->setTranslator($translator);
        $languages->setTranslator($translator);

        $formTranslator = new Translator($translator);

        AbstractValidator::setDefaultTranslator($formTranslator, 'Forms');*/
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getViewHelperConfig() {
        return array(
            'invokables' => array(
                'IsMobile'              => 'Aptero\View\Helper\IsMobile',
                'Breadcrumbs'           => 'Application\View\Helper\Breadcrumbs',
                'BtnSwitcher'           => 'Aptero\View\Helper\BtnSwitcher',
                'FormRow'               => 'Aptero\Form\View\Helper\FormRow',
                'FormErrors'            => 'Aptero\Form\View\Helper\FormErrors',
                'Fieldset'              => 'Aptero\Form\View\Helper\Fieldset',
                'FormElement'           => 'Aptero\Form\View\Helper\FormElement',
                'FormImage'             => 'Aptero\Form\View\Helper\FormImage',
                'AdminFormFileManager'  => 'Aptero\Form\View\Helper\Admin\FormFileManager',
                'AdminFormTreeSelect'   => 'Aptero\Form\View\Helper\FormTreeSelect',
                'AdminFormImage'        => 'Aptero\Form\View\Helper\Admin\Image',
                'AdminFormProps'        => 'Aptero\Form\View\Helper\Admin\Props',
                'AdminFormAttrs'        => 'Aptero\Form\View\Helper\Admin\Attrs',
                'AdminFormImages'       => 'Aptero\Form\View\Helper\Admin\Images',
                'AdminFormContentImages'=> 'Aptero\Form\View\Helper\Admin\ContentImages',
                'AdminFormProductImages'=> 'Aptero\Form\View\Helper\Admin\ProductImages',
                'AdminFormExercisesImages'=> 'Aptero\Form\View\Helper\Admin\ExercisesImages',
                'AdminFormFile'         => 'Aptero\Form\View\Helper\Admin\File',
                'AdminFormRow'          => 'Aptero\Form\View\Helper\Admin\FormRow',
                'AdminPrice'            => 'Aptero\View\Helper\Admin\Price',
                'AdminTableList'        => 'Aptero\View\Helper\Admin\TableList',
                'AdminMenuWidget'       => 'ApplicationAdmin\View\Helper\MenuWidget',
                'AdminFormCollection'   => 'Aptero\Form\View\Helper\Admin\Collection',

                'AdminMessenger'        => 'ApplicationAdmin\View\Helper\Messenger',
                'AdminContentList'      => 'ApplicationAdmin\View\Helper\ContentList',
                'ContentRender'         => 'Application\View\Helper\ContentRender',
                'GenerateMeta'          => 'Application\View\Helper\GenerateMeta',
                //'WidgetNav'             => 'Application\View\Helper\WidgetNav',
                'LanguageSelect'        => 'Application\View\Helper\LanguageSelect',
                //'TextBlock'             => 'Application\View\Helper\TextBlock',
                //'HtmlBlocks'            => 'Application\View\Helper\HtmlBlocks',
                'Header'                => 'Application\View\Helper\Header',
                //'HeaderBlack'           => 'Application\View\Helper\HeaderBlack',
                'Price'                 => 'Aptero\View\Helper\Price',
                'SubStr'                => 'Aptero\View\Helper\SubStr',
                'Escape'                => 'Aptero\View\Helper\Escape',
                'Date'                  => 'Aptero\View\Helper\Date',
                'NotEmpty'              => 'Aptero\View\Helper\NotEmpty',
                'Link'                  => 'Aptero\View\Helper\Link',
                'Video'                 => 'Aptero\View\Helper\Video',
                'Stars'                 => 'Aptero\View\Helper\Stars',
                'Declension'            => 'Aptero\View\Helper\Declension',
                'Tr'                    => 'Aptero\View\Helper\Translator',
            ),
            'initializers' => array(
                function ($instance, $helperPluginManager) {
                    $sm = $helperPluginManager->getServiceLocator();

                    if ($instance instanceof \Zend\ServiceManager\ServiceLocatorAwareInterface) {
                        $instance->setServiceLocator($sm);
                    }
                }
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'invokables' => array(
                'ApplicationAdmin\Service\SettingsService'  => 'ApplicationAdmin\Service\SettingsService',
                'Application\Service\SystemService'  => 'Application\Service\SystemService',
                'Application\Service\SitemapService' => 'Application\Service\SitemapService',
                'Application\Model\Module'          => 'Application\Model\Module',
                'ApplicationAdmin\Model\Page'       => 'ApplicationAdmin\Model\Page',
            ),
            'factories' => array(
                'ApplicationAdmin\Service\PageService' => function ($sm) {
                    return new \ApplicationAdmin\Service\PageService(new \ApplicationAdmin\Model\Page());
                },
                'ApplicationAdmin\Service\CountriesService' => function ($sm) {
                    return new \ApplicationAdmin\Service\CountriesService(new \ApplicationAdmin\Model\Country());
                },
                'ApplicationAdmin\Service\AboutService' => function ($sm) {
                    return new \ApplicationAdmin\Service\AboutService(new \ApplicationAdmin\Model\About());
                },
                'Settings' => function ($sm) {
                    $settings = new \Application\Model\Settings();
                    $settings->setId(1);
                    return $settings;
                },
                'Zend\Session\SessionManager' => function ($sm) {
                    $config = $sm->get('config');
                    if (isset($config['session'])) {
                        $session = $config['session'];

                        $sessionConfig = null;
                        if (isset($session['config'])) {
                            $class = isset($session['config']['class'])  ? $session['config']['class'] : 'Zend\Session\Config\SessionConfig';
                            $options = isset($session['config']['options']) ? $session['config']['options'] : array();
                            $sessionConfig = new $class();
                            $sessionConfig->setOptions($options);
                        }

                        $sessionStorage = null;
                        if (isset($session['storage'])) {
                            $class = $session['storage'];
                            $sessionStorage = new $class();
                        }

                        $sessionSaveHandler = null;
                        if (isset($session['save_handler'])) {
                            $sessionSaveHandler = $sm->get($session['save_handler']);
                        }

                        $sessionManager = new SessionManager($sessionConfig, $sessionStorage, $sessionSaveHandler);

                        if (isset($session['validator'])) {
                            $chain = $sessionManager->getValidatorChain();
                            foreach ($session['validator'] as $validator) {
                                $validator = new $validator();
                                $chain->attach('session.validate', array($validator, 'isValid'));

                            }
                        }
                    } else {
                        $sessionManager = new SessionManager();
                    }
                    Container::setDefaultManager($sessionManager);
                    return $sessionManager;
                },
            ),
        );
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__   => __DIR__ . '/src/' . __NAMESPACE__,
                    __NAMESPACE__.'Admin'   => __DIR__ . '/src/Admin',
                ),
            ),
        );
    }
}
