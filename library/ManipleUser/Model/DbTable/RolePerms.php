<?php

class ManipleUser_Model_DbTable_RolePerms extends Zefram_Db_Table
{
    const className = __CLASS__;

    protected $_name = 'role_perms';

    protected $_primary = array('role_id', 'perm_id');
}
