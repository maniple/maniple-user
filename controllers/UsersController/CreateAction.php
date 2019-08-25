<?php

/**
 * @property Zend_Controller_Request_Http $_request
 * @property ManipleUser_Form_User $_form
 * @method void requireAuthentication();
 */
class ManipleUser_UsersController_CreateAction
    extends Maniple_Controller_Action_StandaloneForm
{
    /**
     * @Inject
     * @var Zefram_Db
     */
    protected $_db;

    /**
     * @Inject
     * @var ManipleUser_Form_Factory_User
     */
    protected $_userFormFactory;

    /**
     * @Inject('user.model.userMapper')
     * @var ManipleUser_Model_UserMapperInterface
     */
    protected $_userRepository;

    /**
     * @Inject
     * @var ManipleUser_PasswordService
     */
    protected $_passwordService;

    /**
     * @Inject('user.sessionManager')
     * @var Maniple_Security_ContextInterface
     */
    protected $_securityContext;

    protected function _prepare()
    {
        $this->requireAuthentication();
        if (!$this->_securityContext->isAllowed('manage_users')) {
            throw new Maniple_Controller_Exception_NotAllowed();
        }

        $this->_form = $this->_userFormFactory->createForm();
    }

    protected function _process()
    {
        $password = $this->_passwordService->generatePassword();

        $user = $this->_form->populateUser(new ManipleUser_Entity_User());
        $user->setActive(true);
        $user->setPassword($this->_passwordService->temporaryPasswordHash($password));
        $user->setCreatedAt(time());

        $this->_db->beginTransaction();
        try {
            /** @var ManipleUser_Model_UserInterface $user */
            $user = $this->_userRepository->saveUser($user);
            $roleId = $this->_form->getValue('role_id');

            if ($roleId) {
                $this->_db->getTable(ManipleUser_Model_DbTable_UserRoles::className)->createRow(array(
                    'user_id' => $user->getId(),
                    'role_id' => $roleId,
                ))->save();
            }

            $message = new Zefram_Mail;
            $message->setType(Zend_Mime::MULTIPART_RELATED);
            $message->setSubject(sprintf(
                $this->view->translate('Welcome to %s'),
                preg_replace('%https?://%', '', $this->view->serverUrl($this->view->baseUrl()))
            ));
            $message->addTo($user->getEmail());

            $this->view->assign(array(
                'user'     => $user,
                'message'  => $message,
                'password' => $password,
            ));

            $message->setBodyHtml($this->view->render('maniple-user/users/create-mail.twig'));
            $message->send();

            $this->_db->commit();

        } catch (Exception $e) {
            $this->_db->rollBack();
            throw $e;
        }

        $this->_helper->flashMessenger->addSuccessMessage($this->view->translate('User account has been successfully created'));

        $returnUrl = $this->getSingleParam('return_url');
        return $returnUrl ? $returnUrl : $this->view->url('maniple-user.users.index');
    }
}
