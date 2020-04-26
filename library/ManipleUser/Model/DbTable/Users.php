<?php

/**
 * @method ManipleUser_Model_User createRow(array $data = array(), string $defaultSource = null)
 * @method ManipleUser_Model_User|null fetchRow(string|array|Zend_Db_Table_Select $where = null, string|array $order = null, int $offset = null)
 * @method ManipleUser_Model_User|null findRow(mixed $id)
 * @method Zend_Db_Table_Rowset|ManipleUser_Model_User[] find(mixed $key, mixed ...$keys)
 * @method Zend_Db_Table_Rowset|ManipleUser_Model_User[] fetchAll(string|array|Zend_Db_Table_Select $where = null, string|array $order = null, int $count = null, int $offset = null)
 */
class ManipleUser_Model_DbTable_Users extends Zefram_Db_Table
{
    const className = __CLASS__;

    protected $_rowClass = ManipleUser_Model_User::className;

    protected $_name = 'users';
}
