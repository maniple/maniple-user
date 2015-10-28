<?php

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ModUser_Service_UserMapperFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $db = $serviceLocator->get('ZeframDb');
        $userMapper = new ModUser_Model_UserMapper($db);
        return $userMapper;
    }
}