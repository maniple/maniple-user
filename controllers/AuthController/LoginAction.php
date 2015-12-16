<?php

/**
 * Class ModUser_AuthController_LoginAction
 *
 * @method string getContinueParam()
 * @method ModUser_Service_Security getSecurityContext()
 * @method ModUser_Model_UserManagerInterface getUserManager()
 */
class ModUser_AuthController_LoginAction
    extends Zefram_Controller_Action_StandaloneForm
{
    protected $_ajaxFormHtml = true;

    protected $_user;

    protected function _prepare() // {{{
    {
        if ($this->getSecurityContext()->isAuthenticated()) {
            $continue = $this->getContinueAfterLogin($this->getContinueParam());

            if ($this->_request->isXmlHttpRequest()) {
                $response = $this->_helper->ajaxResponse();
                $response->setMessage('You are already authenticated');
                $response->setData(array('redirect' => $continue));
                $response->sendAndExit();

            } else {
                $this->_redirect($continue);
                return;
            }
        }

        $this->_form = new ModUser_Form_Login();

        // boolean auth_required value is set only via Auth plugin
        $this->view->auth_required = true === $this->getParam('auth_required');
        $this->view->is_ajax = $this->_request->isXmlHttpRequest();
    } // }}}

    protected function _populate() // {{{
    {
        // form is not submitted, set continue value
        $continue = $this->getContinueParam();
        $this->_form->getElement('continue')->setValue($continue);

        // set cookie
        setcookie('cookie_check', 1, 0, $this->view->baseUrl('/'));
    } // }}}

    protected function _validate(array $data) // {{{
    {
        //if (empty($_COOKIE['cookie-check'])) {
        //    $this->view->message = 'Cookies are required to access this site. Please enable them and try again.';
        //    return false;
        //}

        if (false && !$this->_request->getCookie('cookie_check')) {
            $this->_form->getElement('password')->addError(
                'User authentication requires that cookies are enabled in your browser'
                // 'Logowanie wymaga włączonej obsługi cookies w przeglądarce'
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
            'Invalid username or password, or your account has not yet been activated'
            // 'Nieprawidłowa nazwa użytkownika lub hasło, albo konto nie zostało jeszcze aktywowane'
        );
        $this->_form->markAsError();
        return false;
    } // }}}

    protected function _process() // {{{
    {
        $user = $this->_user;

        // remove all password resets for user
        $this->getTableManager()->getTable('ModUser_Model_DbTable_PasswordResets')->delete(array(
            'user_id = ?' => (int) $user->getId(),
        ));

        $this->getSecurityContext()->getUserStorage()->setUser($user);

        $continue = $this->getContinueAfterLogin($this->_form->getElement('continue')->getValue());

        if ($this->_request->isXmlHttpRequest()) {
            $response = $this->_helper->ajaxResponse();
            $response->setData(array('redirect' => $continue));
            $response->sendAndExit();
            return;
        }

        $this->_helper->redirector->gotoUrl($continue);

        return false;
    } // }}}
}
