<?php

/**
 * @method ManipleUser_Model_DbTable_Users getTable()
 */
class ManipleUser_Model_User extends Zefram_Db_Table_Row implements ManipleUser_Model_UserInterface
{
    const className = __CLASS__;

    protected $_tableClass = ManipleUser_Model_DbTable_Users::className;

    public function getId()
    {
        return $this->user_id;
    }

    public function setId($id)
    {
        $this->user_id = (int) $id;
        return $this;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = (string) $username;
        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = (string) $password;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = (string) $email;
        return $this;
    }

    public function getFirstName()
    {
        return $this->first_name;
    }

    public function setFirstName($firstName)
    {
        $this->first_name = (string) $firstName;
        return $this;
    }

    public function getLastName()
    {
        return $this->last_name;
    }

    public function setLastName($lastName)
    {
        $this->last_name = (string) $lastName;
        return $this;
    }

    public function isActive()
    {
        return (bool) $this->is_active;
    }

    public function setActive($active)
    {
        $this->is_active = (bool) $active;
        return $this;
    }

    public function getCreatedAt()
    {
        $createdAt = $this->created_at;

        if (null === $createdAt) {
            return null;
        }

        return new DateTime('@' . intval($createdAt));
    }

    public function setCreatedAt($createdAt)
    {
        if ($createdAt instanceof DateTime) {
            $createdAt = $createdAt->getTimestamp();
        }

        $this->created_at = (int) $createdAt;
        return $this;
    }

}
