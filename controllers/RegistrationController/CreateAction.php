<?php

/**
 * @property Zend_Controller_Request_Http $_request
 * @method Zend_Session_Namespace getSessionNamespace()
 */
class ManipleUser_RegistrationController_CreateAction extends Maniple_Controller_Action_StandaloneForm
{
    protected $_actionControllerClass = ManipleUser_RegistrationController::className;

    /**
     * @Inject('user.sessionManager')
     * @var Maniple_Security_ContextInterface
     */
    protected $_securityContext;

    /**
     * @Inject('user.model.userMapper')
     * @var ManipleUser_Model_UserMapperInterface
     */
    protected $_userRepository;

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

    protected function _prepare()
    {
        if ($this->_securityContext->isAuthenticated()) {
            // Redirect to login page for proper after-login redirection
            $queryString = ltrim($this->_request->getServer('QUERY_STRING'), '?');
            if (strlen($queryString)) {
                $queryString  = '?' . $queryString;
            }
            $this->_helper->redirector->gotoUrl($this->view->url('user.auth.login') . $queryString);
            return;
        }

        $config = $this->getResource('config');
        if (!is_array($config)) {
            $config = $config->toArray();
        }
        $registrationClosed = @$config['mod_user']['registration']['closed'];
        if ($registrationClosed) {
            $this->_helper->flashMessenger->addErrorMessage('Rejestracja nowych użytkowników jest zamknięta');
            $this->_helper->redirector->gotoRoute('user.auth.login');
            return;
        }

        $this->getSessionNamespace()->unsetAll();

        $this->_form = $this->_signupManager->createSignupForm();
        $this->_form->setView($this->view);

        $this->view->form_template = 'maniple-user/forms/registration';
    }

    protected function _process()
    {
        $data = $this->_form->getValues();
        $reg = $this->_signupManager->createSignupRecord($data, $this->_request->getClientIp());

        // close connection and send e-mail semi-asynchronously

        $vars = array(
            'complete'   => true,
            'expires_at' => $reg->expires_at,
            'email'      => $reg->email,
        );

        /*
        if ($this->isAjax()) {
            $this->view->assign($vars);

            $response = Zefram_Json::encode(array(
                'status' => 'success',
                'data' => $this->view->render('registration/complete.twig'),
            ));

            header('Content-Type: application/json');
            header('Connection: close');
            header('Content-Length: ' . strlen($response));

            echo $response;

        } else {
            $sessionNamespace = $this->getSessionNamespace();
            $sessionNamespace->setExpirationHops(1, null, true);

            foreach ($vars as $key => $value) {
                $sessionNamespace->{$key} = $value;
            }

            $url = $this->view->url('user.registration.complete');
            header('Location: ' . $url);
            header('Connection: close');
            header('Content-Length: 0');
        }

        while (ob_end_flush());
        flush();

        session_write_close();
         */

        $message = new Zefram_Mail;
        $message->setType(Zend_Mime::MULTIPART_RELATED);
        $message->setSubject($this->view->translate('Confirm your email address'));
        $message->addTo($reg->email);

        $name = isset($data['username']) ? $data['username'] : $data['email'];
        if ($name === $data['email']) {
            $name = substr($name, 0, strpos($name, '@'));
        }

        $this->view->assign($data);
        $this->view->url_confirm = $this->view->serverUrl() . $this->view->url('user.registration.confirm', array('reg_id' => $reg->reg_id));
        $this->view->name = $name;
        $this->view->message = $message;

        $message->setBodyHtml($this->view->render('maniple-user/registration/mail/confirm.twig'));

        try {
            $message->send();
        } catch (Exception $e) {
            echo '<div class="alert alert-danger">', $this->view->translate('Sending message failed'), '</div>';
            return false;
        }

        if ($this->_request->isXmlHttpRequest()) {
            $this->view->assign($vars);

            $response = Zefram_Json::encode(array(
                'status' => 'success',
                'data' => $this->view->render('maniple-user/registration/complete.twig'),
            ));

            $this->_helper->json($response);

        } else {
            $sessionNamespace = $this->getSessionNamespace();
            $sessionNamespace->setExpirationHops(1, null, true);

            foreach ($vars as $key => $value) {
                $sessionNamespace->{$key} = $value;
            }

            $url = $this->view->url('user.registration.complete');
            $this->_helper->redirector->gotoUrl($url);
        }

        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout(true);
        return false;
    }
}
