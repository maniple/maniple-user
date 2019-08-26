<?php

/**
 * Service for managing users
 */
class ManipleUser_Service_UserManager implements ManipleUser_Service_UserManagerInterface
{
    /**
     * @Inject('user.model.userMapper')
     * @var ManipleUser_Model_UserMapperInterface
     */
    protected $_userMapper;

    /**
     * @Inject
     * @var ManipleUser_Service_Username
     */
    protected $_usernameService;

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
     * Currently unused.
     * @param  Zend_Cache_Core $cache
     * @return ManipleUser_Service_UserManager
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
        return $this->_userMapper->getUser($userId);
    }

    /**
     * @param  string $username
     * @return ManipleUser_Model_UserInterface|null
     */
    public function getUserByUsername($username)
    {
        return $this->_userMapper->getUserByUsername($username);
    }

    /**
     * @param  string $email
     * @return ManipleUser_Model_UserInterface|null
     */
    public function getUserByEmail($email)
    {
        return $this->_userMapper->getUserByEmail($email);
    }

    /**
     * @param  string $usernameOrEmail
     * @return ManipleUser_Model_UserInterface|null
     */
    public function getUserByUsernameOrEmail($usernameOrEmail)
    {
        return $this->_userMapper->getUserByUsernameOrEmail($usernameOrEmail);
    }

    /**
     * @param  array $userIds
     * @return ManipleUser_Model_UserInterface[]
     */
    public function getUsers(array $userIds = null)
    {
        return $this->_userMapper->getUsers($userIds);
    }

    /**
     * Saves user entity to the storage.
     *
     * @param  ManipleUser_Model_UserInterface $user
     * @return ManipleUser_Model_UserInterface
     */
    public function saveUser(ManipleUser_Model_UserInterface $user)
    {
        if (!preg_match('/^[_0-9A-Za-z]+$/', $user->getUsername())) {
            $username = $this->_usernameService->generateUsername(array(
                'email'      => $user->getEmail(),
                'first_name' => $user->getFirstName(),
                'last_name'  => $user->getLastName(),
            ));
            $user->setUsername($username);
        }

        return $this->_userMapper->saveUser($user);
    }

    /**
     * @param array $data
     * @return ManipleUser_Model_UserInterface
     * @deprecated
     */
    public function createUser(array $data = null)
    {
        return $this->_userMapper->createUser($data);
    }
}
