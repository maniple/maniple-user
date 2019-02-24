<?php return array(
    'user.model.userMapper' => array(
        'class' => 'ModUser_Model_UserMapper',
        'args' => array(
            'db' => 'resource:ZeframDb',
        ),
    ),

    'user.sessionManager' => array(
        'callback' => 'ModUser_Service_Security::factory',
    ),

    'user.userManager' => array(
        'class' => 'ModUser_Model_UserManager',
        'options' => array(
            'userMapper' => 'resource:user.model.userMapper',
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
);
