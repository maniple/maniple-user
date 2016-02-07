<?php

namespace Maniple\ModUser\Entity;

class User implements UserInterface
{
    /**
     * @var mixed
     */
    protected $_id;

    /**
     * @var string
     */
    protected $_username;

    /**
     * @var string
     */
    protected $_password;

    /**
     * @var string
     */
    protected $_email;

    /**
     * @var bool
     */
    protected $_isActive;

    /**
     * @var DateTime
     */
    protected $_createdAt;

    /**
     * @var string
     */
    protected $_firstName;

    /**
     * @var string
     */
    protected $_lastName;

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

    public function getFirstName()
    {
        return $this->_firstName;
    }

    public function setFirstName($firstName)
    {
        $this->_firstName = $firstName;
        return $this;
    }

    public function getLastName()
    {
        return $this->_lastName;
    }

    public function setLastName($lastName)
    {
        $this->_lastName = $lastName;
        return $this;
    }
}
