<?php

interface ModUser_Model_UserInterface extends Maniple_Security_UserInterface
{
    public function getId();

    public function setId($id);

    public function getUsername();

    public function setUsername($username);

    public function getPassword();

    public function setPassword($password);

    public function getEmail();

    public function setEmail($email);

    public function getIsActive();

    public function setIsActive($isActive);

    public function getCreatedAt();

    public function setCreatedAt($createdAt);
}
