<?php

class ModUser_Model_DbTable_UserRoles extends Zefram_Db_Table
{
    const className = __CLASS__;

    protected $_name = 'user_roles';

    protected $_referenceMap = array(
        'User' => array(
            'columns'       => 'user_id',
            'refTableClass' => ModUser_Model_DbTable_Users::className,
            'refColumns'    => 'user_id',
        ),
        'Role' => array(
            'columns'       => 'role_id',
            'refTableClass' => ModUser_Model_DbTable_Roles::className,
            'refColumns'    => 'role_id',
        ),
    );
}
