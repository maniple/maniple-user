<?php

class ManipleUser_View_Helper_UserSignupForm extends Maniple_View_Helper_Abstract
    implements Zefram_Twig_SafeInterface
{
    /**
     * @var ManipleUser_Service_Signup
     * @Inject
     */
    protected $_signupService;

    public function userSignupForm()
    {
        $form = $this->_signupService->createSignupForm();
        $form->setMethod('POST');
        $form->setAction($this->view->url('maniple-user.signup.create'));
        return $form;
    }

    public function getSafe()
    {
        return array('html');
    }
}
