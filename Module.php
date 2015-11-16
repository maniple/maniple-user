<?php

namespace ModUser;

use Zend\Mvc\MvcEvent;

class Module
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'prefixes' => array(
                    'ModUser_' => __DIR__ . '/library',
                ),
            ),
        );
    }

    public function getConfig()
    {
        return array_merge(
            require __DIR__ . '/configs/resources.config.php',
            array(
                'resources' => array(
                    'front_controller' => array(
                        'controllerDirectory' => array(
                            'mod-user' => __DIR__ . '/controllers',
                        ),
                    ),
                    'router' => array(
                        'routes' => require __DIR__ . '/configs/routes.config.php',
                    ),
                    'view' => array(
                        'scriptPath' => array(
                            __DIR__ . '/views/scripts',
                        ),
                    ),
                ),
            )
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'user.model.userMapper' => 'ModUser_Service_UserMapperFactory',
                'user.userManager'      => 'ModUser_Service_UserManagerFactory',
            ),
        );
    }

    public function onBootstrap(MvcEvent $e)
    {
        /** @var $bootstrap \Zend_Application_Bootstrap_BootstrapAbstract */
        $bootstrap = $e->getApplication()->getServiceManager()->get('Bootstrap');

        // If log resource is present register plugin which adds user-related
        // variables to extra data of a log event
        if ($bootstrap->hasResource('Log')) {
            $log = $bootstrap->getResource('Log');

            /** @var $frontController \Zend_Controller_Front */
            $frontController = $bootstrap->getResource('FrontController');
            $frontController->registerPlugin(new \ModUser_Plugin_LogExtras($log, $frontController));
        }
    }
}
