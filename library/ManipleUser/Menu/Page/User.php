<?php

class ManipleUser_Menu_Page_User extends Maniple_Menu_Page
{
    protected $_properties = array(
        'liClass' => 'dropdown',
        'ulClass' => 'dropdown-menu',
    );

    /**
     * @return string
     */
    public function getPartial()
    {
        return 'maniple-user/menu/user-page.twig';
    }
}
