<?php

class ManipleUser_Form_User extends Zefram_Form
{
    const className = __CLASS__;

    /**
     * @var ManipleUser_Model_UserInterface|null
     */
    protected $_user;

    /**
     * @var ManipleUser_Model_UserMapperInterface
     */
    protected $_userRepository;

    /**
     * @var ManipleUser_UsersService
     */
    protected $_usersService;

    /**
     * @var Zend_Db_Adapter_Abstract
     */
    protected $_rolesTable;

    public function __construct(
        ManipleUser_UsersService $usersService,
        ManipleUser_Model_UserMapperInterface $userRepository,
        ManipleUser_Model_DbTable_Roles $rolesTable,
        array $options = array()
    ) {
        $this->_usersService = $usersService;
        $this->_userRepository = $userRepository;
        $this->_rolesTable = $rolesTable;

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
                            'userRepository' => $userRepository,
                            'matchBy' => ManipleUser_Validate_User::MATCH_EMAIL,
                            'messages' => array(
                                ManipleUser_Validate_User::USER_EXISTS => 'This email address is already in use',
                            ),
                        )), true),
                    ),
                ),
            ),
            'username' => array(
                'type' => 'text',
                'options' => array(
                    'label' => 'Username',
                    'required' => true,
                    'validators' => array(
                        array($this->_usersService->getUsernameValidator(), true),
                    ),
                )
            ),
            'role_id' =>
                isset($options['user'])
                ? array(
                    'type' => 'multiselect',
                    'options' => array(
                        'label' => 'Roles',
                        'multioptions' => array_column(
                            $this->_rolesTable->fetchAll(null, 'name')->toArray(),
                            'name',
                            'role_id'
                        ),
                    ),
                )
                : array(
                    'type' => 'select',
                    'options' => array(
                        'label' => 'Primary role',
                        'required' => true,
                        'multioptions' => array_column(
                            array_merge(
                                array(
                                    array(
                                        'role_id' => 0,
                                        'name' => 'User',
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

        if (empty($options['user'])) {
            unset($elements['username']);
        }

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

        $username = $this->getElement('username')
            ? $this->getValue('username')
            : $this->getValue('email');

        $user->setUsername($username);

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
            'username'   => $user->getUsername(),
            'first_name' => $user->getFirstName(),
            'last_name'  => $user->getLastName(),
            'role_id'    => array_column(
                $this->_rolesTable->fetchRolesByUserId($user->getId())->toArray(),
                'role_id'
            ),
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

        /** @var ManipleUser_Validate_UserNotExists $emailNotExistsValidator */
        $emailNotExistsValidator = $this->getElement('email')->getValidator('UserNotExists');
        $emailNotExistsValidator->setExclude($user->getEmail());

        /** @var ManipleUser_Validate_Username $usernameValidator */
        $usernameValidator = $this->getElement('username')->getValidator('Username');
        $usernameValidator->setUser($user);

        return $this;
    }

    /**
     * @return ManipleUser_Model_UserInterface|null
     */
    public function getUser()
    {
        return $this->_user;
    }
}
