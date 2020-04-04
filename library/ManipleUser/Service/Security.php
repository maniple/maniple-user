<?php

class ManipleUser_Service_Security extends Maniple_Security_ContextAbstract
{
    /**
     * @Inject
     * @var Zefram_Db
     */
    protected $_db;

    /**
     * @var array
     */
    protected $_userPerms;

    public function __construct(array $options = null)
    {
        if ($options) {
            $this->setOptions($options);
        }
    }

    public function setOptions(array $options)
    {
        foreach ($options as $key => $value) {
            $method = 'set' . $key;
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
        return $this;
    }

    /**
     * @param Zefram_Db $db
     * @return $this
     */
    public function setDbAdapter(Zefram_Db $db)
    {
        $this->_db = $db;
        return $this;
    }

    public function setSuperUserId($ids)
    {
        foreach ((array) $ids as $id) {
            $this->addSuperUserId($id);
        }
        return $this;
    }

    public function isAllowed($permission, $resource = null)
    {
        if ($this->isSuperUser()) {
            return true;
        }

        // TODO generalize this
        switch ($permission) {
            case 'enroll':
                return $this->isAuthenticated();

            case 'manage':
                return $this->isSuperUser();
        }

        if ($this->isAuthenticated()) {

            if ($this->_userPerms === null) {
                /** @var ManipleUser_Model_DbTable_Perms $permsTable */
                $permsTable = $this->_db->getTable(ManipleUser_Model_DbTable_Perms::className);

                $userPerms = array();
                foreach ($permsTable->fetchPermsByUserId($this->getUser()->getId()) as $perm) {
                    $userPerms[$perm->perm_key] = true;
                }
                $this->_userPerms = $userPerms;
            }

            if (isset($this->_userPerms[$permission])) {
                return true;
            }
        }

        return parent::isAllowed($permission, $resource);
    }

    /**
     * @param Maniple_Security_UserInterface $user
     * @throws Exception
     */
    public function impersonate(Maniple_Security_UserInterface $user)
    {
        if (!$this->isSuperUser()) {
            throw new Exception('User is not allowed to impersonate');
        }

        $this->_userPerms = null;
        $this->getUserStorage()->impersonate($user);
    }

    /**
     * @param array|ArrayAccess $serviceLocator
     * @return ManipleUser_Service_Security
     */
    public static function factory($serviceLocator)
    {
        $options = (array) $serviceLocator['ManipleUser_Bootstrap']->getOption('security');
        $options['dbAdapter'] = $serviceLocator['Zefram_Db'];

        return new self($options);
    }
}
