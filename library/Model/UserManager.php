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
     * @var string
     */
    protected $_userClass = 'ModUser_Model_User';

    /**
     * @var string
     */
    protected $_userMapperClass = 'ModUser_Model_UserMapper';

    /**
     * @var ModUser_Model_UserMapper
     */
    protected $_userMapper;

    /**
     * @var Zefram_Db_TableProvider
     */
    protected $_tableManager;

    /**
     * @var Zend_Cache_Core
     */
    protected $_cache;

    /**
     * @param  string $userClass
     * @return ModUser_Model_UserManager
     * @throws InvalidArgumentException
     */
    public function setUserClass($userClass)
    {
        $userClass = (string) $userClass;

        // can't use is_subclass_of as prior to PHP 5.3.7 it does not
        // check interfaces
        if (!in_array('ModUser_Model_UserInterface', class_implements($userClass))) {
            throw new InvalidArgumentException('User class must implement ModUser_Model_UserInterface interface');
        }

        $this->_userClass = $userClass;

        return $this;
    }

    /**
     * @param  string $userMapperClass
     * @return ModUser_Model_UserManager
     * @throws InvalidArgumentException
     */
    public function setUserMapperClass($userMapperClass)
    {
        $userMapperClass = (string) $userMapperClass;

        if (!in_array('ModUser_Model_UserMapper', class_implements($userMapperClass))) {
            throw new InvalidArgumentException('User mapper class must inherit from ModUser_Model_UserMapper class');
        }

        $this->_userMapperClass = $userMapperClass;
        return $this;
    }

    /**
     * @param  Zefram_Db_TableProvider $tableProvider OPTIONAL
     * @return ModUser_Model_UserManager
     */
    public function setTableManager(Zefram_Db_TableProvider $tableManager = null)
    {
        $this->_tableManager = $tableManager;
        return $this;
    }

    public function getTableManager()
    {
        if (!$this->_tableManager instanceof Zefram_Db_TableProvider) {
            throw new Exception('Table manager is not initialized');
        }
        return $this->_tableManager;
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
        $userId = (int) $userId;
        $row = $this->_getUsersTable()->findRow($userId);
        if ($row) {
            return $this->createUser($row->toArray());
        }
        return null;
    }

    /**
     * @param  string $username
     * @return ModUser_Model_UserInterface|null
     */
    public function getUserByUsername($username)
    {
        $username = (string) $username;
        return $this->_getUserBy(array('username = LOWER(?)' => $username));
    }

    /**
     * @param  string $email
     * @return ModUser_Model_UserInterface|null
     */
    public function getUserByEmail($email)
    {
        $email = (string) $email;
        return $this->_getUserBy(array('email = LOWER(?)' => $email));
    }

    /**
     * @param  string $usernameOrEmail
     * @return ModUser_Model_UserInterface|null
     */
    public function getUserByUsernameOrEmail($usernameOrEmail)
    {
        $usernameOrEmail = (string) $usernameOrEmail;

        // usernames and emails are required to be stored lowercase only
        return $this->_getUserBy(array(
            'username = LOWER(?) OR email = LOWER(?)' => $usernameOrEmail,
        ));
    }

    /**
     * @param  array $userIds
     * @return ModUser_Model_UserInterface[]
     */
    public function getUsers(array $userIds = null)
    {
        $users = array();

        if ($userIds) {
            $userIds = array_map('intval', $userIds);
            $where = array('user_id IN (?)' => $userIds);
        } else {
            $where = null;
        }

        $rows = $this->_getUsersTable()->fetchAll($where);
        foreach ($rows as $row) {
            $users[$user->getId()] = $this->createUser($row->toArray());
        }

        return $users;
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
        $userId = $user->getId();

        if ($userId) {
            $row = $this->_getUsersTable()->findRow((int) $userId);
        }

        if (empty($row)) {
            $row = $this->_getUsersTable()->createRow();
            $isCreate = true;
        } else {
            $isCreate = false;
        }

        if (!$this->validateUser($user)) {
            throw new Exception('User model contains invalid data');
        }

        $data = $this->_getUserMapper()->getAsArray($user);

        if ($isCreate) {
            // disallow explicitly setting value on auto increment column, as
            // in some DBMS write may fail if sequence reaches value that is
            // already present in the table
            $sequence = $row->getTable()->info(Zend_Db_Table_Abstract::SEQUENCE);
            foreach ($row->getPrimaryKey() as $column => $value) {
                if ($sequence === true || $sequence === $column) {
                    unset($data[$column]);
                }
            }
        }

        $row->setFromArray($data);
        $row->save();

        $this->_getUserMapper()->setFromArray($user, $row->toArray());
        return $user;
    }

    /**
     * Creates a new instance of user entity.
     *
     * @param  array $data OPTIONAL
     * @return ModUser_Model_UserInterface
     */
    public function createUser(array $data = null)
    {
        $userClass = $this->_userClass;
        $user = new $userClass();
        if ($data) {
            $this->_getUserMapper()->setFromArray($user, $data);
        }
        return $user;
    }

    public function validateUser(ModUser_Model_UserInterface $user)
    {
        // TODO not sure if this should be here
        return true;
    }

    /**
     * @param  string|array|Zend_Db_Expr $where
     * @return ModUser_Model_UserInterface|null
     */
    protected function _getUserBy($where)
    {
        $row = $this->_getUsersTable()->fetchRow($where);
        if ($row) {
            return $this->createUser($row->toArray());
        }
        return null;
    }

    /**
     * @return ModUser_Model_DbTable_Users
     * @internal
     */
    protected function _getUsersTable()
    {
        return $this->getTableManager()->getTable('ModUser_Model_DbTable_Users');
    }

    /**
     * @return ModUser_Model_UserMapper
     * @internal
     */
    protected function _getUserMapper()
    {
        if (!$this->_userMapper instanceof ModUser_Model_UserMapper) {
            $this->_userMapper = new ModUser_Model_UserMapper();
        }
        return $this->_userMapper;
    }
}
