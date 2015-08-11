<?php

class ModUser_Bootstrap extends Maniple_Application_Module_Bootstrap
{
    public function getResourcesConfig()
    {
        return require dirname(__FILE__) . '/configs/resources.config.php';
    }

    public function getRoutesConfig()
    {
        return require dirname(__FILE__) . '/configs/routes.config.php';
    }
    
    /**
     * Initializes LogExtras controller plugin
     *
     * @return void
     */
    protected function _initLogExtras() // {{{
    {
        // If log resource is present register plugin which adds user-related
        // variables to extra data of a log event
        $log = $this->getResource('log');

        if ($log) {
            $frontController = $this->getResource('frontController');
            $frontController->registerPlugin(new ModUser_Plugin_LogExtras($log, $frontController));
        }

        // if nothing is returned resource is not added to the container
    } // }}}
}
