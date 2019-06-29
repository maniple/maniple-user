<?php

// controller with password reset/recovery
class ManipleUser_PasswordController extends ManipleUser_Controller_Action
{
    public function getSessionNamespace($name)
    {
        return new Zend_Session_Namespace('user.password.' . $name);
    }

    public function forgotCompleteAction()
    {
        $sessionNamespace = $this->getSessionNamespace('forgot');
        $this->view->complete = $sessionNamespace->complete;
        $sessionNamespace->unsetAll();
    }

    /**
     * Notification after successful sending of an email with password reset
     * instructions.
     */
    public function resetCompleteAction()
    {
        $sessionNamespace = $this->getSessionNamespace('reset');
        $this->view->complete = $sessionNamespace->complete;
        $sessionNamespace->unsetAll();
    }
}
