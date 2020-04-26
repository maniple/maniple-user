<?php

class ManipleUser_Filter_FriendlyName implements Zend_Filter_Interface
{
    /**
     * @param ManipleUser_Model_UserInterface $user
     * @return string
     */
    public function filter($user)
    {
        return self::filterStatic($user);
    }

    /**
     * @param ManipleUser_Model_UserInterface $user
     * @return string
     */
    public static function filterStatic($user)
    {
        if (!$user instanceof ManipleUser_Model_UserInterface) {
            throw new InvalidArgumentException(sprintf(
                'ManipleUser_Model_UserInterface expected, %s was given',
                is_object($user) ? get_class($user) : gettype($user)
            ));
        }

        $name = $user->getFirstName();
        if (!$name) {
            $name = $user->getUsername();
        }
        if ($name === $user->getEmail()) {
            $name = substr($name, 0, strpos($name, '@'));
        }
        return $name;
    }
}
