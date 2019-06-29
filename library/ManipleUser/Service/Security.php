<?php

class ManipleUser_Service_Security extends Maniple_Security_ContextAbstract
{
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

        $this->getUserStorage()->impersonate($user);
    }

    /**
     * @param array|ArrayAccess $serviceLocator
     * @return ManipleUser_Service_Security
     */
    public static function factory($serviceLocator)
    {
        $config = $serviceLocator['config'];

        if (is_array($config)) {
            $options = isset($config['mod_user']['security']) ? $config['mod_user']['security'] : null;
        } else {
            $options = isset($config->{'mod_user'}->{'security'}) ? $config->{'mod_user'}->{'security'} : null;
        }
        if (is_object($options) && method_exists($options, 'toArray')) {
            $options = $options->toArray();
        }
        $options = (array) $options;

        return new self($options);
    }
}
