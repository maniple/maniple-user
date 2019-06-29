<?php

class ManipleUser_Model_DbTable_Registrations extends Zefram_Db_Table
{
    const className = __CLASS__;

    protected $_name = 'registrations';

    protected $_referenceMap = array(
        'User' => array(
            'columns'       => 'user_id',
            'refTableClass' => ManipleUser_Model_DbTable_Users::className,
            'refColumns'    => 'user_id',
        ),
        'Verifier' => array(
            'columns'       => 'verified_by',
            'refTableClass' => ManipleUser_Model_DbTable_Users::className,
            'refColumns'    => 'user_id',
        ),
    );
}
