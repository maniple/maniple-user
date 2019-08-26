<?php

/**
 * @property ManipleUser_Form_Password $_form
 */
class ManipleUser_PasswordController_UpdateAction
    extends Maniple_Controller_Action_StandaloneForm
{
    protected $_actionControllerClass = ManipleUser_PasswordController::className;

    protected $_ajaxFormHtml = true;

    /**
     * @Inject('user.sessionManager')
     * @var Maniple_Security_ContextInterface
     */
    protected $_securityContext;

    /**
     * @Inject
     * @var ManipleUser_Service_UserManager
     */
    protected $_userManager;

    /**
     * @Inject
     * @var ManipleUser_Service_Password
     */
    protected $_passwordService;

    /**
     * @return void
     * @throws Maniple_Controller_Exception
     */
    protected function _prepare()
    {
        if (!$this->_securityContext->isAuthenticated()) {
            throw new Maniple_Controller_Exception_AuthenticationRequired($this->_request);
        }

        $user = $this->_userManager->getUser(
            $this->_securityContext->getUser()->getId()
        );

        if (empty($user)) {
            // unlikely to happen if user is authenticated
            throw new Maniple_Controller_Exception_NotFound('User was not found');
        }

        $this->_form = new ManipleUser_Form_Password($this->_passwordService, $user);
    }

    protected function _process()
    {
        $userManager = $this->_userManager;
        $password = $this->_passwordService->passwordHash($this->_form->getValue('password'));

        $user = $this->_form->getUser();
        $user->setPassword($password);

        $userManager->saveUser($user);

        if ($this->isAjax()) {
            $this->view->success = $this->view->translate('Your password has been changed');
            $this->_helper->json(array(
                'data' => array(
                    'html' => $this->renderForm()
                ),
            ));
            return false;
        }

        $this->_helper->flashMessenger->addSuccessMessage($this->view->translate('Your password has been changed'));

        $config = $this->getResource('modules')->offsetGet('maniple-user')->getOptions();
        $continue = isset($config['afterLoginRoute'])
            ? $this->view->url($config['afterLoginRoute'])
            : $this->view->baseUrl('/');

        return $continue;
    }
}
