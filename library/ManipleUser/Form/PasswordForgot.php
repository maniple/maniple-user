<?php

class ManipleUser_Form_PasswordForgot extends Zefram_Form
{
    public function __construct(ManipleUser_Model_UserMapperInterface $userRepository)
    {
        $elements = array(
            'username' => array(
                'type' => 'text',
                'options' => array(
                    'label' => 'Username or email',
                    'required' => true,
                    'validators' => array(
                        new ManipleUser_Validate_UserExists(array(
                            'matchBy' => ManipleUser_Validate_User::MATCH_USERNAME_OR_EMAIL,
                            'userRepository' => $userRepository,
                        )),
                    ),
                ),
            ),
            '__submit' => array(
                'type' => 'submit',
                'options' => array(
                    'label' => 'Submit',
                ),
            ),
        );

        parent::__construct(compact('elements'));
    }
}
