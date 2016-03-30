<?php

class ModUser_Bootstrap extends Maniple_Application_Module_Bootstrap
{
    public function getResourcesConfig()
    {
        return require dirname(__FILE__) . '/configs/resources.config.php';
    }

    /**
     * Register module routes
     */
    protected function _initRouter()
    {
        /** @var Zend_Application_Bootstrap_BootstrapAbstract $bootstrap */
        $bootstrap = $this->getApplication();
        $bootstrap->bootstrap('FrontController');

        /** @var Zend_Controller_Router_Rewrite $router */
        $router = $bootstrap->getResource('FrontController')->getRouter();
        $router->addConfig(new Zend_Config(require dirname(__FILE__) . '/configs/routes.config.php'));
    }

    /**
     * Register view helpers
     */
    protected function _initView()
    {
        /** @var Zend_Application_Bootstrap_BootstrapAbstract $bootstrap */
        $bootstrap = $this->getApplication();
        $bootstrap->bootstrap('View');

        /** @var Zend_View_Abstract $view */
        $view = $bootstrap->getResource('View');
        $view->addScriptPath(dirname(__FILE__) . '/views/scripts');
        $view->addHelperPath(dirname(__FILE__) . '/library/View/Helper/', 'ModUser_View_Helper_');

        /** @var Zefram_Controller_Action_Helper_ViewRenderer $viewRenderer */
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
        $viewRenderer->setViewScriptPathSpec(':module/:controller/:action.:suffix', 'mod-user');
        $viewRenderer->setViewSuffix('twig', 'mod-user');
    }

    /**
     * Register controller plugins
     */
    protected function _initPlugins()
    {
        /** @var Zend_Application_Bootstrap_BootstrapAbstract $bootstrap */
        $bootstrap = $this->getApplication();

        // If log resource is present, register plugin which adds user-related
        // variables to extra data of a log event
        if ($bootstrap->hasPluginResource('Log')) {
            $bootstrap->bootstrap('Log');
            $log = $bootstrap->getResource('Log');

            /** @var Zend_Controller_Front $front */
            $front = $bootstrap->getResource('FrontController');
            $front->registerPlugin(new ModUser_Plugin_LogExtras($log, $front));
        }

        // if nothing is returned, resource is not added to the container
    }

    protected function _initTranslate()
    {
        $bootstrap = $this->getApplication();
        $bootstrap->bootstrap('Translate');

        /** @var Zend_Translate $translate */
        $translate = $bootstrap->getResource('Translate');
        $locale = $translate->getLocale();

        $translationsDir = dirname(__FILE__) . '/languages/' . $locale;

        if (is_dir($translationsDir)) {
            $translate->addTranslation(array(
                'content' => $translationsDir,
                'locale'  => $locale,
            ));
        }
    }

    protected function _initEntityManager()
    {
        $bootstrap = $this->getApplication();

        /** @var ManipleCore\Doctrine\Config $config */
        $config = $bootstrap->getResource('EntityManager.config');
        if ($config) {
            $config->addPath(__DIR__ . '/library/Entity');
        }
    }
}
