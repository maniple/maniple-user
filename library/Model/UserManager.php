<?php

/**
 * Zend_Db_Table based user repository.
 *
 * @package ModUser
 * @version 2015-03-30
 * @uses    Zend_Db_Table
 * @uses    Zefram_Stdlib
 */
class ModUser_Model_UserManager implements ModUser_Model_UserManagerInterface
{
    /**
     * @var ModUser_Model_UserMapperInterface
     */
    protected $_userMapper;

    /**
     * @var Zend_Cache_Core
     */
    protected $_cache;

    /**
     * @param ModUser_Model_UserMapperInterface $userMapper
     * @return $this
     */
    public function setUserMapper(ModUser_Model_UserMapperInterface $userMapper)
    {
        $this->_userMapper = $userMapper;
        return $this;
    }

    /**
     * @return ModUser_Model_UserMapperInterface
     * @throws Exception
     */
    public function getUserMapper()
    {
        if (!$this->_userMapper) {
            throw new Exception('UserMapper property is not configured');
        }
        return $this->_userMapper;
    }

    /**
     * Currently unused.
     * @param  Zend_Cache_Core $cache
     * @return ModUser_Model_UserManager
     */
    public function setCache(Zend_Cache_Core $cache = null)
    {
        $this->_cache = $cache;
        return $this;
    }

    /**
     * @param  int $userId
     * @return ModUser_Model_UserInterface|null
     */
    public function getUser($userId)
    {
        return $this->getUserMapper()->getUser($userId);
    }

    /**
     * @param  string $username
     * @return ModUser_Model_UserInterface|null
     */
    public function getUserByUsername($username)
    {
        return $this->getUserMapper()->getUserByUsername($username);
    }

    /**
     * @param  string $email
     * @return ModUser_Model_UserInterface|null
     */
    public function getUserByEmail($email)
    {
        return $this->getUserMapper()->getUserByEmail($email);
    }

    /**
     * @param  string $usernameOrEmail
     * @return ModUser_Model_UserInterface|null
     */
    public function getUserByUsernameOrEmail($usernameOrEmail)
    {
        return $this->getUserMapper()->getUserByUsernameOrEmail($usernameOrEmail);
    }

    /**
     * @param  array $userIds
     * @return ModUser_Model_UserInterface[]
     */
    public function getUsers(array $userIds = null)
    {
        return $this->getUserMapper()->getUsers($userIds);
    }

    /**
     * Saves user entity to the storage.
     *
     * @param  ModUser_Model_UserInterface $user
     * @return ModUser_Model_UserInterface
     * @throws Exception
     */
    public function saveUser(ModUser_Model_UserInterface $user)
    {
        return $this->getUserMapper()->saveUser($user);
    }

    /**
     * @param array $data
     * @return ModUser_Model_UserInterface
     */
    public function createUser(array $data = null)
    {
        return $this->getUserMapper()->createUser($data);
    }

    public function validateUser(ModUser_Model_UserInterface $user)
    {
        // TODO not sure if this should be here
        return true;
    }

    /**
     * @TODO this should not be in repository, but in service
     * @param ModUser_Model_UserInterface $user
     * @param string $password
     * @return string
     */
    public function getPasswordHash($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * @TODO this should not be in repository, but in service
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public function verifyPasswordHash($password, $hash)
    {
        return password_verify($password, $hash);
    }
}
