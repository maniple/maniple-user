<?php

class ManipleUser_Signup_SignupManager
{
    const className = __CLASS__;

    /**
     * @Inject
     * @var Zefram_Db
     */
    protected $_db;

    /**
     * @Inject
     * @var ManipleCore_Settings_SettingsManager
     */
    protected $_settingsManager;

    /**
     * @Inject('user.userManager')
     * @var ManipleUser_Model_UserManager
     */
    protected $_userRepository;

    /**
     * @Inject('ManipleUser.UserSettings')
     * @var ManipleUser_UserSettings_Service
     */
    protected $_userSettingsManager;

    /**
     * @var array
     */
    protected $_customFields;

    public function createSignup($form)
    {

    }

    /**
     * Create user account from registration
     *
     * @param string $token
     * @return ManipleUser_Model_UserInterface
     */
    public function createUser($token)
    {
        $reg = $this->_db->getTable(ManipleUser_Model_DbTable_Registrations::className)->fetchRow(array(
            'reg_id = ?' => (string) $token,
            'status = ?' => 'PENDING',
        ));

        if (!$reg) {
            throw new ManipleUser_Signup_Exception_SignupNotFound();
        }

        // TODO check for signup expiration
        $user = $this->_userRepository->getUserByEmail($reg->email);

        if ($user) {
            try {
                $reg->status = 'INVALIDATED';
                $reg->save();
            } catch (Exception $e) {
            }

            throw new ManipleUser_Signup_Exception_UserAlreadyRegistered();
        }

        $data = Zefram_Json::decode($reg->data);

        //$auto_accept_domains = array('fuw.edu.pl');
        //$domain = substr($data['email'], strrpos($data['email'], '@') + 1);
        //$auto_accept = in_array($domain, $auto_accept_domains);

        $reg->confirmed_at = time();

        /*if ($auto_accept) {
            $reg->status = 'ACCEPTED';
        } else {
            $reg->status = 'CONFIRMED';
        }*/

        $reg->status = 'CONFIRMED';
        $reg->save();

        // Invalidate all other PENDING registrations for this user
        $this->_db->getTable(ManipleUser_Model_DbTable_Registrations::className)->update(
            array('status' => 'INVALIDATED'),
            array(
                'email = ?' => (string) $reg->email,
                'status = ?' => 'PENDING',
            )
        );

        $user = $this->_userRepository->createUser();
        $filter = new Zend_Filter_Word_UnderscoreToCamelCase();

        if (empty($data['username'])) {
            $data['username'] = $reg->email;
        }

        foreach ($data as $key => $value) {
            $method = 'set' . $filter->filter($key);
            if (method_exists($user, $method)) {
                $user->{$method}($value);
                unset($data[$key]);
            }
        }

        $user->setCreatedAt(time());
        $user->setId(null); // enforce auto-generation
        $this->_userRepository->saveUser($user);

        // Store custom fields
        foreach ($this->getCustomFields() as $name) {
            if (!isset($data[$name])) {
                continue;
            }
            $this->_userSettingsManager->set(
                sprintf('signup.customField.%s', $name),
                $data[$name]
            );
        }

        return $user;
    }

    /**
     * Retrieve custom signup fields
     *
     * @return array
     */
    public function getCustomFields()
    {
        if ($this->_customFields === null) {
            $customFields = array();
            $fields = (array) $this->_settingsManager->get('ManipleUser.Signup.customFields');

            foreach ($fields as $name => $field) {
                $type = isset($field['type']) ? $field['type'] : null;

                if ($type !== 'checkbox') {
                    throw new ManipleUser_Signup_Exception_InvalidArgumentException(sprintf(
                        'Unsupported custom field type: %s',
                        $type
                    ));
                }

                $customFields[$name] = array(
                    'type' => $type,
                    'label' => isset($field['label']) ? $field['label'] : null,
                    'description' => isset($field['description']) ? $field['description'] : null,
                );
            }

            $this->_customFields = $customFields;
        }

        return $this->_customFields;
    }
}
