<?php

/**
 * @property Zend_Controller_Request_Http $_request
 */
class ManipleUser_PasswordController_ForgotAction
    extends Maniple_Controller_Action_StandaloneForm
{
    /**
     * @Inject('user.model.userMapper')
     * @var ManipleUser_Model_UserMapperInterface
     */
    protected $_userRepository;

    /**
     * @Inject('Zefram_Db')
     * @var Zefram_Db
     */
    protected $_db;

    protected function _prepare()
    {
        $security = $this->getSecurity();

        if ($security->isAuthenticated()) {
            $this->_helper->flashMessenger->addErrorMessage(
                $this->view->translate(
                    'You cannot request resetting your password while being a logged in user.'
                )
            );
            return $this->_helper->redirector->gotoUrlAndExit('/');
        }

        $this->_form = new ManipleUser_Form_PasswordForgot($this->_userRepository);
        $this->getSessionNamespace('forgot')->unsetAll();
    }

    protected function _process()
    {
        /** @var ManipleUser_Model_UserInterface $user */
        $user = $this->_form->getElement('username')->getValidator('UserExists')->user;

        $reset = $this->_db->getTable(ManipleUser_Model_DbTable_PasswordResets::className)->createRow();
        $reset->reset_id = Zefram_Math_Rand::getString(64);
        $reset->created_at = time();
        $reset->expires_at = time() + 3600; // TODO lifetime
        $reset->ip_addr = $this->_request->getClientIp();
        $reset->user_id = $user->getId();
        $reset->save();

        // nofity user about password reset

        $message = new Zefram_Mail;
        $message->setType(Zend_Mime::MULTIPART_RELATED);
        $message->setSubject($this->view->translate('Password change'));
        $message->addTo($user->getEmail());

        $this->view->url_password_reset = $this->view->serverUrl() . $this->view->url('user.password.reset', array('reset_id' => $reset->reset_id));
        $this->view->user = $user;
        $this->view->message = $message;

        $message->setBodyHtml($this->view->render('maniple-user/password/password-forgot-mail.twig'));
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
