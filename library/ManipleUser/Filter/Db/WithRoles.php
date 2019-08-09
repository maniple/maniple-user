<?php

class ManipleUser_Filter_Db_WithRoles implements Zend_Filter_Interface
{
    /**
     * @var Zefram_Db
     */
    protected $_db;

    /**
     * @var Maniple_Security_ContextInterface
     */
    protected $_securityContext;

    public function __construct(Zefram_Db $db, Maniple_Security_ContextInterface $securityContext)
    {
        $this->_db = $db;
        $this->_securityContext = $securityContext;
    }

    /**
     * @param array $users
     * @return array
     */
    public function filter($users)
    {
        $users = array_filter((array) $users, 'is_array');

        $userRoles = array();

        foreach ($users as &$user) {
            $userId = isset($user['user_id']) ? (int) $user['user_id'] : 0;
            $userRoles[$userId] = array();
        }

        if (count($userRoles)) {
            $select = $this->_db->select();
            $select->from(
                array('roles' => $this->_db->getTable(ManipleUser_Model_DbTable_Roles::className))
            );
            $select->join(
                array('user_roles' => $this->_db->getTable(ManipleUser_Model_DbTable_UserRoles::className)),
                'user_roles.role_id = roles.role_id',
                array(
                    'user_id',
                )
            );
            $select->where('user_roles.user_id IN (?)', array_keys($userRoles));

            foreach ($select->query(Zend_Db::FETCH_ASSOC)->fetchAll() as $row) {
                $userRoles[$row['user_id']][] = $row;
            }

            foreach ($users as &$user) {
                $userId = isset($user['user_id']) ? (int) $user['user_id'] : 0;
                $roles = $userRoles[$userId];

                if ($this->_securityContext->isSuperUser($userId)) {
                    $roles = array_merge(array(
                        array(
                            'role_id' => -1,
                            'name'    => 'Superuser',
                        )
                    ), $roles);
                }

                $user['roles'] = $roles;
            }
            unset($user);
        }

        return $users;
    }
}
