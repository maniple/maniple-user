<?php

class ModUser_Validate_UserExists extends ModUser_Validate_User
{
    /**
     * @param  mixed $value
     * @return bool
     */
    public function isValid($value)
    {
        $this->_value = $value;
        $this->_user = $this->_getUserByValue($value);

        if (empty($this->_user)) {
            $this->_error(self::USER_NOT_EXISTS);
            return false;
        }

        return true;
    }
}
