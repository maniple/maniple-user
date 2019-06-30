<?php

/**
 * @property Zend_Controller_Request_Http $_request
 * @method Zend_Session_Namespace getSessionNamespace()
 */
class ManipleUser_RegistrationController_CreateAction extends Maniple_Controller_Action_StandaloneForm
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
        if ($this->getSecurityContext()->isAuthenticated()) {
            $this->_helper->redirector->gotoUrl('/');
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

        $formClass = @$config['mod_user']['registration']['formClass'];
        if (!$formClass) {
            $formClass = ManipleUser_Form_Registration::className;
        }
        $this->_form = new $formClass($this->_userRepository, array('view' => $this->view));

        $this->view->form_template = 'maniple-user/forms/registration';
    }

    protected function _process()
    {
        $data = $this->_form->getValues();

        // make sure email is lowercased
        $tolower = new Zend_Filter_StringToLower();
        $data['email'] = $tolower->filter($data['email']);

        if (isset($data['username'])) {
            $data['username'] = $tolower->filter($data['username']);
        }

        // hash password
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);

        $reg = $this->_db->getTable(ManipleUser_Model_DbTable_Registrations::className)->createRow(array(
            'reg_id'     => Zefram_Math_Rand::getString(64, Zefram_Math_Rand::BASE64URL),
            'created_at' => time(),
            'expires_at' => null, // TODO registration.lifetime setting
            'ip_addr'    => $this->_request->getServer('REMOTE_ADDR'),
            'email'      => $data['email'],
            'data'       => Zefram_Json::encode($data, array('unescapedSlashes' => true, 'unescapedUnicode' => true)),
            'status'     => 'PENDING',
        ));
        $reg->save();

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
