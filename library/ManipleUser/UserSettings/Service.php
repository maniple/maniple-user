<?php

class ManipleUser_UserSettings_Service
{
    /**
     * @var Maniple_Security_ContextInterface
     */
    protected $_securityContext;

    /**
     * @var ManipleUser_UserSettings_Adapter_Interface
     */
    protected $_adapter;

    public function __construct(Maniple_Security_ContextInterface $security, ManipleUser_UserSettings_Adapter_Interface $adapter)
    {
        $this->_securityContext = $security;
        $this->_adapter = $adapter;
    }

    /**
     * Retrieve all settings of a given user
     *
     * @param int|ModUser_Model_UserInterface $user OPTIONAL
     * @return array
     */
    public function getAll($user = null)
    {
        return $this->_adapter->getAll($this->_getUserId($user));
    }

    /**
     * Retrieve single user setting
     *
     * @param string $name
     * @param int|ModUser_Model_UserInterface $user OPTIONAL
     * @return mixed
     */
    public function get($name, $user = null)
    {
        return $this->_adapter->get($this->_getUserId($user), $name);
    }

    /**
     * Set user setting
     *
     * @param string $name
     * @param mixed $value
     * @param int|ModUser_Model_UserInterface $user OPTIONAL
     * @return $this
     */
    public function set($name, $value, $user = null)
    {
        $this->_adapter->set($this->_getUserId($user), $name, $value);
        return $this;
    }

    /**
     * Remove user setting
     *
     * @param $name
     * @param int|ModUser_Model_UserInterface $user OPTIONAL
     * @return bool
     */
    public function remove($name, $user = null)
    {
        return $this->_adapter->remove($this->_getUserId($user), $name);
    }

    /**
     * @param ModUser_Model_UserInterface|null|int $user
     * @return int
     */
    protected function _getUserId($user)
    {
        if ($user === null) {
            $userId = $this->_securityContext->getIdentity();
            if (empty($userId)) {
                throw new Exception('No user is authenticated');
            }
        } elseif ($user instanceof ModUser_Model_UserInterface) {
            $userId = $user->getId();
        } else {
            $userId = $user;
        }

        $userId = (int) $userId;

        return $userId;
    }

    /**
     * Returns list of user IDs that have matching setting
     *
     * @param string $name
     * @param mixed $value
     * @return int[]
     */
    public function getUsersWithPref($name, $value)
    {
        return $this->_adapter->getUsersWithSetting($name, $value);
    }
}
