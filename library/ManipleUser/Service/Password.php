<?php

class ManipleUser_Service_Password
{
    const MIN_LENGTH = 6;

    const SPECIAL_CHARS = '-+=_.:;!?@#$%&*<>^"\'\\/()[]{}';

    const TEMPORARY_PREFIX = 'temporary:';

    /**
     * @param string $password
     * @return string
     */
    public function passwordHash($password)
    {
        return password_hash((string) $password, PASSWORD_BCRYPT);
    }

    /**
     * Verifies that a password matches a hash. This validator requires that
     * function password_verify() is available (it is implemented natively
     * since PHP 5.5.0).
     *
     * @param string $password
     * @param string|ManipleUser_Model_UserInterface $hashOrUser
     * @return bool
     */
    public function verify($password, $hashOrUser)
    {
        if ($hashOrUser instanceof ManipleUser_Model_UserInterface) {
            $hash = $hashOrUser->getPassword();
        } else {
            $hash = (string) $hashOrUser;
        }
        if ($this->isPasswordTemporary($hash)) {
            $hash = substr($hash, strlen(self::TEMPORARY_PREFIX));
        }
        return password_verify($password, $hash);
    }

    /**
     * @param int $length
     * @return string
     */
    public function generatePassword($length = 24)
    {
        $length = (int) $length;
        if ($length < self::MIN_LENGTH) {
            throw new InvalidArgumentException('Minimum password length is ' . self::MIN_LENGTH);
        }

        $password = null;
        $passwordValidator = new ManipleUser_Validate_Password(array('min' => self::MIN_LENGTH));

        $alphaCount = ceil($length / 3);
        $alphaUpperCount = ceil($alphaCount / 2);
        $alphaLowerCount = floor($alphaCount / 2);
        $numCount = floor($length / 3);
        $specialCount = $length - $alphaCount - $numCount;

        $tries = 10;
        while (--$tries) {
            $password = str_shuffle(
                Zefram_Random::getString($alphaUpperCount, Zefram_Random::ALPHA_UPPER)
                . Zefram_Random::getString($alphaLowerCount, Zefram_Random::ALPHA_LOWER)
                . Zefram_Random::getString($numCount, Zefram_Random::DIGITS)
                . Zefram_Random::getString($specialCount, self::SPECIAL_CHARS)
            );

            if ($passwordValidator->isValid($password)) {
                break;
            }

            $password = null;
        }

        if (empty($password)) {
            throw new RuntimeException('Unable to generate valid password');
        }

        return $password;
    }

    /**
     * @param string $password
     * @return string
     */
    public function temporaryPasswordHash($password)
    {
        return self::TEMPORARY_PREFIX . $this->passwordHash($password);
    }

    /**
     * @param string $hash
     * @return bool
     */
    public function isPasswordTemporary($hash)
    {
        return !strncmp($hash, self::TEMPORARY_PREFIX, strlen(self::TEMPORARY_PREFIX));
    }
}
