<?php return array(

    // user.auth {{{
    'user.auth.login' => array(
        'route' => 'login',
        'defaults' => array(
            'module'     => 'mod-user',
            'controller' => 'auth',
            'action'     => 'login',
        ),
    ),
    'user.auth.logout' => array(
        'route' => 'logout',
        'defaults' => array(
            'module'     => 'mod-user',
            'controller' => 'auth',
            'action'     => 'logout',
        ),
    ),
    'user.auth.impersonate' => array(
        'route' => 'impersonate/:user_id',
        'defaults' => array(
            'module'     => 'mod-user',
            'controller' => 'auth',
            'action'     => 'impersonate',
        ),
        'reqs' => array(
            'user_id' => '^\d+',
        ),
    ),
    // }}}

    // user.password {{{
    'user.password.forgot' => array(
        'route' => 'forgot-password',
        'defaults' => array(
            'module'     => 'mod-user',
            'controller' => 'password',
            'action'     => 'forgot',
        ),
    ),
    'user.password.forgot_complete' => array(
        'route' => 'forgot-password/complete',
        'defaults' => array(
            'module'     => 'mod-user',
            'controller' => 'password',
            'action'     => 'forgot-complete',
        ),
    ),
    'user.password.reset' => array(
        'route' => 'reset-password/:reset_id',
        'defaults' => array(
            'module'     => 'mod-user',
            'controller' => 'password',
            'action'     => 'reset',
        ),
        'reqs' => array(
            'reset_id' => '^\S+',
        ),
    ),
    'user.password.reset_complete' => array(
        'route' => 'reset-password/complete',
        'defaults' => array(
            'module'     => 'mod-user',
            'controller' => 'password',
            'action'     => 'reset-complete',
        ),
    ),
    'user.password.update' => array(
        'route' => 'password',
        'defaults' => array(
            'module'     => 'mod-user',
            'controller' => 'password',
            'action'     => 'update',
        ),    
    ),
    // }}}

    // user.registration {{{
    'user.registration.create' => array(
        'route' => 'register',
        'defaults' => array(
            'module'     => 'mod-user',
            'controller' => 'registration',
            'action'     => 'create',
        ),
    ),
    'user.registration.confirm' => array(
        'route' => 'registration/confirm/:reg_id',
        'defaults' => array(
            'module'     => 'mod-user',
            'controller' => 'registration',
            'action'     => 'confirm',
        ),
        'reqs' => array(
            'reg_token' => '^[-_a-zA-Z0-9]+$',
        ),
    ),
    'user.registration.complete' => array(
        'route' => 'registration/complete',
        'defaults' => array(
            'module'     => 'mod-user',
            'controller' => 'registration',
            'action'     => 'complete',
        ),
    ),
    // }}}
);
