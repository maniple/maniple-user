<?php

class ModUser_Form_Registration extends Zefram_Form
{
    public function __construct(ModUser_Model_UserManagerInterface $userManager)
    {
        $elements = array(
            'first_name' => array(
                'type' => 'text',
                'options' => array(
                    'label' => 'First name',
                    'required' => true,
                    'validators' => array(
                        array('StringLength', true, array('max' => 128)),
                    ),
                    'filters' => array(
                        'StringTrim',
                    ),
                ),
            ),
            'last_name' => array(
                'type' => 'text',
                'options' => array(
                    'label' => 'Surname',
                    'required' => true,
                    'validators' => array(
                        array('StringLength', true, array('max' => 128)),
                    ),
                    'filters' => array(
                        'StringTrim',
                    ),
                ),
            ),
            'email' => array(
                'type' => 'text',
                'options' => array(
                    'label' => 'Email address',
                    'required' => true,
                    'validators' => array(
                        array('StringLength', true, array('max' => 128)),
                        array('EmailAddress', true),
                        array(new ModUser_Validate_UserNotExists(array(
                            'userRepository' => $userManager,
                            'matchBy' => ModUser_Validate_User::MATCH_EMAIL,
                            'messages' => array(
                                ModUser_Validate_User::USER_EXISTS => 'This email address is already in use',
                            ),
                        )), true),
                    ),
                    'filters' => array(
                        'StringToLower',
                    ),
                ),
            ),
            'email_verify' => array(
                'type' => 'text',
                'options' => array(
                    'label' => 'Confirm email address',
                    'required' => true,
                    'validators' => array(
                        array('StringLength', true, array('max' => 128)),
                        array('EmailAddress', true),
                        array('Identical',    true, array(
                            'token' => 'email',
                            'messages' => array(
                                Zend_Validate_Identical::NOT_SAME => 'Email address mismatch',
                            ),
                        )),
                    ),
                    'filters' => array(
                        'StringToLower',
                    ),
                ),
            ),
            'password' => array(
                'type' => 'password',
                'options' => array(
                    'label' => 'Password',
                    'required' => true,
                    'validators' => array(
                        array(new Zefram_Validate_NotEqual(array(
                            'token' => 'email',
                            'useContext' => true,
                        )), true),
                    ),
                ),
            ),
            '__submit' => array(
                'type' => 'button',
                'options' => array(
                    'label' => 'Sign up',
                    'attribs' => array(
                        'type' => 'submit',
                    ),
                ),
            ),
        );

        parent::__construct(compact('elements'));
    }
}
