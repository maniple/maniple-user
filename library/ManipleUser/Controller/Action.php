<?php

class ManipleUser_Controller_Action extends Maniple_Controller_Action
{
    /**
     * @Inject('Translate')
     * @var Zend_Translate
     */
    protected $_translate;

    /**
     * Plugin for switching to localized view script if available
     * @throws Zend_View_Exception
     */
    public function preDispatch()
    {
        $locale = $this->_translate->getAdapter()->getLocale();
        $scriptPathSpec = $this->_helper->viewRenderer->getViewScriptPathSpec();
        $this->_helper->viewRenderer->setViewScriptPathSpec(':module.' . $locale . '/:controller/:action.:suffix', $this->_request->getModuleName());
        $scriptPath = $this->_helper->viewRenderer->getViewScript();
        if (!$this->view->getScriptPath($scriptPath)) {
            $this->_helper->viewRenderer->setViewScriptPathSpec($scriptPathSpec, $this->_request->getModuleName());
        }
    }

    /**
     * @deprecated Use @Inject annotation
     * @return ManipleUser_Service_Security
     */
    public function getSecurity()
    {
        return $this->getResource('user.sessionManager');
    }

    /**
     * @return ManipleUser_Service_Security
     * @deprecated Use {@link getSecurity()} instead
     */
    public function getSecurityContext()
    {
        return $this->getSecurity();
    }

    /**
     * @return ManipleUser_Service_UserManager
     * @deprecated Use @Inject annotation
     */
    public function getUserManager()
    {
        return $this->getResource('user.userManager');
    }

    /**
     * @param string $name
     * @return string
     * @noinspection PhpDocMissingThrowsInspection
     */
    public function getLocalizedScriptPath($name)
    {
        $locale = $this->_translate->getAdapter()->getLocale();
        $localizedName = str_replace('maniple-user/', "maniple-user.{$locale}/", $name);
        return $this->view->getScriptPath($localizedName) ? $localizedName : $name;
    }
}
