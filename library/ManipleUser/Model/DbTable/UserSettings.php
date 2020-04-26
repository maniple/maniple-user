<?php

/**
 * @method ManipleUser_Model_UserSetting createRow(array $data = array(), string $defaultSource = null)
 * @method ManipleUser_Model_UserSetting|null fetchRow(string|array|Zend_Db_Table_Select $where = null, string|array $order = null, int $offset = null)
 * @method ManipleUser_Model_UserSetting|null findRow(mixed $id)
 * @method Zend_Db_Table_Rowset|ManipleUser_Model_UserSetting[] find(mixed $key, mixed ...$keys)
 * @method Zend_Db_Table_Rowset|ManipleUser_Model_UserSetting[] fetchAll(string|array|Zend_Db_Table_Select $where = null, string|array $order = null, int $count = null, int $offset = null)
 */
class ManipleUser_Model_DbTable_UserSettings extends Zefram_Db_Table
{
    const className = __CLASS__;

    protected $_rowClass = ManipleUser_Model_UserSetting::className;

    protected $_name = 'user_settings';

    protected $_primary = array('user_id', 'name');

    protected $_sequence = false;

    protected $_referenceMap = array();
}
