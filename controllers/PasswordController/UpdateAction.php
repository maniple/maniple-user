<?php

class ManipleUser_PasswordController_UpdateAction
    extends Maniple_Controller_Action_StandaloneForm
{
    protected $_ajaxFormHtml = true;

    /**
     * @return ManipleUser_Model_UserManager
     */
    public function getUserManager()
    {
        /** @var ManipleUser_Model_UserManager $userManager */
        $userManager = $this->getResource('user.userManager');
        return $userManager;
    }

    protected function _prepare()
    {
        if (!$this->getSecurity()->isAuthenticated()) {
            throw new Exception('Musisz być zalogowany aby zmienić hasło');
        }

        $user = $this->getUserManager()->getUser(
            $this->getSecurity()->getUser()->getId()
        );

        if (empty($user)) {
            // unlikely to happen if user is authenticated
            throw new Exception('User was not found');
        }

        $this->_form = new ManipleUser_Form_Password($user);
    }

    protected function _process()
    {
        $userManager = $this->getUserManager();
        $password = $userManager->getPasswordHash($this->_form->getValue('password'));

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
    }
}
