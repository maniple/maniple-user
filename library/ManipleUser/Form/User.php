<?php

class ManipleUser_Form_User extends Zefram_Form
{
    const className = __CLASS__;

    /**
     * @var ManipleUser_Model_UserInterface|null
     */
    protected $_user;

    public function __construct(
        ManipleUser_Model_UserMapperInterface $userManager,
        ManipleUser_Model_DbTable_Roles $rolesTable,
        array $options = array()
    ) {
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
                        array(new ManipleUser_Validate_UserNotExists(array(
                            'userRepository' => $userManager,
                            'matchBy' => ManipleUser_Validate_User::MATCH_EMAIL,
                            'messages' => array(
                                ManipleUser_Validate_User::USER_EXISTS => 'This email address is already in use',
                            ),
                        )), true),
                    ),
                ),
            ),
            'role_id' => array(
                'type' => 'select',
                'options' => array(
                    'label' => 'Primary role',
                    'required' => true,
                    'multioptions' => array_column(
                        array_merge(
                            array(
                                array(
                                    'role_id' => '',
                                    'name' => 'Please choose',
                                ),
                            ),
                            $rolesTable->fetchAll(null, 'name')->toArray()
                        ),
                        'name',
                        'role_id'
                    ),
                ),
            ),
            '_submit' => array(
                'type' => 'submit',
                'options' => array(
                    'label' => 'Save changes',
                ),
            ),
        );

        $options['elements'] = array_merge(
            isset($options['elements']) ? (array) $options['elements'] : array(),
            $elements
        );

        parent::__construct($options);
    }

    /**
     * @param ManipleUser_Model_UserInterface $user
     * @return ManipleUser_Model_UserInterface
     */
    public function populateUser(ManipleUser_Model_UserInterface $user)
    {
        $user->setEmail($this->getValue('email'));
        $user->setFirstName($this->getValue('first_name'));
        $user->setLastName($this->getValue('last_name'));

        return $user;
    }

    /**
     * @param ManipleUser_Model_UserInterface $user
     * @return $this
     */
    public function setDefaultsFromUser(ManipleUser_Model_UserInterface $user)
    {
        $this->setDefaults(array(
            'email'      => $user->getEmail(),
            'first_name' => $user->getFirstName(),
            'last_name'  => $user->getLastName(),
        ));
        return $this;
    }

    /**
     * @param ManipleUser_Model_UserInterface $user
     * @return $this
     */
    public function setUser(ManipleUser_Model_UserInterface $user)
    {
        $this->_user = $user;
        $this->setDefaultsFromUser($user);
        return $this;
    }
}
