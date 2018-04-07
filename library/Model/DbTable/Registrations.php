<?php

class ModUser_Model_DbTable_Registrations extends Zefram_Db_Table
{
    const className = __CLASS__;

    protected $_name = 'registrations';

    protected $_referenceMap = array(
        'User' => array(
            'columns'       => 'user_id',
            'refTableClass' => 'ModUser_Model_DbTable_Users',
            'refColumns'    => 'user_id',
        ),
        'Verifier' => array(
            'columns'       => 'verified_by',
            'refTableClass' => 'ModUser_Model_DbTable_Users',
            'refColumns'    => 'user_id',
        ),
    );
}
