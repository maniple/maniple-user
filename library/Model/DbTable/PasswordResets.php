<?php

class ModUser_Model_DbTable_PasswordResets extends Zefram_Db_Table
{
    const className = __CLASS__;

    protected $_name = 'password_resets';

    protected $_referenceMap = array(
        'User' => array(
            'columns'       => 'user_id',
            'refTableClass' => 'ModUser_Model_DbTable_Users',
            'refColumns'    => 'user_id',
        ),
    );
}
