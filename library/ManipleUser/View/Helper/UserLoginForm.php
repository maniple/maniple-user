<?php

class ManipleUser_View_Helper_UserLoginForm extends Maniple_View_Helper_Abstract
    implements Zefram_Twig_SafeInterface
{
    /**
     * @var ManipleUser_Bootstrap
     * @Inject
     */
    protected $_bootstrap;

    public function userLoginForm()
    {
        $loginFormClass = $this->_bootstrap->getOption('loginFormClass');

        if (!$loginFormClass) {
            $loginFormClass = ManipleUser_Form_Login::className;
        }

        $form = new $loginFormClass;
        $form->setMethod('POST');
        $form->setAction($this->view->url('user.auth.login'));
        return $form;
    }

    public function getSafe()
    {
        return array('html');
    }
}
