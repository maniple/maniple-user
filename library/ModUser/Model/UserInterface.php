<?php

interface ModUser_Model_UserInterface
{
    public function getId();

    public function setId($id);

    public function getUsername();

    public function setUsername($username);

    public function getPassword();

    public function setPassword($password);

    public function getEmail();

    public function setEmail($email);

    public function getFirstName();

    public function setFirstName($firstName);

    public function getLastName();

    public function setLastName($lastName);

    public function isActive();

    public function setActive($active);

    public function getCreatedAt();

    public function setCreatedAt($createdAt);
}
