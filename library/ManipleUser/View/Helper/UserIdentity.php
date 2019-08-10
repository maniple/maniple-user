<?php

class ManipleUser_View_Helper_UserIdentity extends Maniple_View_Helper_Abstract
{
    /**
     * @Inject('user.sessionManager')
     * @var ManipleUser_Service_Security
     */
    protected $_securityContext;

    /**
     * @return Maniple_Security_UserInterface|null
     * @deprecated
     */
    public function getUser()
    {
        return $this->_securityContext->getUser();
    }

    /**
     * @return Maniple_Security_UserInterface|null
     */
    public function userIdentity()
    {
        return $this->_securityContext->getUser();
    }
}
