<?php

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ModUser_Service_UserManagerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $userMapper = $serviceLocator->get('user.model.userMapper');

        $userManager = new ModUser_Model_UserManager();
        $userManager->setUserMapper($userMapper);

        return $userManager;
    }
}