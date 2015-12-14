<?php

class ModUser_Bootstrap extends Maniple_Application_Module_Bootstrap
{
    public function getResourcesConfig()
    {
        return require dirname(__FILE__) . '/configs/resources.config.php';
    }

    /**
     * Initializes module routes
     */
    protected function _initRoutes()
    {
        /** @var Zend_Controller_Router_Rewrite $router */
        $router = $this->getResource('FrontController')->getRouter();
        $router->addConfig(new Zend_Config(require dirname(__FILE__) . '/configs/routes.config.php'));
    }

    /**
     * Initializes LogExtras controller plugin
     */
    protected function _initLogExtras()
    {
        // If log resource is present register plugin which adds user-related
        // variables to extra data of a log event
        $log = $this->getResource('Log');

        if ($log) {
            $front = $this->getResource('FrontController');
            $front->registerPlugin(new ModUser_Plugin_LogExtras($log, $front));
        }

        // if nothing is returned, resource is not added to the container
    }
}
