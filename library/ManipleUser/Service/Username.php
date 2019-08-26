<?php

class ManipleUser_Service_Username
{
    /**
     * @Inject('user.model.userMapper')
     * @var ManipleUser_Model_UserMapperInterface
     */
    protected $_userRepository;

    /**
     * @param array $data
     * @return string
     */
    public function generateUsername(array $data = array())
    {
        $stem = $this->generateStem($data);

        if (strlen($stem)) {
            if (!$this->_userRepository->getUserByUsername($stem)) {
                return $stem;
            }

            for ($i = 1; $i <= 10; ++$i) {
                $username = $stem . sprintf('%02d', $i);
                if (!$this->_userRepository->getUserByUsername($username)) {
                    return $username;
                }
            }

            $tries = 5;
            while ($tries--) {
                try {
                    $username = $stem . Zend_Crypt_Math::randInteger(100, 1000);
                } catch (Zend_Crypt_Exception $e) {
                    throw new LogicException($e->getMessage(), $e->getCode(), $e);
                }

                if (!$this->_userRepository->getUserByUsername($username)) {
                    return $username;
                }
            }
        }

        $tries = 5;
        while ($tries--) {
            $username = Zefram_Random::getString(Zefram_Random::ALNUM . '_', 12);
            if (!$this->_userRepository->getUserByUsername($username)) {
                return $username;
            }
        }

        throw new LogicException(sprintf('Unable to generate username from stem "%s"', $stem));
    }

    /**
     * Generate username stem from provided data
     *
     * @param array $data
     * @return string
     * @internal
     */
    public function generateStem(array $data)
    {
        $email = isset($data['email']) ? $data['email'] : null;

        if ($email !== null) {
            list($local, ) = preg_split('/[+@]/', $email, 2);
            $stem = $this->_sanitizeStem($local);

            if (strlen($stem)) {
                return $stem;
            }
        }

        $stem = $this->_sanitizeStem(
            (isset($data['first_name']) ? $data['first_name'] : null)
            .
            (isset($data['last_name']) ? $data['last_name'] : null)
        );

        return strlen($stem) ? $stem : null;
    }

    /**
     * Remove from provided string characters that are invalid in usernames
     *
     * @param string $value
     * @return string
     */
    protected function _sanitizeStem($value)
    {
        $value = str_replace(array('.', '-'), '_', $value);
        $value = Zefram_Filter_Translit::filterStatic($value);
        $value = preg_replace('/[^_0-9A-Za-z]/', '', $value);
        return $value;
    }

    /**
     * @return ManipleUser_Validate_Username
     */
    public function getUsernameValidator()
    {
        return new ManipleUser_Validate_Username($this->_userRepository);
    }
}
