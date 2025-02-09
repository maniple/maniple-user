<?php

return array(
    'user.auth.login' => array(
        'route'    => 'login',
        'defaults' => array(
            'module'     => 'maniple-user',
            'controller' => 'auth',
            'action'     => 'login',
        ),
    ),
    'user.auth.logout' => array(
        'route'    => 'logout',
        'defaults' => array(
            'module'     => 'maniple-user',
            'controller' => 'auth',
            'action'     => 'logout',
        ),
    ),
    'user.auth.impersonate' => array(
        'route'    => 'impersonate/:user_id',
        'defaults' => array(
            'module'     => 'maniple-user',
            'controller' => 'auth',
            'action'     => 'impersonate',
        ),
        'reqs'     => array(
            'user_id' => '^\\d+',
        ),
    ),
    'user.password.forgot' => array(
        'route'    => 'forgot-password',
        'defaults' => array(
            'module'     => 'maniple-user',
            'controller' => 'password',
            'action'     => 'forgot',
        ),
    ),
    'user.password.forgot_complete' => array(
        'route'    => 'forgot-password/complete',
        'defaults' => array(
            'module'     => 'maniple-user',
            'controller' => 'password',
            'action'     => 'forgot-complete',
        ),
    ),
    'user.password.reset' => array(
        'route'    => 'reset-password/:reset_id',
        'defaults' => array(
            'module'     => 'maniple-user',
            'controller' => 'password',
            'action'     => 'reset',
        ),
        'reqs'     => array(
            'reset_id' => '^\\S+',
        ),
    ),
    'user.password.reset_complete' => array(
        'route'    => 'reset-password/complete',
        'defaults' => array(
            'module'     => 'maniple-user',
            'controller' => 'password',
            'action'     => 'reset-complete',
        ),
    ),
    'user.password.update' => array(
        'route'    => 'password',
        'defaults' => array(
            'module'     => 'maniple-user',
            'controller' => 'password',
            'action'     => 'update',
        ),
    ),

    'maniple-user.signup.create' => array(
        'route'    => 'signup',
        'defaults' => array(
            'module'     => 'maniple-user',
            'controller' => 'registration',
            'action'     => 'create',
        ),
    ),
    'maniple-user.signup.confirm' => array(
        'route'    => 'signup/confirm/:reg_id',
        'defaults' => array(
            'module'     => 'maniple-user',
            'controller' => 'registration',
            'action'     => 'confirm',
        ),
        'reqs'     => array(
            'reg_token' => '^[-_a-zA-Z0-9]+$',
        ),
    ),
    'maniple-user.signup.complete' => array(
        'route'    => 'signup/complete',
        'defaults' => array(
            'module'     => 'maniple-user',
            'controller' => 'registration',
            'action'     => 'complete',
        ),
    ),

    'user.registration.create' => array(
        'route'    => 'register',
        'defaults' => array(
            'module'     => 'maniple-user',
            'controller' => 'registration',
            'action'     => 'create',
        ),
    ),
    'user.registration.confirm' => array(
        'route'    => 'registration/confirm/:reg_id',
        'defaults' => array(
            'module'     => 'maniple-user',
            'controller' => 'registration',
            'action'     => 'confirm',
        ),
        'reqs'     => array(
            'reg_token' => '^[-_a-zA-Z0-9]+$',
        ),
    ),
    'user.registration.complete' => array(
        'route'    => 'registration/complete',
        'defaults' => array(
            'module'     => 'maniple-user',
            'controller' => 'registration',
            'action'     => 'complete',
        ),
    ),

    'maniple-user.users.index' => array(
        'route'    => 'users',
        'defaults' => array(
            'module'     => 'maniple-user',
            'controller' => 'users',
            'action'     => 'index',
        ),
    ),
    'maniple-user.users.create' => array(
        'route'    => 'users/create',
        'defaults' => array(
            'module'     => 'maniple-user',
            'controller' => 'users',
            'action'     => 'create',
        ),
    ),
    'maniple-user.users.edit' => array(
        'route'    => 'users/:user_id/edit',
        'defaults' => array(
            'module'     => 'maniple-user',
            'controller' => 'users',
            'action'     => 'edit',
        ),
        'reqs' => array(
            'user_id' => '^\d+',
        ),
    ),
    'maniple-user.users.search' => array(
        'route'    => 'users/search',
        'defaults' => array(
            'module'     => 'maniple-user',
            'controller' => 'users',
            'action'     => 'search',
        ),
    ),
);
