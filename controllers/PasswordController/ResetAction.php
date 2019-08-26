<?php

/**
 * @property ManipleUser_Form_PasswordReset $_form
 * @method Zend_Session_Namespace getSessionNamespace(string $name)
 */
class ManipleUser_PasswordController_ResetAction
    extends Maniple_Controller_Action_StandaloneForm
{
    protected $_actionControllerClass = ManipleUser_PasswordController::className;

    protected $_ajaxViewScript = '_forms/password.twig';

    protected $_ajaxFormHtml = true;

    /**
     * @var Zefram_Db_Table_Row
     */
    protected $_reset;

    /**
     * @var ManipleUser_Model_UserInterface
     */
    protected $_user;

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
     * @Inject
     * @var Zefram_Db
     */
    protected $_db;

    protected function _prepare()
    {
        if ($this->_securityContext->isAuthenticated()) {
            $this->_helper->flashMessenger->addErrorMessage(
                $this->view->translate(
                    'You cannot request resetting your password while being a logged in user.'
                )
            );
            $this->_helper->redirector->gotoUrlAndExit('/');
            return;
        }

        $reset = $this->_db->getTable(ManipleUser_Model_DbTable_PasswordResets::className)->fetchRow(array('reset_id = ?' => (string) $this->getScalarParam('reset_id')));

        if (empty($reset) || ($reset->expires_at !== null && $reset->expires_at < time())) {
            throw new Exception($this->view->translate('Invalid password reset token'));
        }

        $user = $this->_userManager->getUser($reset->user_id);
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
        $password = $this->_passwordService->passwordHash($this->_form->getValue('password'));

        $user = $this->_form->getUser();
        $user->setPassword($password);

        $this->_userManager->saveUser($user);

        $this->_db->getTable(ManipleUser_Model_DbTable_PasswordResets::className)->delete(array(
            'user_id = ?' => (int) $user->getId(),
        ));

        $sessionNamespace = $this->getSessionNamespace('reset');
        $sessionNamespace->complete = true;

        header('Location: ' . $this->view->url('user.password.reset_complete'));
        header('Connection: close');
        header('Content-Length: 0');

        while (@ob_end_clean());
        flush();

        session_write_close();

        $message = new Zefram_Mail;
        $message->setType(Zend_Mime::MULTIPART_RELATED);
        $message->setSubject($this->view->translate('Your password has been changed'));
        $message->addTo($user->getEmail(), $user->getFirstName() . ' ' . $user->getLastName());

        $this->view->user = $user;
        $this->view->message = $message;

        $message->setBodyHtml($this->view->render('maniple-user/password/password-reset-mail.twig'));
        $message->send();

        exit;
        return $this->view->url('user.password.reset_complete');
    }
}
