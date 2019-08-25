<?php

class ManipleUser_UsersService
{
    /**
     * @Inject
     * @var Zefram_Db
     */
    protected $_db;

    /**
     * @Inject('user.sessionManager')
     * @var Maniple_Security_ContextInterface
     */
    protected $_securityContext;

    /**
     * @Inject('user.model.userMapper')
     * @var ManipleUser_Model_UserMapperInterface
     */
    protected $_userRepository;

    /**
     * Options:
     * - string query
     * - string|array sort
     * - bool active
     * - bool withRoles
     *
     * @param array $options OPTIONAL
     * @return Zend_Paginator
     * @throws Zend_Paginator_Exception
     */
    public function getUsers(array $options = array())
    {
        $query = isset($options['query']) ? $options['query'] : null;

        $forbidden = array('%', '_', '[', ']', '^', "\x00");
        $query = str_replace($forbidden, ' ', strval($query));
        $query = preg_replace('/\s+/', ' ', $query);
        $query = trim($query);

        $select = $this->_db->select();
        $select->from(
            array('users' => $this->_db->getTable(ManipleUser_Model_DbTable_Users::className)),
            array(
                'id' => new Zend_Db_Expr('user_id'),
                'user_id',
                'first_name',
                'last_name',
                'username',
                'email' => new Zend_Db_Expr(
                    $this->_securityContext->isAllowed('manage_users') ? 'email' : 'NULL'
                ),
                'is_active',
                'is_locked',
            )
        );

        if (isset($options['active'])) {
            $select->where($options['active'] ? 'is_active <> 0' : 'is_active = 0');
        }

        $sort = isset($options['sort']) ? $options['sort'] : null;
        if (!$sort) {
            $sort = array('last_name', 'first_name');
        }
        $select->order($sort);

        if (strlen($query)) {
            // There is no support for nested groups in Zend_Db_Select,
            // https://stackoverflow.com/a/1179410/281087
            $select->where(new Zend_Db_Expr(implode(
                ' OR ',
                array(
                    $this->_db->quoteInto('first_name LIKE ?', '%' . $query . '%'),
                    $this->_db->quoteInto('last_name LIKE ?', '%' . $query . '%'),
                    $this->_db->quoteInto('CONCAT(first_name, \' \', last_name) LIKE ?', '%' . $query . '%'),
                    $this->_db->quoteInto('email LIKE ?', '%' . $query . '%'),
                )
            )));
        }

        $paginator = Zend_Paginator::factory($select);
        $paginator->setCurrentPageNumber(1);
        $paginator->setItemCountPerPage(25);
        $paginator->setFilter($filter = new Zend_Filter());

        if (!empty($options['withRoles'])) {
            $filter->addFilter(new ManipleUser_Filter_Db_WithRoles($this->_db, $this->_securityContext));
        }

        return $paginator;
    }

    /**
     * @return ManipleUser_Validate_Username
     */
    public function getUsernameValidator()
    {
        return new ManipleUser_Validate_Username($this->_userRepository);
    }
}
