<?php

use Maniple\ModUser\Entity\UserInterface;

interface ModUser_Model_UserManagerInterface
{
    public function getUser($id);

    public function getUserByUsername($username);

    public function getUserByEmail($email);

    public function getUserByUsernameOrEmail($usernameOrEmail);

    public function getUsers(array $ids = null);

    public function createUser(array $data = null);

    public function saveUser(UserInterface $user);
}
