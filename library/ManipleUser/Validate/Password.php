<?php

class ManipleUser_Validate_Password extends Zend_Validate_StringLength
{
    const INVALID_CHARS    = 'invalidChars';
    const TOO_EASY         = 'tooEasy';
    const NOT_ENOUGH_ALNUM = 'notEnoughAlnum';

    protected $_messageTemplates = array(
        self::TOO_SHORT        => 'Password must be at least %min% characters long',
        self::INVALID_CHARS    => 'Password contains invalid characters',
        self::TOO_EASY         => 'Each character may occur no more than half the password length (rounded down) times',
        self::NOT_ENOUGH_ALNUM => 'Password must contain at least one letter and at least one digit',
    );

    /**
     * Default minimum password length
     * @var int
     */
    protected $_min = 6;

    public function isValid($value)
    {
        if (!parent::isValid($value)) {
            return false;
        }

        // validate characters
        // disallow tilde as in Windows it depends on previous char pushed
        // and language specific characters (diacritics)
        $rx = '/^[-+=_.:;!?@#$%&*<>^"\'\\\\\\/\\(\\)\\[\\]\\{\\}a-z0-9]+$/i';
        if (!preg_match($rx, $value)) {
            $this->_error(self::INVALID_CHARS);
            return false;
        }

        // single letter must not occur more than half length of the string
        // rounded down
        $chars = array();
        $letters = 0;
        $digits = 0;
        for ($i = 0, $len = strlen($value), $lim = floor($len / 2); $i < $len; ++$i) {
            $c = substr($value, $i, 1);
            if (ctype_digit($c)) {
                ++$digits;
            } elseif (ctype_alpha($c)) {
                ++$letters;
            }
            $chars[$c] = (isset($chars[$c]) ? $chars[$c] : 0) + 1;
            if ($chars[$c] > $lim) {
                $this->_error(self::TOO_EASY);
                return false;
            }
        }

        if (!($letters * $digits)) {
            // at least one letter and one digit is required
            $this->_error(self::NOT_ENOUGH_ALNUM);
            return false;
        }
        
        return true;    
    }
}
