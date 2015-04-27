<?php

class ModUser_Model_UserMapper
{
    /**
     * Set user properties from array.
     *
     * @param  ModUser_Model_UserInterface $user
     * @param  array $data
     */
    public function setFromArray(ModUser_Model_UserInterface $user, array $data)
    {
        $filter = new Zend_Filter_Word_UnderscoreToCamelCase();

        foreach ($data as $key => $value) {
            $method = 'set' . $filter->filter($key);
            if (method_exists($user, $method)) {
                $user->{$method}($value);
            }
        }

        if (isset($data['user_id']) || array_key_exists('user_id', $data)) {
            $user->setId($data['user_id']);
        }

        return $user;
    }

    /**
     * Extract user properties to an array indexed by corresponding row
     * column names.
     *
     * @param  ModUser_Model_UserInterface $user
     * @return array
     */
    public function getAsArray(ModUser_Model_UserInterface $user)
    {
        $filter = new Zend_Filter_Word_CamelCaseToUnderscore();
        $data = array('user_id' => null);

        foreach (get_class_methods($user) as $method) {
            if (!strncasecmp($method, 'get', 3)) {
                $key = strtolower($filter->filter(substr($method, 3)));
                $data[$key] = $user->{$method}();
            }
        }

        $data['user_id'] = $user->getId();

        return $data;
    }
}
