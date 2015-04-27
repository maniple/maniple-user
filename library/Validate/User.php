<?php

abstract class ModUser_Validate_User extends Zend_Validate_Abstract
{
    const MATCH_ID                = 'id';
    const MATCH_EMAIL             = 'email';
    const MATCH_USERNAME          = 'username';
    const MATCH_USERNAME_OR_EMAIL = 'usernameOrEmail';

    const USER_NOT_EXISTS         = 'userNotExists';
    const USER_EXISTS             = 'userExists';

    /**
     * @var ModUser_Model_UserRepositoryInterface
     */
    protected $_userRepository;

    /**
     * @var ModUser_Model_UserInterface
     */
    protected $_user;

    /**
     * @var string
     */
    protected $_matchBy = self::MATCH_ID;

    protected $_messageTemplates = array(
        self::USER_NOT_EXISTS => 'No matching user was found',
        self::USER_EXISTS     => 'A matching user was found',
    );

    protected $_messageVariables = array(
        'user' => '_user',
    );

    /**
     * Constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = null)
    {
        if ($options) {
            foreach ($options as $key => $value) {
                $method = 'set' . $key;
                if (method_exists($this, $method)) {
                    $this->{$method}($value);
                }
            }
        }
    }

    /**
     * @param  ModUser_Model_UserRepositoryInterface $userRepository
     * @return Core_Validate_UserExists
     */
    public function setUserRepository(/* ModUser_Model_UserRepositoryInterface */ $userRepository)
    {
        $this->_userRepository = $userRepository;
        return $this;
    }

    /**
     * @return ModUser_Model_UserRepositoryInterface
     * @throws Exception
     */
    public function getUserRepository()
    {
        if (empty($this->_userRepository)) {
            throw new Exception('User repository is not configured');
        }
        return $this->_userRepository;
    }

    /**
     * @param  string $matchBy
     * @return ModUser_Validate_UserExists
     * @throws InvalidArgumentException
     */
    public function setMatchBy($matchBy)
    {
        $matchBy = (string) $matchBy;

        switch ($matchBy) {
            case self::MATCH_ID:
            case self::MATCH_EMAIL:
            case self::MATCH_USERNAME:
            case self::MATCH_USERNAME_OR_EMAIL:
                $this->_matchBy = $matchBy;
                break;

            default:
                throw new InvalidArgumentException(sprintf(
                    "Unsupported matchBy option value: '%s'", $matchBy
                ));
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getMatchBy()
    {
        return $this->_matchBy;
    }

    /**
     * Retrieves user from repository matched by given value interpreted
     * according to current matchBy setting.
     *
     * @param  mixed $value
     * @return ModUser_Model_UserInterface
     */
    protected function _getUserByValue($value)
    {
	$user = null;

        switch ($this->_matchBy) {
            case self::MATCH_ID:
                $user = $this->getUserRepository()->getUser($value);
                break;

            case self::MATCH_EMAIL:
                $user = $this->getUserRepository()->getUserByEmail($value);
                break;

            case self::MATCH_USERNAME:
                $user = $this->getUserRepository()->getUserByUsername($value);
                break;

            case self::MATCH_USERNAME_OR_EMAIL:
                $user = $this->getUserRepository()->getUserByUsernameOrEmail($value);
                break;

            default:
                throw new RuntimeException(sprintf(
                    "Unsupported matchBy option value: '%s'", $this->_matchBy
                ));
	}

	return $user;
    }
}
