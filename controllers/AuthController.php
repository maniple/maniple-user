<?php

class ModUser_AuthController extends ModUser_Controller_Action
{
    public function getContinueParam() // {{{
    {
        $continue = trim($this->getScalarParam('continue'));
        $continue = str_replace('://', '', $continue);
        $continue = '/' . ltrim($continue, '/');

        if ('/' === $continue) {
            return false;
        }

        // baseUrl view helper contains the necessary logic to retrieve
        // correct value
        $baseUrl = rtrim($this->view->baseUrl('/'), '/');

        if (0 === strncmp($continue, $baseUrl, strlen($baseUrl))) {
            // strip baseUrl from the beginning of continue param
            $continue = substr($continue, strlen($baseUrl));
        }

        return $continue;
    } // }}}

    public function indexAction()
    {
        $this->forward('login');
    }

    public function logoutAction() // {{{
    {
        $security = $this->getSecurity();

        if ($security->isAuthenticated()) {
            $user = $security->getUser();
            $returnUrl = $security->getUserStorage()->get('returnUrl');

            $security->getUserStorage()->clearUser();
        }

        $this->redirect(empty($returnUrl) ? '/' : $returnUrl);
    } // }}}

    public function impersonateAction() // {{{
    {
        $user_id = $this->getScalarParam('user_id', 0);
        $user = $this->getUserManager()->getUser($user_id);
        if (empty($user)) {
            throw new Exception('Niepoprawny ID użytkownika');
        }

        // TODO ensure that you cannot impersonate yourself
        $security = $this->getSecurity();
        $security->getUserStorage()->impersonate($user);

        $referer = $this->_request->getHeader('referer');
        $prefix = $this->view->serverUrl() . rtrim($this->_request->getBaseUrl(), '/') . '/';
        if (!strncmp($referer, $prefix, strlen($prefix))) {
            $returnUrl = $referer;
        } else {
            $returnUrl = '/';
        }

        $security->getUserStorage()->set('returnUrl', $returnUrl);

        // TODO get homeRoute from config
        // module->config->homeRoute
        // $this->_redirect('/');
        $this->_helper->redirector->gotoRoute('main.workshops.my_meetings');
    } // }}}

    /**
     * @version 2013-07-02
     */
    public function passwordResetCompleteAction() // {{{
    {
        $sessionNamespace = new Zend_Session_Namespace('password-reset');
        $this->view->complete = $sessionNamespace->complete;
        $sessionNamespace->unsetAll();
    } // }}}

    /**
     * Notification after successful sending of an email with password reset
     * instructions.
     *
     * @version 2013-07-02
     */
    public function forgotPasswordCompleteAction() // {{{
    {
        $sessionNamespace = new Zend_Session_Namespace('forgot-password');
        $this->view->complete = $sessionNamespace->complete;
        $sessionNamespace->unsetAll();
    } // }}}
}
