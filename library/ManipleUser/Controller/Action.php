<?php

class ManipleUser_Controller_Action extends Maniple_Controller_Action
{
    /**
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
     * @return ManipleUser_Model_UserManager
     */
    public function getUserManager()
    {
        return $this->getResource('user.userManager');
    }
}
