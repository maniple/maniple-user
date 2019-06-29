<?php

interface ManipleUser_Model_UserInterface extends Maniple_Security_UserInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id);

    /**
     * @return string
     */
    public function getUsername();

    /**
     * @param string $username
     * @return $this
     */
    public function setUsername($username);

    /**
     * @return string
     */
    public function getPassword();

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword($password);

    /**
     * @return string
     */
    public function getEmail();

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail($email);

    /**
     * @return string
     */
    public function getFirstName();

    /**
     * @param string $firstName
     * @return $this
     */
    public function setFirstName($firstName);

    /**
     * @return string
     */
    public function getLastName();

    /**
     * @param string $lastName
     * @return $this
     */
    public function setLastName($lastName);

    /**
     * @return bool
     */
    public function isActive();

    /**
     * @param bool $active
     * @return $this
     */
    public function setActive($active);

    /**
     * @return DateTime|null
     */
    public function getCreatedAt();

    /**
     * @param DateTime|string|int $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt);
}
