<?php

class ManipleUser_Form_Element_CurrentPassword extends Zend_Form_Element_Password
{
    /**
     * @var ManipleUser_Model_UserInterface
     */
    protected $_user;

    public function __construct(ManipleUser_Model_UserInterface $user, array $options = null)
    {
        $this->_user = $user;

        if (is_object($options) && method_exists($options, 'toArray')) {
            $options = $options->toArray();
        }

        $options = array_merge(
            array(
                'name'       => 'current_password',
                'label'      => 'Current password',
                'required'   => true,
                'validators' => array(
                    array(
                        'PasswordVerify',
                        true,
                        array('hash' => $user->getPassword()),
                    ),
                ),
            ),
            (array) $options
        );

        parent::__construct($options);
    }
}
