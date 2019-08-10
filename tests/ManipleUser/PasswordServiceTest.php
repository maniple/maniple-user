<?php

class ManipleUser_PasswordServiceTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var ManipleUser_PasswordService
     */
    protected $_passwordService;

    protected function setUp()
    {
        $this->_passwordService = new ManipleUser_PasswordService();
    }

    public function testGeneratePassword()
    {
        $password = $this->_passwordService->generatePassword(16);

        $this->assertEquals(16, strlen($password));
    }
}
