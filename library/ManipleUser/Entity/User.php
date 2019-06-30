<?php

/**
 * @Entity(repositoryClass="ManipleUser_Repository_UserRepository")
 * @Table(name="users")
 */
class ManipleUser_Entity_User implements ManipleUser_Entity_UserInterface
{
    const className = __CLASS__;

    /**
     * @Id
     * @Column(name="user_id", type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $_id;

    /**
     * @Column(name="is_active", type="boolint", nullable=false)
     */
    protected $_active = true;

    /**
     * @Column(name="created_at", type="epoch")
     */
    protected $_createdAt;

    /**
     * @Column(name="username", type="string", length=255, unique=true)
     */
    protected $_username;

    /**
     * @Column(name="email", type="string", length=255, unique=true)
     */
    protected $_email;

    /**
     * @Column(name="password", type="string", length=255)
     */
    protected $_password;

    /**
     * @Column(name="first_name", type="string", length=255, nullable=true)
     */
    protected $_firstName;

    /**
     * @Column(name="last_name", type="string", length=255, nullable=true)
     */
    protected $_lastName;

    /**
     * @Column(name="middle_name", type="string", length=255, nullable=true)
     */
    protected $_middleName;

    public function getId()
    {
        return $this->_id;
    }

    public function setId($_id)
    {
        $this->_id = $_id;
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

    public function isActive()
    {
        return $this->_active;
    }

    public function setActive($active)
    {
        $this->_active = (bool) $active;
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

    public function setMiddleName($middleName)
    {
        $this->_middleName = $middleName;
        return $this;
    }

    public function getMiddleName()
    {
        return $this->_middleName;
    }
}
