<?php

class ManipleUser_RegistrationController extends ManipleUser_Controller_Action
{
    const className = __CLASS__;

    /**
     * @Inject
     * @var Zefram_Db
     */
    protected $_db;

    /**
     * @Inject
     * @var ManipleUser_Service_Signup
     */
    protected $_signupManager;

    /**
     * @return Zend_Session_Namespace
     */
    public function getSessionNamespace() // {{{
    {
        return new Zend_Session_Namespace('user.registration');
    } // }}}

    /**
     * Action executed after successful registration, i.e. a registration
     * request has been saved to the database.
     *
     * No params are expected.
     */
    public function completeAction()
    {
        // registration complete message is available for a limited amount
        // of time, and within limited number of page hops
        $sessionNamespace = $this->getSessionNamespace();

        $this->view->activated  = $sessionNamespace->activated;
        $this->view->complete   = $sessionNamespace->complete;
        $this->view->expires_at = $sessionNamespace->expires_at;
        $this->view->email      = $sessionNamespace->email;
    }

    /**
     * Link to this action is stored in the registration confirmation email
     * message.
     *
     * Parameters expected:
     * - reg_id
     */
    public function confirmAction()
    {
        $reg_id = (string) $this->getScalarParam('reg_id');
        $user = null;

        $db = $this->_db->getAdapter();
        $db->beginTransaction();

        try {
            $user = $this->_signupManager->createUser($reg_id);
            $db->commit();

        } catch (ManipleUser_Signup_Exception_SignupNotFound $e) {
            $this->view->error = true;
            $db->rollBack();

        } catch (ManipleUser_Signup_Exception_UserAlreadyRegistered $e) {
            $this->view->already_registered = true;
            $this->view->error = true;
            $db->rollBack();
        }

        if (!$user) {
            return;
        }

        // Notify user about account activation
        $message = new Zefram_Mail;
        $message->setType(Zend_Mime::MULTIPART_RELATED);
        $message->setSubject($this->view->translate('Your account has been activated'));
        $message->addTo($user->getEmail());

        $this->view->assign(array(
            'message' => $message,
            'user' => $user,
            'name' => ManipleUser_Filter_FriendlyName::filterStatic($user),
            'site_url' => $this->view->serverUrl() . $this->view->baseUrl('/'),
            'url_forgot_password' => $this->view->serverUrl() . $this->view->url('user.password.forgot'),
        ));

        // TODO configurable template root dir
        $message->setBodyHtml($this->view->render($this->getLocalizedScriptPath('maniple-user/registration/mail/account-activated.twig')));
        $message->send();

        /*
        // notify staff uses about registration user_prefs
        // fetch all staff users
        $staff = $userRepository->getStaffUsers();
        if ($staff && !$auto_accept) {
            $message = new Zefram_Mail;
            $message->setType(Zend_Mime::MULTIPART_RELATED);
            $message->setSubject(sprintf('[%s] Nowe zgłoszenie rejestracyjne', $appName));
            foreach ($staff as $user) {
                $message->addTo($user->email);
            }
            $this->view->reg = $data;
            $this->view->url_verify = $this->view->serverUrl() . $this->view->url('registration.verify', array('reg_id' => $reg->reg_id));
            $this->view->message = $message;
            $message->setBodyHtml($this->view->render('registration/mail/new_registration.twig'));
            try {
                $message->send();
            } catch (Exception $e) {
                $this->getResource('log')->err($e->getMessage());
            }
        } */

        // prepare login form
        $form = new ManipleUser_Form_Login;
        $form->setAction($this->view->url('user.auth.login'));
        $form->setDefaults(array('username' => $user->getEmail()));

        $this->view->assign(array(
            'accepted' => true,
            'email' => $user->getEmail(),
            'form' => $form,
        ));
    }
}
