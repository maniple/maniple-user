<?php

class ModUser_Controller_Action extends Zefram_Controller_Action
{
    public function getSecurity()
    {
        return $this->getResource('user.sessionManager');    
    }

    public function getSecurityContext()
    {
        return $this->getSecurity();
    }

    public function getUserManager()
    {
        return $this->getResource('user.userManager');
    }

    /**
     * @return Zefram_Db_Table_FactoryInterface
     */
    public function getTableManager()
    {
        /** @var $db Zefram_Db */
        $db = $this->getResource('ZeframDb');
        return $db->getTableFactory();
    }

    public function preDispatch()
    {
        $config = $this->getResource('config');
        if (!is_array($config)) {
            $config = $config->toArray(); // TODO unify Config resource
        }
        if (isset($config['ModUser']['scriptPath'])) {
            $scriptPath = $config['ModUser']['scriptPath'];

            if (strpos($scriptPath, ':locale') !== false) {
                $scriptPath = str_replace(':locale', $this->getResource('locale'), $scriptPath);
            }

            $this->getResource('view')->addScriptPath($scriptPath);
        }
    }
}
