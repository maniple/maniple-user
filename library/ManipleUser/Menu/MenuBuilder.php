<?php

class ManipleUser_Menu_MenuBuilder implements Maniple_Menu_MenuBuilderInterface
{
    const className = __CLASS__;

    const PAGE_SEPARATOR_ORDER = 1000;

    /**
     * @Inject
     * @var ManipleUser_Service_Security
     */
    protected $_securityContext;

    /**
     * @param array $options
     * @return $this
     */
    public function setOptions(array $options)
    {
        foreach ($options as $key => $value) {
            $method = 'set' . $key;
            if (method_exists($this, $method)) {
                $this->{$method}($value);
            }
        }
        return $this;
    }

    /**
     * @param Maniple_Menu_Menu $menu
     */
    public function buildMenu(Maniple_Menu_Menu $menu)
    {
        if ($menu->getName() === 'maniple.primary') {
            return $this->_buildPrimaryMenu($menu);
        }

        if ($menu->getName() === 'maniple.secondary') {
            return $this->_buildSecondaryMenu($menu);
        }
    }

    protected function _buildPrimaryMenu(Maniple_Menu_Menu $menu)
    {
        if (!$this->_securityContext->isAllowed('manage_users')) {
            return;
        }

        $menu->addPage(array(
            'label' => 'Users',
            'route' => 'maniple-user.users.index',
            'type'  => 'mvc',
        ));
    }

    protected function _buildSecondaryMenu(Maniple_Menu_Menu $menu)
    {
        if (!$this->_securityContext->isAuthenticated()) {
            return;
        }

        $menu->addPage(new ManipleUser_Menu_Page_User(array(
            'pages' => array(
                array(
                    'label' => 'Change password',
                    'route' => 'user.password.update',
                    'order' => 0,
                ),
                new Maniple_Menu_Page_Separator(array(
                    'order' => self::PAGE_SEPARATOR_ORDER,
                )),
                array(
                    'label' => 'Log out',
                    'route' => 'user.auth.logout',
                    'order' => self::PAGE_SEPARATOR_ORDER + 1,
                ),
            ),
        )));
    }
}
