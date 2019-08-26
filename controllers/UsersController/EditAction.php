<?php

/**
 * @property Zend_Controller_Request_Http $_request
 * @property ManipleUser_Form_User $_form
 * @method void requireAuthentication();
 */
class ManipleUser_UsersController_EditAction
    extends Maniple_Controller_Action_StandaloneForm
{
    protected $_actionControllerClass = ManipleUser_UsersController::className;

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
     * @Inject
     * @var ManipleUser_Service_UserManagerInterface
     */
    protected $_userManager;

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

        /** @var ManipleUser_Model_UserInterface $user */
        $user = $this->_userManager->getUser((int) $this->getSingleParam('user_id'));
        if (empty($user)) {
            throw new Maniple_Controller_Exception_NotFound('User not found');
        }

        // Non-superuser cannot edit superuser
        if ($this->_securityContext->isSuperUser($user->getId())
            && !$this->_securityContext->isSuperUser()
        ) {
            throw new Maniple_Controller_Exception_NotAllowed();
        }

        $this->_form = $this->_userFormFactory->createForm(array('user' => $user));
    }

    protected function _process()
    {
        $user = $this->_form->populateUser($this->_form->getUser());

        $this->_db->beginTransaction();
        try {
            /** @var ManipleUser_Model_UserInterface $user */
            $user = $this->_userManager->saveUser($user);
            $roleIds = $this->_form->getValue('role_id');

            /** @var ManipleUser_Model_DbTable_UserRoles $userRolesTable */
            $userRolesTable = $this->_db->getTable(ManipleUser_Model_DbTable_UserRoles::className);
            $userRolesTable->delete(array(
                'user_id = ?' => (int) $user->getId(),
            ));

            // role_id is null if no option is selected
            if (is_array($roleIds)) {
                foreach ($roleIds as $roleId) {
                    $userRolesTable->createRow(array(
                        'user_id' => $user->getId(),
                        'role_id' => $roleId,
                    ))->save();
                }
            }

            $this->_db->commit();

        } catch (Exception $e) {
            $this->_db->rollBack();
            throw $e;
        }

        $this->_helper->flashMessenger->addSuccessMessage($this->view->translate('User account has been successfully updated'));

        $returnUrl = $this->getSingleParam('return_url');
        return $returnUrl ? $returnUrl : $this->view->url('maniple-user.users.index');
    }
}
