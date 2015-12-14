<?php

class ModUser_Service_Security extends Maniple_Security_ContextAbstract
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

    public function isAllowed($permission)
    {
        // TODO generalize this
        switch ($permission) {
            case 'enroll':
                return $this->isAuthenticated();

            case 'manage':
                return $this->isSuperUser();

            default:
                return false;
        }
    }

    /**
     * @param array|ArrayAccess $serviceLocator
     * @return ModUser_Service_Security
     */
    public static function factory($serviceLocator)
    {
        $config = $serviceLocator['config'];
        $options = isset($config['mod-user']['security']) ? $config['mod-user']['security'] : null;
        return new self($options);
    }
}
