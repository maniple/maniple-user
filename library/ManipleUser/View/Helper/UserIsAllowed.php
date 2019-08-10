<?php

class ManipleUser_View_Helper_UserIsAllowed extends Maniple_View_Helper_Abstract
{
    /**
     * @Inject('user.sessionManager')
     * @var ManipleUser_Service_Security
     */
    protected $_securityContext;

    /**
     * @param string $permission
     * @return bool
     */
    public function userIsAllowed($permission)
    {
        return $this->_securityContext->isAllowed($permission);
    }
}
