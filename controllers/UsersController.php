<?php

/**
 * @property Zend_Controller_Request_Http $_request
 * @property Zend_View_Abstract|Zefram_View_Abstract $view
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
            throw new Maniple_Controller_Exception_Forbidden();
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

    public function requireAuthentication()
    {
        if (!$this->_securityContext->isAuthenticated()) {
            throw new Maniple_Controller_Exception_AuthenticationRequired($this->_request);
        }
    }

    /**
     * Data provider for user search widget.
     */
    public function searchAction()
    {
        $this->requireAuthentication();
        if (!$this->_securityContext->isAllowed('manage_users')) {
            throw new Maniple_Controller_Exception_Forbidden();
        }

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $query = trim($this->getSingleParam('query'));
        $users = $this->_usersService->getUsers(array('query' => $query));
        $items = array(
            'items' => (array) $users->getCurrentItems(),
            'total' => $users->getTotalItemCount(),
            'page' => $users->getCurrentPageNumber(),
            'pageSize' => $users->getItemCountPerPage(),
        );

        $response = $this->getResponse();
        $response->setHeader('Content-Type', 'application/json');
        $response->setBody(Zefram_Json::encode($items));
    }
}
