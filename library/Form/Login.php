<?php

class ModUser_Form_Login extends Zend_Form
{
    public function __construct()
    {
        $elements = array(
            'username' => array(
                'type' => 'text',
                'options' => array(
                    'label' => 'Nazwa użytkownika / e-mail',
                    'required' => true,
                    'filters' => array(
                        'StringToLower',
                    ),
                    'autofocus' => '',
                ),
            ),
            'password' => array(
                'type' => 'password',
                'options' => array(
                    'label' => 'Hasło',
                    'required' => true,
                ),
            ),
            'continue' => array(
                'type' => 'hidden',
            ),
            '__submit' => array(
                'type' => 'button',
                'options' => array(
                    'type' => 'submit',
                    'label' => 'Zaloguj',
                ),
            ),
        );

        parent::__construct(compact('elements'));
    }
}
