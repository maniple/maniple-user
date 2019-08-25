<?php

class ManipleUser_Form_Factory_User
{
    /**
     * @var ManipleUser_Model_UserMapperInterface
     */
    protected $_userManager;

    /**
     * @var ManipleUser_UsersService
     */
    protected $_usersService;

    /**
     * @var ManipleUser_Model_DbTable_Roles
     */
    protected $_rolesTable;

    public function __construct(
        ManipleUser_UsersService $usersService,
        ManipleUser_Model_UserMapperInterface $userManager,
        ManipleUser_Model_DbTable_Roles $rolesTable
    ) {
        $this->_usersService = $usersService;
        $this->_userManager = $userManager;
        $this->_rolesTable = $rolesTable;
    }

    /**
     * @param array $options OPTIONAL
     * @return ManipleUser_Form_User
     */
    public function createForm(array $options = array())
    {
        return new ManipleUser_Form_User(
            $this->_usersService,
            $this->_userManager,
            $this->_rolesTable,
            $options
        );
    }
}
