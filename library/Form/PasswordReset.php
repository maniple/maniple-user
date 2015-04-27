<?php

class ModUser_Form_PasswordReset extends Zefram_Form
{
    protected $_user;

    public function __construct(ModUser_Model_UserInterface $user = null)
    {
        $elements = array(
            'password' => array(
                'type' => 'password',
                'options' => array(
                    'required' => true,
                    'label' => 'Password',
                    'validators' => array(
                        array(new Zefram_Validate_NotEqual(array(
                            'useContext' => false,
                            'messages' => array(
                                Zefram_Validate_NotEqual::IS_EQUAL => 'Hasło musi być różne od nazwy użytkownika / adresu e-mail', // 'Password must be different from username / email',
                            ),
                        )), true),
                        new ModUser_Validate_Password(),
                    ),
                ),
            ),
            'password_confirm' => array(
                'type' => 'password',
                'options' => array(
                    'required' => true,
                    'label' => 'Confirm password',
                    'validators' => array(
                        array('Identical', true, array(
                            'token' => 'password',
                            'messages' => array(
                                Zend_Validate_Identical::NOT_SAME => 'Podane hasła są różne',
                            ),
                        )),
                    ),
                ),
            ),
        );

        parent::__construct(compact('elements'));

        if ($user) {
            $this->setUser($user);
        }
    }

    public function setUser(ModUser_Model_UserInterface $user)
    {
        $this->_user = $user;
        $this->getElement('password')->getValidator('NotEqual')->setToken($user->getUsername());
        return $this;
    }

    public function getUser()
    {
        return $this->_user;
    }
}
