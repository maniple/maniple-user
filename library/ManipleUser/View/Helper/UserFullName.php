<?php

/**
 * @property Zend_View_Abstract $view
 */
class ManipleUser_View_Helper_UserFullName extends Zend_View_Helper_Abstract
{
    /**
     * @var array
     */
    protected $_options = array(
        'escape'     => true,
        'reverse'    => false,
        'middleName' => false,
    );

    /**
     * @param  ManipleUser_Model_UserInterface|array $user
     * @param  array $options OPTIONAL
     * @return string
     */
    public function userFullName($user, array $options = null)
    {
        $options = array_merge($this->_options, (array) $options);

        $names = array_map('trim', $this->_extractNames($user));

        if ($options['reverse']) {
            // eastern name order
            list($firstName, $middleName, $lastName) = $names;
            $names = array($lastName, $firstName, $middleName);
        }

        $fullName = implode(' ', array_filter($names, 'strlen'));

        if ($options['escape']) {
            return $this->view->escape($fullName);
        }

        return $fullName;
    }

    /**
     * @param ManipleUser_Model_UserInterface|array $source
     * @return array
     */
    protected function _extractNames($source)
    {
        if ($source instanceof ManipleUser_Model_UserInterface) {
            return array(
                $source->getFirstName(),
                method_exists($source, 'getMiddleName') ? $source->getMiddleName() : null,
                $source->getLastName(),
            );
        }

        return array(
            $this->_extractProperty($source, 'first_name'),
            $this->_extractProperty($source, 'middle_name'),
            $this->_extractProperty($source, 'last_name'),
        );
    }

    /**
     * @param  array|object $object
     * @param  string $property
     * @return mixed
     */
    protected function _extractProperty($object, $property)
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

    /**
     * @param  array $options
     * @return $this
     */
    public function setOptions(array $options)
    {
        $this->_options = array_merge(
            $this->_options,
            array_intersect_key(
                array_keys($this->_options),
                $options
            )
        );
        return $this;
    }
}
