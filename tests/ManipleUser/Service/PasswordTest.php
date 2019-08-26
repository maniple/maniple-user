<?php

class ManipleUser_Service_PasswordTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var ManipleUser_Service_Password
     */
    protected $_passwordService;

    protected function setUp()
    {
        $this->_passwordService = new ManipleUser_Service_Password();
    }

    public function testGeneratePassword()
    {
        $password = $this->_passwordService->generatePassword(16);

        $this->assertEquals(16, strlen($password));
    }
}
