<?php

abstract class ManipleUser_Menu_MenuBuilderFactory
{
    public static function factory(Maniple_Di_Container $container)
    {
        /** @var ManipleUser_Menu_MenuBuilder $menuBuilder */
        $menuBuilder = $container->getInjector()->newInstance(ManipleUser_Menu_MenuBuilder::className);
        $options = $container[ManipleUser_Bootstrap::className]->getOption('menuBuilder');
        if ($options) {
            $menuBuilder->setOptions($options);
        }
        return $menuBuilder;
    }
}
