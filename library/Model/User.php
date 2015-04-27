<?php

class ModUser_Model_User implements ModUser_Model_UserInterface
{
    protected $_id;

    protected $_username;

    protected $_password;

    protected $_email;

    protected $_isActive;

    protected $_createdAt;

    public function getId()
    {
        return $this->_id;
    }

    public function setId($id)
    {
        $this->_id = $id;
        return $this;
    }

    public function getUsername()
    {
        return $this->_username;
    }

    public function setUsername($username)
    {
        $this->_username = $username;
        return $this;
    }

    public function getPassword()
    {
        return $this->_password;
    }

    public function setPassword($password)
    {
        $this->_password = $password;
        return $this;
    }

    public function getEmail()
    {
        return $this->_email;
    }

    public function setEmail($email)
    {
        $this->_email = $email;
        return $this;
    }

    public function getIsActive()
    {
        return $this->_isActive;
    }

    public function setIsActive($isActive)
    {
        $this->_isActive = (bool) $isActive;
        return $this;
    }

    public function getCreatedAt()
    {
        return $this->_createdAt;
    }

    public function setCreatedAt($createdAt)
    {
        $this->_createdAt = $createdAt;
        return $this;
    }
}
