<?php

namespace Maniple\ModUser\Entity;

// use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity(repositoryClass="Maniple\ModUser\Repository\UserRepository")
 * @Table(name="users")
 */
class User implements UserInterface
{
    /**
     * @Id
     * @Column(name="user_id", type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Column(name="is_active", type="boolint", nullable=false)
     */
    protected $active = true;

    /**
     * @Column(name="created_at", type="epoch")
     */
    protected $createdAt;

    /**
     * @Column(name="username", type="string", length=255, unique=true)
     */
    protected $username;

    /**
     * @Column(name="email", type="string", length=255, unique=true)
     */
    protected $email;

    /**
     * @Column(name="password", type="string", length=255)
     */
    protected $password;

    /**
     * @Column(name="first_name", type="string", length=255, nullable=true)
     */
    protected $firstName;

    /**
     * @Column(name="last_name", type="string", length=255, nullable=true)
     */
    protected $lastName;

    /**
     * @Column(name="middle_name", type="string", length=255, nullable=true)
     */
    protected $middleName;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function isActive()
    {
        return $this->active;
    }

    public function setActive($active)
    {
        $this->active = (bool) $active;
        return $this;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function setMiddleName($middleName)
    {
        $this->middleName = $middleName;
        return $this;
    }

    public function getMiddleName()
    {
        return $this->middleName;
    }
}
