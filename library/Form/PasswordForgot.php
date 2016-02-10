<?php

class ModUser_Form_PasswordForgot extends Zefram_Form
{
    public function __construct(ModUser_Model_UserManagerInterface $userManager)
    {
        $elements = array(
            'username' => array(
                'type' => 'text',
                'options' => array(
                    'label' => 'Username or email', // 'Nazwa użytkownika / e-mail',
                    'required' => true,
                    'validators' => array(
                        new ModUser_Validate_UserExists(array(
                            'matchBy' => ModUser_Validate_User::MATCH_USERNAME_OR_EMAIL,
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
