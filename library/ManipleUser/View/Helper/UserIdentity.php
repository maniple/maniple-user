<?php

class ManipleUser_View_Helper_UserIdentity extends Zend_View_Helper_Abstract
{
    /**
     * @var ManipleUser_Service_Security
     */
    protected $_securityContext;

    /**
     * @return ManipleUser_Service_Security
     */
    public function getSecurityContext()
    {
        if ($this->_securityContext === null) {
            $this->_securityContext = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource('user.sessionManager');
        }
        return $this->_securityContext;
    }

    /**
     * @return Maniple_Security_UserInterface|null
     */
    public function getUser()
    {
        $securityContext = $this->getSecurityContext();
        if (!$securityContext->isAuthenticated()) {
            return null;
        }
        return $securityContext->getUser();
    }

    /**
     * Proxy to {@link getUser()}.
     *
     * @return Maniple_Security_UserInterface|null
     */
    public function userIdentity()
    {
        return $this->getUser();
    }
}
