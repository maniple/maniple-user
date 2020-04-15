<?php

/**
 * @method ManipleUser_Model_Signup createRow(array $data = array(), string $defaultSource = null)
 * @method ManipleUser_Model_Signup|null fetchRow(string|array|Zend_Db_Table_Select $where = null, string|array $order = null, int $offset = null)
 * @method ManipleUser_Model_Signup|null findRow(mixed $id)
 * @method Zend_Db_Table_Rowset_Abstract|ManipleUser_Model_Signup[] find(mixed $key, mixed ...$keys)
 * @method Zend_Db_Table_Rowset_Abstract|ManipleUser_Model_Signup[] fetchAll(string|array|Zend_Db_Table_Select $where = null, string|array $order = null, int $count = null, int $offset = null)
 */
class ManipleUser_Model_DbTable_Signups extends Zefram_Db_Table
{
    const className = __CLASS__;

    protected $_name = 'signups';

    protected $_referenceMap = array(
        'User' => array(
            'columns'       => 'user_id',
            'refTableClass' => ManipleUser_Model_DbTable_Users::className,
            'refColumns'    => 'user_id',
        ),
        'Verifier' => array(
            'columns'       => 'verified_by',
            'refTableClass' => ManipleUser_Model_DbTable_Users::className,
            'refColumns'    => 'user_id',
        ),
    );
}
