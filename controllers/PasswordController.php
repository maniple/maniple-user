<?php

// controller with password reset/recovery
class ModUser_PasswordController extends Zefram_Controller_Action
{
    public function getSecurity()
    {
        return $this->getResource('security');
    }

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
