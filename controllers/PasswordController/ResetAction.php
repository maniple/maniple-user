<?php

class ModUser_PasswordController_ResetAction
    extends Zefram_Controller_Action_StandaloneForm
{
    protected $_ajaxViewScript = '_forms/password.twig';

    protected $_ajaxFormHtml = true;

    protected $_reset;

    protected $_user;

    protected function _prepare()
    {
        $security = $this->getSecurity();

        if ($security->isAuthenticated()) {
            $this->_helper->flashMessenger->addMessage(
                $this->view->translate(
                    'You cannot request resetting your password while being a logged in user.'
                ),
                'error'
            );
            return $this->_helper->redirector->gotoUrlAndExit('/');
        }

        $reset = $this->getResource('tableManager')->getTable('ModUser_Model_DbTable_PasswordResets')->fetchRow(array('reset_id = ?' => (string) $this->getScalarParam('reset_id')));

        if (empty($reset) || ($reset->expires_at !== null && $reset->expires_at < time())) {
            throw new Exception('Niepoprawny token resetu hasła');
                // 'Invalid password reset token');
        }

        $user = $this->getUserManager()->getUser($reset->user_id);
        if (empty($user)) {
            throw new Exception('Corrupted registration token');
        }

        $form = new ModUser_Form_PasswordReset($user);
        $form->setAction(
            $this->view->url('user.password.reset', array('reset_id' => $reset->reset_id))
        );

        $this->_form = $form;
        $this->_reset = $reset;

        $this->getSessionNamespace('reset')->unsetAll();
    }

    protected function _process()
    {
        $password = password_hash($this->_form->getValue('password'), PASSWORD_BCRYPT);

        $user = $this->_form->getUser();
        $user->setPassword($password);

        $this->getUserManager()->saveUser($user);

        $this->getTableManager()->getTable('ModUser_Model_DbTable_PasswordResets')->delete(array(
            'user_id = ?' => (int) $user->getId(),
        ));

        $sessionNamespace = $this->getSessionNamespace('reset');
        $sessionNamespace->complete = true;

        $appName = 'app';

        $message = new Zefram_Mail;
        $message->setType(Zend_Mime::MULTIPART_RELATED);
        $message->setSubject(sprintf('[%s] Powiadomienie o zmianie hasła', $appName));
        $message->addTo($user->getEmail());

        $this->view->user = $user;
        $this->view->message = $message;

        $message->setBodyHtml($this->view->render('mail/password-reset.twig'));
        $message->send();

        return $this->view->url('user.password.reset_complete');
    }
}
