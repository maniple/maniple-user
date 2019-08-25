<?php return array(
    'user.model.userMapper' => array(
        'class' => 'ManipleUser_Model_UserMapper',
        'args' => array(
            'db' => 'resource:Zefram_Db',
        ),
    ),

    'user.sessionManager' => array(
        'callback' => 'ManipleUser_Service_Security::factory',
    ),

    'user.userManager' => array(
        'class' => 'ManipleUser_Model_UserManager',
        'options' => array(
            'userMapper' => 'resource:user.model.userMapper',
        ),
    ),

    'ManipleUser_Signup_SignupManager' => array(
        'class' => 'ManipleUser_Signup_SignupManager',
        'args' => array(
            'resource:SharedEventManager',
        ),
    ),

    'ManipleUser.UserSettings' => array(
        'class' => 'ManipleUser_UserSettings_Service',
        'args'  => array(
            'resource:user.sessionManager',
            'resource:ManipleUser.UserSettingsAdapter',
        ),
    ),

    'ManipleUser.UserSettingsAdapter' => array(
        'class' => 'ManipleUser_UserSettings_Adapter_DbTable',
        'args' => array(
            'resource:Zefram_Db',
        ),
    ),

    'ManipleUser_UsersService' => array(
        'class' => 'ManipleUser_UsersService',
    ),

    'ManipleUser_PasswordService' => array(
        'class' => 'ManipleUser_PasswordService',
    ),

    'ManipleUser_Form_Factory_User' => array(
        'class' => 'ManipleUser_Form_Factory_User',
        'args' => array(
            'resource:ManipleUser_UsersService',
            'resource:user.model.userMapper',
            'resource:ManipleUser_Model_DbTable_Roles',
        ),
    ),

    'ManipleUser_Model_DbTable_Roles' => array(
        'callback' => 'Maniple_Model_TableProvider::getTable',
        'args' => 'ManipleUser_Model_DbTable_Roles',
    ),
);
