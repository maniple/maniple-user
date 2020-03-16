<?php

class ManipleUser_Form_Login extends Zend_Form
{
    const className = __CLASS__;

    public function __construct()
    {
        $elements = array(
            'username' => array(
                'type' => 'text',
                'options' => array(
                    'label' => 'Username or email',
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
                    'label' => 'Password',
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
                    'label' => 'Sign in',
                ),
            ),
        );

        parent::__construct(compact('elements'));
    }
}
