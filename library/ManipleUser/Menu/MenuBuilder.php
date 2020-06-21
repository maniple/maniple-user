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

        $menu->addPage($new = new Maniple_Menu_Page(array(
            'label'   => 'New',
            'id'      => 'maniple.secondary.new',
            'liClass' => 'dropdown',
            'ulClass' => 'dropdown-menu',
            'partial' => 'maniple-user/menu/page-create.twig',
        )));

        if ($this->_securityContext->isAllowed('manage_users')) {
            $new->addPage(array(
                'label' => 'User',
                'route' => 'maniple-user.users.create',
                'order' => 1000,
            ));
        }

        $menu->addPage(new Maniple_Menu_Page(array(
            'id'      => 'maniple.secondary.user',
            'liClass' => 'dropdown',
            'ulClass' => 'dropdown-menu',
            'partial' => 'maniple-user/menu/user-page.twig',
            'order'   => 1000,
            'pages'   => array(
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

    public function postBuildMenu(Maniple_Menu_Menu $menu)
    {
        if ($menu->getName() !== 'maniple.secondary') {
            return;
        }

        if (($new = $menu->findOneBy('id', 'maniple.secondary.new')) === null) {
            return;
        }

        if (empty($new->getPages())) {
            $new->getParent()->removePage($new);
            /** @noinspection PhpUnhandledExceptionInspection */
            $new->setParent(null);
        }
    }
}
