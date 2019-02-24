<?php

/**
 * @method ManipleUser_Model_UserSetting findRow(mixed $id)
 * @method ManipleUser_Model_UserSetting createRow(array $data = array(), string $defaultSource = null)
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
