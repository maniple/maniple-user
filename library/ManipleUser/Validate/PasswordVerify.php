<?php

class ManipleUser_Validate_PasswordVerify extends Zend_Validate_Abstract
{
    const EMPTY_HASH = 'passwordVerifyEmptyHash';
    const INVALID    = 'passwordVerifyInvalid';

    /**
     * @var ManipleUser_PasswordService
     */
    protected $_passwordService;

    /**
     * @var string
     */
    protected $_hash;

    protected $_messageTemplates = array(
        self::EMPTY_HASH => 'No hash was provided to match against',
        self::INVALID    => 'The password provided is invalid',
    );

    /**
     * @param ManipleUser_PasswordService $passwordService
     * @param string $hash
     */
    public function __construct(ManipleUser_PasswordService $passwordService, $hash)
    {
        $this->_passwordService = $passwordService;
        $this->_hash = (string) $hash;
    }

    /**
     * @return string
     */
    public function getHash()
    {
        return $this->_hash;
    }

    /**
     * @param  string $value
     * @return bool
     */
    public function isValid($value)
    {
        $value = (string) $value;
        $this->_setValue($value);

        $hash = $this->getHash();

        if (empty($hash)) {
            $this->_error(self::EMPTY_HASH);
            return false;
        }

        if (!$this->_passwordService->verify($value, $hash)) {
            $this->_error(self::INVALID);
            return false;
        }

        return true;
    }
}
