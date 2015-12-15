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
        /** @var Zend_Controller_Router_Rewrite $router */
        $router = $this->getResource('FrontController')->getRouter();
        $router->addConfig(new Zend_Config(require dirname(__FILE__) . '/configs/routes.config.php'));
    }

    /**
     * Register view helpers
     */
    protected function _initView()
    {
        /** @var $bootstrap Zend_Application_Bootstrap_BootstrapAbstract */
        $bootstrap = $this->getApplication();
        $bootstrap->bootstrap('View');

        /** @var $view Zend_View_Abstract */
        $view = $bootstrap->getResource('View');
        $view->addHelperPath(dirname(__FILE__) . '/library/View/Helper/', 'ModUser_View_Helper_');
    }

    /**
     * Register controller plugins
     */
    protected function _initPlugins()
    {
        /** @var $bootstrap Zend_Application_Bootstrap_BootstrapAbstract */
        $bootstrap = $this->getApplication();

        // If log resource is present, register plugin which adds user-related
        // variables to extra data of a log event
        if ($bootstrap->hasPluginResource('Log')) {
            $bootstrap->bootstrap('Log');
            $log = $bootstrap->getResource('Log');

            /** @var $front Zend_Controller_Front */
            $front = $this->getResource('FrontController');
            $front->registerPlugin(new ModUser_Plugin_LogExtras($log, $front));
        }

        // if nothing is returned, resource is not added to the container
    }
}
