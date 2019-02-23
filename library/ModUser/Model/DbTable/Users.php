<?php

/**
 * @method ModUser_Model_User findRow(mixed $id)
 * @method ModUser_Model_User createRow(array $data = array(), string $defaultSource = null)
 */
class ModUser_Model_DbTable_Users extends Zefram_Db_Table
{
    const className = __CLASS__;

    protected $_rowClass = ModUser_Model_User::className;

    protected $_name = 'users';
}
