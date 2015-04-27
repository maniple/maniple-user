<?php

class ModUser_Service_Security extends Maniple_Security_ContextAbstract
{
    public function isAllowed($permission)
    {
        // TODO generalize this
        switch ($permission) {
            case 'enroll':
                return $this->isAuthenticated();

            case 'manage':
                return $this->isSuperUser();

            default:
                return false;
        }
    }
}
