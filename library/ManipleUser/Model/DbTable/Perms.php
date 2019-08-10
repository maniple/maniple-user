<?php

class ManipleUser_Model_DbTable_Perms extends Zefram_Db_Table
{
    const className = __CLASS__;

    protected $_name = 'perms';

    /**
     * @param int $userId
     * @return Zefram_Db_Table_Rowset
     */
    public function fetchPermsByUserId($userId)
    {
        $select = $this->_db->select();
        $select->from(
            array('user_roles' => $this->_getTableFromString(ManipleUser_Model_DbTable_UserRoles::className)->getName()),
            array()
        );
        $select->join(
            array('role_perms' => $this->_getTableFromString(ManipleUser_Model_DbTable_RolePerms::className)->getName()),
            'role_perms.role_id = user_roles.role_id',
            array()
        );
        $select->join(
            array('perms' => $this->getName()),
            'perms.perm_id = role_perms.perm_id',
            Zend_Db_Select::SQL_WILDCARD
        );
        $select->where('user_roles.user_id = ?', (int) $userId);

        return $this->fetchAll($select);
    }
}
