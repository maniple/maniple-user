<?php

class ManipleUser_Model_DbTable_Roles extends Zefram_Db_Table
{
    const className = __CLASS__;

    protected $_name = 'roles';

    public function fetchRoles()
    {
        $roles = array();

        foreach ($this->fetchAllAsArray(null, 'name ASC') as $role) {
            $role['perms'] = (array) Zefram_Json::decode($role['perms']); // <-- this is obsolete
            $roles[$role['role_id']] = $role;
        }

        if ($roles) {
            $select = new Zefram_Db_Select($this->_db);
            $select->from(
                array('perms' => $this->_getTableFromString(ManipleUser_Model_DbTable_Perms::className)),
                'perm_name'
            );
            $select->join(
                array('role_perms' => $this->_getTableFromString(ManipleUser_Model_DbTable_RolePerms::className)),
                array(
                    'role_perms.role_id IN (?)' => array_keys($roles),
                    'role_perms.perm_id = perms.perm_id',
                ),
                'role_id'
            );
            foreach ($select->query()->fetchAll() as $row) {
                $perm_name = $row['perm_name'];
                $role_id = $row['role_id'];
                $roles[$role_id]['perms'][$perm_name] = $perm_name;
            }
        }

        return $roles;
    }

    public function fetchRolesWithPrivilege($resource, $privilege = null)
    {
        if (null === $privilege) {
            $privilege = $resource;
            $resource = 'privileges';
        }

        // TODO cache all roles
        $roles = array();

        foreach ($this->fetchRoles() as $role) {
            if (isset($role['perms'][$resource]) &&
                in_array($privilege, $role['perms'][$resource], true)
            ) {
                $roles[$role['role_id']] = $role;
            }
        }

        return $roles;
    }
}
