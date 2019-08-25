<?php

class ManipleUser_Validate_Username extends Zefram_Validate
{
    public function __construct(ManipleUser_Model_UserMapperInterface $userRepository)
    {
        parent::__construct(array(
            array(
                new ManipleUser_Validate_UserNotExists(array(
                    'userRepository' => $userRepository,
                    'matchBy' => ManipleUser_Validate_User::MATCH_USERNAME,
                    'messages' => array(
                        ManipleUser_Validate_User::USER_EXISTS => 'This username is already in use',
                    ),
                )),
                true
            ),
        ));
    }

    /**
     * @param ManipleUser_Model_UserInterface $user
     * @return $this
     */
    public function setUser(ManipleUser_Model_UserInterface $user)
    {
        /** @var ManipleUser_Validate_UserNotExists $validator */
        $validator = $this->getValidator('UserNotExists');
        $validator->setExclude($user->getUsername());
        return $this;
    }
}
