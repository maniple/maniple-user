<?php

/**
 * @property Zend_View_Abstract $view
 */
class ManipleUser_View_Helper_UserFullName extends Zend_View_Helper_Abstract
{
    /**
     * Options:
     * - bool escape = true
     * - bool reverse = false
     * - bool middleName = false
     *
     * @param  ManipleUser_Model_UserInterface|array $user
     * @param  array $options OPTIONAL
     * @return string
     */
    public function userFullName($user, array $options = array())
    {
        $options = $options + array(
            'escape'     => true,
            'reverse'    => false,
            'middleName' => false,
        );

        $fullName = ManipleUser_Filter_FullName::filterStatic($user, $options);

        if ($options['escape']) {
            return $this->view->escape($fullName);
        }

        return $fullName;
    }
}
