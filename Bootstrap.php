<?php

class ManipleUser_Bootstrap extends Maniple_Application_Module_Bootstrap
    implements Maniple_Menu_MenuManagerProviderInterface
{
    const className = __CLASS__;

    public function getModuleDependencies()
    {
        return array();
    }

    public function getResourcesConfig()
    {
        return require __DIR__ . '/configs/resources.config.php';
    }

    public function getRoutesConfig()
    {
        return require __DIR__ . '/configs/routes.config.php';
    }

    public function getTranslationsConfig()
    {
        return array(
            'adapter' => Zend_Translate::AN_ARRAY,
            'scan'    => Zend_Translate::LOCALE_DIRECTORY,
            'content' => __DIR__ . '/languages',
        );
    }

    public function getViewConfig()
    {
        return array(
            'scriptPaths' => __DIR__ . '/views/scripts',
            'helperPaths' => array(
                'ManipleUser_View_Helper_' => __DIR__ . '/library/ManipleUser/View/Helper/',
            ),
            'scriptPathSpec' => ':module/:controller/:action.:suffix',
            'suffix' => 'twig',
        );
    }

    public function getMenuManagerConfig()
    {
        return array(
            'builders' => array(
                ManipleUser_Menu_MenuBuilder::className,
            ),
        );
    }

    protected function _initControllerPlugins()
    {
        $bootstrap = $this->getApplication();

        // If log resource is present, register plugin which adds user-related
        // variables to extra data of a log event
        if ($bootstrap->hasPluginResource('Log')) {
            $bootstrap->bootstrap('Log');
            $log = $bootstrap->getResource('Log');

            /** @var Zend_Controller_Front $front */
            $front = $bootstrap->getResource('FrontController');
            $front->registerPlugin(new ManipleUser_Controller_Plugin_LogExtras($log, $front));
        }

        // if nothing is returned, resource is not added to the container
    }

    protected function _initEntityManager()
    {
        $bootstrap = $this->getApplication();

        /** @var ManipleDoctrine\Config $config */
        $config = $bootstrap->getResource('EntityManager.config');
        if ($config) {
            $config->addPath(__DIR__ . '/library/ManipleUser/Entity');
        }
    }

    protected function _initViewStyles()
    {
        /** @var Zefram_View_Abstract $view */
        $view = $this->getApplication()->getResource('View');
        $view->headLink()->appendStylesheet($view->baseUrl('modules/maniple-user/css/style.css'), 'all');
    }
}
