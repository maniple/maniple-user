<?php

interface ManipleUser_Model_UserMapperInterface
{
    public function getUser($id);

    public function getUserByUsername($username);

    public function getUserByEmail($email);

    /**
     * @param string $usernameOrEmail
     * @return ManipleUser_Model_UserInterface|null
     */
    public function getUserByUsernameOrEmail($usernameOrEmail);

    public function getUsers(array $ids = null);

    public function createUser(array $data = null);

    public function saveUser(ManipleUser_Model_UserInterface $user);
}
