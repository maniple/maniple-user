<?php

/**
 * @property Zend_Controller_Request_Http $_request
 * @property ManipleUser_Form_Login $_form
 * @method string getContinueParam()
 * @method string getContinueAfterLogin(string $continue = null)
 */
class ManipleUser_AuthController_LoginAction
    extends Maniple_Controller_Action_StandaloneForm
{
    protected $_actionControllerClass = ManipleUser_AuthController::className;

    protected $_ajaxFormHtml = true;

    /**
     * @Inject
     * @var Zefram_Db
     */
    protected $_db;

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
     * @var ManipleUser_Service_Password
     */
    protected $_passwordService;

    /**
     * @var ManipleUser_Model_UserInterface
     */
    protected $_user;

    protected function _prepare()
    {
        if ($this->_securityContext->isAuthenticated()) {
            $continue = $this->getContinueAfterLogin($this->getContinueParam());

            if ($this->_request->isXmlHttpRequest()) {
                $response = $this->_helper->ajaxResponse();
                $response->setMessage($this->view->translate('You are already authenticated'));
                $response->setData(array('redirect' => $continue));
                $response->sendAndExit();

            } else {
                $this->redirect($continue);
                return;
            }
        }

        $this->_form = new ManipleUser_Form_Login();

        // boolean auth_required value is set only via Auth plugin
        $this->view->auth_required = true === $this->getParam('auth_required');
        $this->view->is_ajax = $this->_request->isXmlHttpRequest();
    }

    protected function _populate()
    {
        // form is not submitted, set continue value
        $continue = $this->getContinueParam();
        $this->_form->getElement('continue')->setValue($continue);

        // set cookie
        setcookie('cookie_check', 1, 0, $this->view->baseUrl('/'));
    }

    protected function _validate(array $data)
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

        $user = $this->_userRepository->getUserByUsernameOrEmail($username);

        if ($user && $user->isActive() && $this->_passwordService->verify($password, $user)) {
            $this->_user = $user;
            return true;
        }

        $this->_form->getElement('password')->addError(
            $this->view->translate('Invalid username or password, or your account has not yet been activated')
        );
        $this->_form->markAsError();
        return false;
    }

    protected function _process()
    {
        $user = $this->_user;

        // remove all password resets for user
        $this->_db->getTable(ManipleUser_Model_DbTable_PasswordResets::className)->delete(array(
            'user_id = ?' => (int) $user->getId(),
        ));

        $this->_securityContext->getUserStorage()->setUser($user);

        $continue = $this->getContinueAfterLogin($this->_form->getElement('continue')->getValue());

        if ($this->_request->isXmlHttpRequest()) {
            $response = $this->_helper->ajaxResponse();
            $response->setData(array('redirect' => $continue));
            $response->sendAndExit();
            return;
        }

        $this->_helper->redirector->gotoUrl($continue);

        return false;
    }
}
