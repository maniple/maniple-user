<?php return array(
    'ManipleUser_Model_UserMapper' => array(
        'class' => 'ManipleUser_Model_UserMapper',
    ),
    'ManipleUser_Model_UserMapperInterface' => 'resource:ManipleUser_Model_UserMapper',
    'user.model.userMapper' => 'resource:ManipleUser_Model_UserMapperInterface',

    'ManipleUser_Service_Security' => array(
        'callback' => 'ManipleUser_Service_Security::factory',
    ),
    'user.sessionManager' => 'resource:ManipleUser_Service_Security',

    'ManipleUser_Service_UserManager' => array(
        'class' => 'ManipleUser_Service_UserManager',
        'options' => array(
            'userMapper' => 'resource:user.model.userMapper',
        ),
    ),
    'ManipleUser_Service_UserManagerInterface' => 'resource:ManipleUser_Service_UserManager',
    'user.userManager' => 'resource:ManipleUser_Service_UserManagerInterface',

    'ManipleUser_Service_Signup' => array(
        'callback' => 'ManipleUser_Service_Signup::factory',
    ),

    'ManipleUser_UserSettings_Service' => array(
        'class' => 'ManipleUser_UserSettings_Service',
        'args'  => array(
            'resource:user.sessionManager',
            'resource:ManipleUser.UserSettingsAdapter',
        ),
    ),
    'ManipleUser.UserSettings' => 'resource:ManipleUser_UserSettings_Service',

    'ManipleUser.UserSettingsAdapter' => array(
        'class' => 'ManipleUser_UserSettings_Adapter_DbTable',
        'args' => array(
            'resource:Zefram_Db',
        ),
    ),

    'ManipleUser_UsersService' => array(
        'class' => 'ManipleUser_UsersService',
    ),

    'ManipleUser_Service_Password' => array(
        'class' => 'ManipleUser_Service_Password',
    ),

    'ManipleUser_Service_Username' => array(
        'class' => 'ManipleUser_Service_Username',
    ),

    'ManipleUser_Form_Factory_User' => array(
        'class' => 'ManipleUser_Form_Factory_User',
    ),

    'ManipleUser_Model_DbTable_Roles' => array(
        'callback' => 'Maniple_Model_TableProvider::getTable',
        'args' => 'ManipleUser_Model_DbTable_Roles',
    ),

    'ManipleUser_Menu_MenuBuilder' => array(
        'callback' => 'ManipleUser_Menu_MenuBuilderFactory::factory',
    ),
);
