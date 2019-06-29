<?php

class ManipleUser_Form_PasswordForgot extends Zefram_Form
{
    public function __construct(ManipleUser_Model_UserMapperInterface $userManager)
    {
        $elements = array(
            'username' => array(
                'type' => 'text',
                'options' => array(
                    'label' => 'Username or email', // 'Nazwa użytkownika / e-mail',
                    'required' => true,
                    'validators' => array(
                        new ManipleUser_Validate_UserExists(array(
                            'matchBy' => ManipleUser_Validate_User::MATCH_USERNAME_OR_EMAIL,
                            'userRepository' => $userManager,
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
