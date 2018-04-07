<?php

class ModUser_Plugin_LogExtras extends Zend_Controller_Plugin_Abstract
{
    /**
     * @var Zefram_Log
     */
    protected $_log;

    protected $_frontController;

    public function __construct(Zefram_Log $log, Zend_Controller_Front $frontController)
    {
        $this->_log = $log;
        $this->_frontController = $frontController;

        $log->setExtras(array(
            'ip'       => '0.0.0.0',
            'uid'      => '-',
            'username' => '-',
        ));
    }

    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
    {
        $this->_log->setEventItem('ip', $request->getClientIp());

        if ($request instanceof Zend_Controller_Request_Http) {
            /** @var Zend_Controller_Request_Http $requestUri */
            $requestUri = $request->getRequestUri();

            if (strpos($requestUri, '?') !== false) {
                list($path, $query) = explode('?', $requestUri, 2);
            } else {
                $path = $requestUri;
                $query = '';
            }

            $host = $request->getHttpHost();
            $this->_log->setExtras(compact('host', 'path', 'query'));
        }

        $bootstrap = $this->_frontController->getParam('bootstrap');

        /** @var ModUser_Service_Security $security */
        $security = $bootstrap->getResource('user.sessionManager');

        if ($security->isAuthenticated()) {
            $user     = $security->getUser();
            $uid      = $user->getId();
            $username = method_exists($user, 'getUsername') ? $user->getUsername() : '-';
        } else {
            $uid      = '-';
            $username = '-';
        }

        $this->_log->setExtras(compact('uid', 'username'));
    }
}
