<?php return array(
    'user.model.userMapper' => array(
        'class' => 'ModUser_Model_UserMapper',
        'args' => array(
            'db' => 'resource:ZeframDb',
        ),
    ),

    'user.sessionManager' => array(
        'class' => 'ModUser_Service_Security',
    ),

    'user.userManager' => array(
        'class' => 'ModUser_Model_UserManager',
        'options' => array(
            'userMapper' => 'resource:user.model.userMapper',
        ),
    ),
);