<?php

class ModUser_AuthController_LoginAction
    extends Zefram_Controller_Action_StandaloneForm
{
    protected $_ajaxFormHtml = true;

    protected $_user;

    protected function _prepare() // {{{
    {
        if ($this->getSecurityContext()->isAuthenticated()) {
            $continue = $this->getContinueParam();
            if ($this->_request->isXmlHttpRequest()) {
                $response = $this->_helper->ajaxResponse();
                $response->setMessage('You are already authenticated');
                $response->setData(array(
                    'redirect' => $continue ? $continue : $this->view->baseUrl('/'),
                ));
                $response->sendAndExit();

            } else {
                $this->_redirect($continue ? $continue : '/');
                return;
            }
        }

        $this->_form = new ModUser_Form_Login();

        // boolean auth_required value is set only via Auth plugin
        $this->view->auth_required = true === $this->getParam('auth_required');
        $this->view->is_ajax = $this->_request->isXmlHttpRequest();
        $this->view->title = 'Logowanie';
    } // }}}

    protected function _populate() // {{{
    {
        // form is not submitted, set continue value
        $continue = $this->getContinueParam();
        $this->_form->continue->setValue($continue);

        // set cookie
        setcookie('cookie_check', 1, 0, $this->view->baseUrl('/'));
    } // }}}

    protected function _validate(array $data) // {{{
    {
        //if (empty($_COOKIE['cookie-check'])) {
        //    $this->view->message = 'Cookies are required to access this site. Please enable them and try again.';
        //    return false;
        //}

        if (!$this->_request->getCookie('cookie_check')) {
            $this->_form->getElement('password')->addError(
                'Logowanie wymaga włączonej obsługi cookies w przeglądarce'
            );
            $this->_form->markAsError();
            return false;
        }

        if (!parent::_validate($data)) {
            return false;
        }

        $username = $this->_form->getValue('username');
        $password = $this->_form->getValue('password');

        $userRepository = $this->getUserManager();
        $user = $userRepository->getUserByUsernameOrEmail($username);

        if ($user && password_verify($password, $user->getPassword())) {
            $this->_user = $user;
            return true;
        }

        $this->_form->getElement('password')->addError(
            'Nieprawidłowa nazwa użytkownika lub hasło, albo konto nie zostało jeszcze aktywowane'
        );
        $this->_form->markAsError();
        return false;
    } // }}}

    protected function _process() // {{{
    {
        $user = $this->_user;

        // remove all password resets for user
        $this->getResource('tableManager')->getTable('ModUser_Model_DbTable_PasswordResets')->delete(array(
            'user_id = ?' => (int) $user->getId(),
        ));

        $this->getSecurityContext()->getUserStorage()->setUser($user);

        $continue = $this->_form->continue->getValue();

        if ($this->_request->isXmlHttpRequest()) {
            $response = $this->_helper->ajaxResponse();
            $response->setData(array(
                'redirect' => $continue ? $continue : '/',
            ));
            $response->sendAndExit();
            return;
        }

        if ($continue) {
            $this->_helper->redirector->gotoUrl($continue);
        } else {
            $this->_helper->redirector->gotoUrl('/');
        }

        return false;
    } // }}}
}
