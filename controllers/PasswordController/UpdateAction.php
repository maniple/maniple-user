<?php

class ModUser_PasswordController_UpdateAction
    extends Zefram_Controller_Action_StandaloneForm
{
    protected $_ajaxFormHtml = true;

    protected function _prepare()
    {
        if (!$this->getSecurity()->isAuthenticated()) {
            throw new Exception('Musisz być zalogowany aby zmienić hasło');
        }

        $user = $this->getResource('user.user_manager')->getUser(
            $this->getSecurity()->getUser()->getId()
        );

        if (empty($user)) {
            throw new Exception('User was not found'); // unlikely to happen
        }

        $this->_form = new ModUser_Form_Password($user);
    }

    protected function _process()
    {
        $password = password_hash($this->_form->getValue('password'), PASSWORD_BCRYPT);
        $user = $this->_form->getUser();
        $user->setPassword($password);

        $this->getResource('user.user_manager')->saveUser($user);

        if ($this->isAjax()) {
            $this->view->success = 'Hasło zostało zmienione';
            $this->_helper->json(array(
                'data' => array(
                    'html' => $this->renderForm()
                ),
            ));
            return false;
        }

        $this->_helper->flashMessenger->addSuccessMessage('Hasło zostało zmienione');
    }
}
