<?php

class ManipleUser_Form_Password extends Zefram_Form
{
    /**
     * @var ManipleUser_Model_UserInterface
     */
    protected $_user;

    /**
     * @param ManipleUser_Service_Password $passwordService
     * @param ManipleUser_Model_UserInterface $user
     */
    public function __construct(
        ManipleUser_Service_Password $passwordService,
        ManipleUser_Model_UserInterface $user
    ) {
        $elements = array();

        if ($user->getPassword()) {
            $elements['password_current'] = array(
                'type' => 'password',
                'options' => array(
                    'label' => 'Current password',
                    'required' => true,
                    'validators' => array(
                        array(
                            new ManipleUser_Validate_PasswordVerify($passwordService, $user->getPassword()),
                            true,
                        ),
                    ),
                ),
            );
        }

        $elements['password'] = array(
            'type' => 'password',
            'options' => array(
                'label' => 'New password',
                'required' => true,
                'validators' => array(
                    array(
                        'NotEqual',
                        true,
                        array(
                            'token' => $user->getUsername(),
                            'useContext' => false,
                        ),
                    ),
                    array(
                        'NotEqual',
                        true,
                        array(
                            'token' => $user->getEmail(),
                            'useContext' => false,
                        ),
                    ),
                    array(
                        new ManipleUser_Validate_Password(),
                        true,
                    ),
                ),
            ),
        );

        $elements['password_confirm'] = array(
            'type' => 'password',
            'options' => array(
                'label' => 'Confirm new password',
                'required' => true,
                'validators' => array(
                    array(
                        'Identical',
                        true,
                        array(
                            'token' => 'password',
                            'messages' => array(
                                Zend_Validate_Identical::NOT_SAME => 'Passwords do not match',
                            ),
                        ),
                    ),
                ),
            ),
        );

        $elements['__submit'] = array(
            'type' => 'submit',
            'options' => array(
                'label' => 'Save changes',
            ),
        );

        $this->_user = $user;

        parent::__construct(compact('elements'));
    }

    /**
     * Retrieve user entity attached to this form.
     *
     * @return ManipleUser_Model_UserInterface
     */
    public function getUser()
    {
        return $this->_user;
    }
}
