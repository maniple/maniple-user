<?php

class ManipleUser_Form_Factory_User
{
    /**
     * @Inject
     * @var ManipleUser_Model_UserMapperInterface
     */
    protected $_userRepository;

    /**
     * @Inject
     * @var ManipleUser_UsersService
     */
    protected $_usersService;

    /**
     * @Inject
     * @var ManipleUser_Service_Username
     */
    protected $_usernameService;

    /**
     * @Inject
     * @var ManipleUser_Model_DbTable_Roles
     */
    protected $_rolesTable;

    /**
     * @param array $options OPTIONAL
     * @return ManipleUser_Form_User
     */
    public function createForm(array $options = array())
    {
        return new ManipleUser_Form_User(
            $this->_usersService,
            $this->_userRepository,
            $this->_usernameService,
            $this->_rolesTable,
            $options
        );
    }
}
