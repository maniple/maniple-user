<?php

class ManipleUser_Filter_FullName implements Zend_Filter_Interface
{
    protected static $_defaultOptions = array(
        'reverse'    => false,
        'middleName' => false,
    );

    /**
     * @var array
     */
    protected $_options;

    public function __construct(array $options = null)
    {
        $this->_options = self::$_defaultOptions;
        if ($options) {
            $this->setOptions($options);
        }
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->_options;
    }

    /**
     * @param  array $options
     * @return $this
     */
    public function setOptions(array $options)
    {
        foreach ($options as $name => $value) {
            $this->setOption($name, $value);
        }
        return $this;
    }

    public function setOption($name, $value)
    {
        if (array_key_exists($name, $this->_options)) {
            $this->_options[$name] = $value;
        }
        return $this;
    }

    /**
     * @param mixed $user
     * @return string
     */
    public function filter($user)
    {
        return self::filterStatic($user, $this->_options);
    }

    /**
     * @param mixed $user
     * @param array $options OPTIONAL
     * @return string
     */
    public static function filterStatic($user, array $options = null)
    {
        $options = array_merge(self::$_defaultOptions, (array) $options);
        $names = array_map('trim', self::_extractNames($user));

        if (!$options['middleName']) {
            $names[1] = null;
        }

        if ($options['reverse']) {
            // eastern name order
            list($firstName, $middleName, $lastName) = $names;
            $names = array($lastName, $firstName, $middleName);
        }

        return implode(' ', array_filter($names, 'strlen'));
    }

    /**
     * @param ManipleUser_Model_UserInterface|array $source
     * @return array
     */
    protected static function _extractNames($source)
    {
        if ($source instanceof ManipleUser_Model_UserInterface) {
            return array(
                $source->getFirstName(),
                method_exists($source, 'getMiddleName') ? $source->getMiddleName() : null,
                $source->getLastName(),
            );
        }

        return array(
            self::_extractProperty($source, 'first_name'),
            self::_extractProperty($source, 'middle_name'),
            self::_extractProperty($source, 'last_name'),
        );
    }

    /**
     * @param  array|object $object
     * @param  string $property
     * @return mixed
     */
    protected static function _extractProperty($object, $property)
    {
        $property = (string) $property;

        if ((is_array($object) || $object instanceof ArrayAccess) && isset($object[$property])) {
            return $object[$property];
        }

        if (is_object($object)) {
            if (isset($object->{$property})) {
                return $object->{$property};
            }

            $getter = 'get' . $property;
            if (method_exists($object, $getter)) {
                return $object->{$getter}();
            }
        }

        return null;
    }
}
