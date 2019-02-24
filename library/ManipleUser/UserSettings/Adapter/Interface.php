<?php

interface ManipleUser_UserSettings_Adapter_Interface
{
    /**
     * Retrieve user setting
     *
     * @param int $userId
     * @param string $name
     * @return mixed
     */
    public function get($userId, $name);

    /**
     * Retrieve all user settings
     *
     * @param int $userId
     * @return array
     */
    public function getAll($userId);

    /**
     * Set user setting
     *
     * @param int $userId
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function set($userId, $name, $value);

    /**
     * Remove user setting
     *
     * @param int $userId
     * @param string $name
     * @return bool
     */
    public function remove($userId, $name);

    /**
     * Return list of user IDs that have matching setting
     *
     * @param string $name
     * @param mixed $value
     * @return int[]
     */
    public function getUsersWithSetting($name, $value);
}
