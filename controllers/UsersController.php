<?php

/**
 * @property Zend_Controller_Request_Http $_request
 * @property Zend_View_Abstract|Zefram_View_Interface $view
 */
class ManipleUser_UsersController extends Maniple_Controller_Action
{
    const className = __CLASS__;

    /**
     * @Inject
     * @var ManipleUser_UsersService
     */
    protected $_usersService;

    /**
     * @Inject('user.sessionManager')
     * @var Maniple_Security_ContextInterface
     */
    protected $_securityContext;

    public function indexAction()
    {
        $this->requireAuthentication();
        if (!$this->_securityContext->isAllowed('manage_users')) {
            throw new Maniple_Controller_Exception_NotAllowed();
        }

        $users = $this->_usersService->getUsers(array(
            'query'     => $this->getSingleParam('query'),
            'active'    => true,
            'withRoles' => true,
        ));
        $users->setCurrentPageNumber($this->getSingleParam('page', 1));
        $users->setItemCountPerPage($this->getSingleParam('page_size', 25));

        $this->view->assign(array(
            'users' => $users,
            'returnUrl' => $this->_request->getRequestUri(),
        ));
    }
}
