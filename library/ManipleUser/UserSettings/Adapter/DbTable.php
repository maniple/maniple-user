<?php

class ManipleUser_UserSettings_Adapter_DbTable implements ManipleUser_UserSettings_Adapter_Interface
{
    /**
     * @var Zefram_Db
     */
    protected $_db;

    /**
     * @var array
     */
    protected $_settingRows = array();

    /**
     * @param Zefram_Db $db
     */
    public function __construct(Zefram_Db $db)
    {
        $this->_db = $db;
    }

    /**
     * @param int $userId
     * @return array
     */
    public function getAll($userId)
    {
        $settings = array();

        foreach ($this->_load($userId) as $setting) {
            $settings[$setting->getName()] = $setting->getValue();
        }

        return $settings;
    }

    /**
     * @param int $userId
     * @param string $name
     * @return mixed
     */
    public function get($userId, $name)
    {
        $settings = $this->_load($userId);
        return isset($settings[$name]) ? $settings[$name]->getValue() : null;
    }

    /**
     * @param int $userId
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function set($userId, $name, $value)
    {
        $settings = $this->_load($userId);
        $value = strval($value);

        if (!isset($settings[$name])) {
            $settings[$name] = $this->_getUserSettingsTable()->createRow(array(
                'user_id'   => (int) $userId,
                'name'      => (string) $name,
                'value'     => $value,
                'saved_at'  => time(),
            ));

        } elseif ($settings[$name]->getValue() !== $value) {
            $settings[$name]->setFromArray(array(
                'value'     => $value,
                'saved_at'  => time(),
            ));
        }

        if ($settings[$name]->isModified()) {
            $settings[$name]->save();
        }

        return $this;
    }

    /**
     * @param int $userId
     * @param string $name
     * @return bool
     */
    public function remove($userId, $name)
    {
        $userId = intval($userId);
        $settings = $this->_load($userId);

        if (isset($settings[$name])) {
            $settings[$name]->delete();

            unset($settings[$name]);

            return true;
        }

        return false;
    }

    /**
     * Load settings of a given user and store them as active records
     *
     * @param int $userId
     * @return ManipleUser_Model_UserSetting[]
     */
    protected function &_load($userId)
    {
        $userId = intval($userId);

        if (!isset($this->_settingRows[$userId])) {
            $settings = array();
            $where = array('user_id = ?' => $userId);
            foreach ($this->_getUserSettingsTable()->fetchAll($where) as $row) {
                /** @var ManipleUser_Model_UserSetting $row */
                $settings[$row->getName()] = $row;
            }
            $this->_settingRows[$userId] = $settings;
        }

        return $this->_settingRows[$userId];
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return int[]
     */
    public function getUsersWithSetting($name, $value)
    {
        $select = $this->_db->select();
        $select->from($this->_getUserSettingsTable(), array('user_id'));
        $select->where(array(
            'name = ?' => (string) $name,
            'value = ?' => (string) $value,
        ));

        return array_map('intval', array_column($select->query()->fetchAll(), 'user_id'));
    }

    /**
     * @return ManipleUser_Model_DbTable_UserSettings
     */
    protected function _getUserSettingsTable()
    {
        /** @var ManipleUser_Model_DbTable_UserSettings $table */
        $table = $this->_db->getTable(ManipleUser_Model_DbTable_UserSettings::className);
        return $table;
    }
}
