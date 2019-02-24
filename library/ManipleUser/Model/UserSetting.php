<?php

/**
 * @method ManipleUser_Model_DbTable_UserSettings getTable()
 */
class ManipleUser_Model_UserSetting extends Zefram_Db_Table_Row
{
    const className = __CLASS__;

    protected $_tableClass = ManipleUser_Model_DbTable_UserSettings::className;

    /**
     * @return int
     */
    public function getUserId()
    {
        return (int) $this->user_id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return (string) $this->name;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return (string) $this->value;
    }
}
