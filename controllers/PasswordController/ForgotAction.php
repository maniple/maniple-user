<?php

class ModUser_PasswordController_ForgotAction
    extends Zefram_Controller_Action_StandaloneForm
{
    protected function _prepare()
    {
        $security = $this->getSecurity();

        if ($security->isAuthenticated()) {
            $this->flashMessage(
                $this->view->translate(
                    'You cannot request resetting your password while being a logged in user.'
                ),
                'error'
            );
            return $this->_helper->redirector->gotoUrlAndExit('/');
        }

        $this->_form = new ModUser_Form_PasswordForgot($this->getUserManager());
        $this->getSessionNamespace('forgot')->unsetAll();
    }

    protected function _process()
    {
        $user = $this->_form->getElement('username')->getValidator('UserExists')->user;

        $reset = $this->getTableManager()->getTable('ModUser_Model_DbTable_PasswordResets')->createRow();
        $reset->reset_id = Zefram_Math_Rand::getString(64);
        $reset->created_at = time();
        $reset->expires_at = time() + 3600; // TODO lifetime
        $reset->user_id = $user->getId();
        $reset->save();

        // nofity user about password reset

        $message = new Zefram_Mail;
        $message->setType(Zend_Mime::MULTIPART_RELATED);
        $message->setSubject($this->view->translate('Your password has been changed'));
        $message->addTo($user->getEmail());

        $this->view->url_password_reset = $this->view->serverUrl() . $this->view->url('user.password.reset', array('reset_id' => $reset->reset_id));
        $this->view->user = $user;
        $this->view->message = $message;

        $message->setBodyHtml($this->view->render('mod-user/password/password-forgot-mail.twig'));
        $message->send();

        if ($this->_request->isXmlHttpRequest()) {
            $this->_helper->json(array(
                'success' => true,
                'message' => $this->view->translate('Password has been successfully changed'),
            ));
            return false;
        }

        $sessionNamespace = $this->getSessionNamespace('forgot');
        $sessionNamespace->complete = true;

        return $this->view->url('user.password.forgot_complete');
    }
}
