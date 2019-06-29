<?php

/**
 * @method ManipleUser_Model_User findRow(mixed $id)
 * @method ManipleUser_Model_User createRow(array $data = array(), string $defaultSource = null)
 */
class ManipleUser_Model_DbTable_Users extends Zefram_Db_Table
{
    const className = __CLASS__;

    protected $_rowClass = ManipleUser_Model_User::className;

    protected $_name = 'users';
}
