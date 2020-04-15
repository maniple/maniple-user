<?php

/**
 * @method ManipleUser_Model_DbTable_Signups getTable()
 * @property ManipleUser_Model_User|null $User
 * @property ManipleUser_Model_User|null $Verifier
 * @property string $email
 * @property string $status
 * @property int $confirmed_at
 * @property string $ip_addr
 */
class ManipleUser_Model_Signup extends Zefram_Db_Table_Row
{
    const className = __CLASS__;

    protected $_tableClass = ManipleUser_Model_DbTable_Signups::className;

    /**
     * @return int
     */
    public function getId()
    {
        /** @noinspection PhpUndefinedFieldInspection */
        return (int) $this->signup_id;
    }
}
