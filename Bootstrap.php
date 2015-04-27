<?php

class ModUser_Bootstrap extends Maniple_Application_Module_Bootstrap
{
    public function getResourcesConfig()
    {
        $security = new ModUser_Service_Security();
        $security->addSuperUserId(1);
        $security->addSuperUserId(2);
        $security->addSuperUserId(15);
        $security->addSuperUserId(6);
        $security->addSuperUserId(21);
        $security->addSuperUserId(40);

        return array(
            'security' => $security,
            'user.user_manager' => array(
                'class' => 'ModUser_Model_UserManager',
                'options' => array(
                    'tableManager' => 'resource:tableManager',
                ),
            ),
        );
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
