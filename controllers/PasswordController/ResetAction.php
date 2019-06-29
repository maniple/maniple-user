<?php

/**
 * @property ManipleUser_Form_PasswordReset $_form
 */
class ManipleUser_PasswordController_ResetAction
    extends Maniple_Controller_Action_StandaloneForm
{
    protected $_ajaxViewScript = '_forms/password.twig';

    protected $_ajaxFormHtml = true;

    protected $_reset;

    protected $_user;

    /**
     * @Inject('Zefram_Db')
     * @var Zefram_Db
     */
    protected $_db;

    protected function _prepare()
    {
        $security = $this->getSecurity();

        if ($security->isAuthenticated()) {
            $this->_helper->flashMessenger->addErrorMessage(
                $this->view->translate(
                    'You cannot request resetting your password while being a logged in user.'
                )
            );
            return $this->_helper->redirector->gotoUrlAndExit('/');
        }

        $reset = $this->_db->getTable(ManipleUser_Model_DbTable_PasswordResets::className)->fetchRow(array('reset_id = ?' => (string) $this->getScalarParam('reset_id')));

        if (empty($reset) || ($reset->expires_at !== null && $reset->expires_at < time())) {
            throw new Exception($this->view->translate('Invalid password reset token'));
        }

        $user = $this->getUserManager()->getUser($reset->user_id);
        if (empty($user)) {
            throw new Exception($this->view->translate('Corrupted password reset token'));
        }

        $form = new ManipleUser_Form_PasswordReset($user);
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

        $this->_db->getTable(ManipleUser_Model_DbTable_PasswordResets::className)->delete(array(
            'user_id = ?' => (int) $user->getId(),
        ));

        $sessionNamespace = $this->getSessionNamespace('reset');
        $sessionNamespace->complete = true;

        $message = new Zefram_Mail;
        $message->setType(Zend_Mime::MULTIPART_RELATED);
        $message->setSubject($this->view->translate('Your password has been changed'));
        $message->addTo($user->getEmail());

        $this->view->user = $user;
        $this->view->message = $message;

        $message->setBodyHtml($this->view->render('mod-user/password/password-reset-mail.twig'));
        $message->send();

        return $this->view->url('user.password.reset_complete');
    }
}
