<?php

use Maniple\ModUser\Entity\UserInterface;

class ModUser_Form_Password extends Zefram_Form
{
    /**
     * @var UserInterface
     */
    protected $_user;

    /**
     * Constructor.
     *
     * @param  UserInterface $user
     * @return void
     */
    public function __construct(UserInterface $user) // {{{
    {
        $elements = array();

        if ($user->getPassword()) {
            $elements['password_current'] = array(
                'type' => 'password',
                'options' => array(
                    'label' => 'Current password',
                    'required' => true,
                    'validators' => array(
                        array(
                            'PasswordVerify',
                            true,
                            array('hash' => $user->getPassword()),
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
                        new ModUser_Validate_Password(),
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
    } // }}}

    /**
     * Retrieve user entity attached to this form.
     *
     * @return UserInterface
     */
    public function getUser() // {{{
    {
        return $this->_user;
    } // }}}
}
