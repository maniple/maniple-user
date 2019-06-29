<?php

/**
 * Zend_Db_Table based user repository.
 *
 * @version 2015-03-30
 * @uses    Zend_Db_Table
 * @uses    Zefram_Stdlib
 */
class ManipleUser_Model_UserManager implements ManipleUser_Model_UserManagerInterface
{
    /**
     * @var ManipleUser_Model_UserMapperInterface
     */
    protected $_userMapper;

    /**
     * @var Zend_Cache_Core
     */
    protected $_cache;

    /**
     * @var Zend_EventManager_EventManager
     */
    protected $_events;

    public function __construct(Zend_EventManager_SharedEventManager $sharedEventManager = null)
    {
        $this->_events = new Zend_EventManager_EventManager();
        $this->_events->setIdentifiers(array(
            'user.userManager',
            __CLASS__,
            get_class($this),
        ));
        if ($sharedEventManager) {
            $this->_events->setSharedCollections($sharedEventManager);
        }
    }

    /**
     * @return Zend_EventManager_EventManager
     */
    public function getEventManager()
    {
        return $this->_events;
    }

    /**
     * @param ManipleUser_Model_UserMapperInterface $userMapper
     * @return $this
     */
    public function setUserMapper(ManipleUser_Model_UserMapperInterface $userMapper)
    {
        $this->_userMapper = $userMapper;
        return $this;
    }

    /**
     * @return ManipleUser_Model_UserMapperInterface
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
     * @return ManipleUser_Model_UserManager
     */
    public function setCache(Zend_Cache_Core $cache = null)
    {
        $this->_cache = $cache;
        return $this;
    }

    /**
     * @param  int $userId
     * @return ManipleUser_Model_UserInterface|null
     */
    public function getUser($userId)
    {
        return $this->getUserMapper()->getUser($userId);
    }

    /**
     * @param  string $username
     * @return ManipleUser_Model_UserInterface|null
     */
    public function getUserByUsername($username)
    {
        return $this->getUserMapper()->getUserByUsername($username);
    }

    /**
     * @param  string $email
     * @return ManipleUser_Model_UserInterface|null
     */
    public function getUserByEmail($email)
    {
        return $this->getUserMapper()->getUserByEmail($email);
    }

    /**
     * @param  string $usernameOrEmail
     * @return ManipleUser_Model_UserInterface|null
     */
    public function getUserByUsernameOrEmail($usernameOrEmail)
    {
        return $this->getUserMapper()->getUserByUsernameOrEmail($usernameOrEmail);
    }

    /**
     * @param  array $userIds
     * @return ManipleUser_Model_UserInterface[]
     */
    public function getUsers(array $userIds = null)
    {
        return $this->getUserMapper()->getUsers($userIds);
    }

    /**
     * Saves user entity to the storage.
     *
     * @param  ManipleUser_Model_UserInterface $user
     * @return ManipleUser_Model_UserInterface
     * @throws Exception
     */
    public function saveUser(ManipleUser_Model_UserInterface $user)
    {
        return $this->getUserMapper()->saveUser($user);
    }

    /**
     * @param array $data
     * @return ManipleUser_Model_UserInterface
     * @deprecated
     */
    public function createUser(array $data = null)
    {
        return $this->getUserMapper()->createUser($data);
    }

    public function validateUser(ManipleUser_Model_UserInterface $user)
    {
        // TODO not sure if this should be here
        return true;
    }

    /**
     * @TODO this should not be in repository, but in service
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
