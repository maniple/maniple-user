<?php

interface ManipleUser_Model_UserManagerInterface
{
    /**
     * @param  int $userId
     * @return ManipleUser_Model_UserInterface|null
     */
    public function getUser($id);

    /**
     * @param  string $username
     * @return ManipleUser_Model_UserInterface|null
     */
    public function getUserByUsername($username);

    /**
     * @param  string $email
     * @return ManipleUser_Model_UserInterface|null
     */
    public function getUserByEmail($email);

    /**
     * @param  string $usernameOrEmail
     * @return ManipleUser_Model_UserInterface|null
     */
    public function getUserByUsernameOrEmail($usernameOrEmail);

    /**
     * @param  array $ids
     * @return ManipleUser_Model_UserInterface[]
     */
    public function getUsers(array $ids = null);

    public function createUser(array $data = null);

    /**
     * Saves user entity to the storage.
     *
     * @param  ManipleUser_Model_UserInterface $user
     * @return ManipleUser_Model_UserInterface
     * @throws Exception
     */
    public function saveUser(ManipleUser_Model_UserInterface $user);
}
