<?php

class ManipleUser_Model_DbTable_PasswordResets extends Zefram_Db_Table
{
    const className = __CLASS__;

    protected $_name = 'password_resets';

    protected $_referenceMap = array(
        'User' => array(
            'columns'       => 'user_id',
            'refTableClass' => ManipleUser_Model_DbTable_Users::className,
            'refColumns'    => 'user_id',
        ),
    );
}
