<?php

class ModUser_Model_DbTable_RolePerms extends Zefram_Db_Table
{
    protected $_name = 'role_perms';

    protected $_primary = array('role_id', 'perm_id');
}
