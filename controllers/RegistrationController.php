<?php

class ModUser_RegistrationController extends Zefram_Controller_Action
{
    /**
     * @return Zend_Session_Namespace
     */
    public function getSessionNamespace() // {{{
    {
        return new Zend_Session_Namespace('user.registration');    
    } // }}}

    /**
     * Action executed after successful registration, i.e. a registration
     * request has been saved to the database.
     *
     * No params are expected.
     */
    public function completeAction() // {{{
    {
        // registration complete message is available for a limited amount
        // of time, and within limited number of page hops
        $sessionNamespace = $this->getSessionNamespace();

        $this->view->activated  = $sessionNamespace->activated;
        $this->view->complete   = $sessionNamespace->complete;
        $this->view->expires_at = $sessionNamespace->expires_at;
        $this->view->email      = $sessionNamespace->email;
    } // }}}

    /**
     * Link to this action is stored in the registration confirmation email
     * message.
     *
     * Parameters expected:
     * - reg_id
     */
    public function confirmAction()
    {
        $reg_id = (string) $this->getScalarParam('reg_id');
        $reg = $this->getResource('tableManager')->getTable('ModUser_Model_DbTable_Registrations')->fetchRow(array(
            'reg_id = ?' => $reg_id,
            'status = ?' => 'PENDING',
        ));

        // check if user with the same registration email already exists in the database
        $userRepository = $this->getResource('user.user_manager');

        if ($reg) {
            // TODO check for expiration
            $data = Zefram_Json::decode($reg->data);
            $user = $userRepository->getUserByEmail($reg->email);
            if ($user) {
                try {
                    $reg->status = 'INVALIDATED';
                    $reg->save();
                } catch (Exception $e) {
                }
                $this->view->already_registered = true;
                $this->view->error = 'Użytkownik o tym adresie e-mail jest już zarejestrowany';
            }
        }

        if (empty($reg)) {
            $this->view->error = true;

        } else {
            //$auto_accept_domains = array('fuw.edu.pl');
            //$domain = substr($data['email'], strrpos($data['email'], '@') + 1);
            //$auto_accept = in_array($domain, $auto_accept_domains);

            $db = $this->getResource('tableManager')->getAdapter();
            $db->beginTransaction();

            try {
                $reg->confirmed_at = time();

                /*if ($auto_accept) {
                    $reg->status = 'ACCEPTED';
                } else {
                    $reg->status = 'CONFIRMED';
                }*/

                $reg->status = 'CONFIRMED';
                $reg->save();

                if (true) { // no verification
                    $user = $userRepository->createUser();
                    $filter = new Zend_Filter_Word_UnderscoreToCamelCase();

                    if (empty($data['username'])) {
                        $data['username'] = $reg->email;
                    }

                    foreach ($data as $key => $value) {
                        $method = 'set' . $filter->filter($key);
                        if (method_exists($user, $method)) {
                            $user->{$method}($value);
                        }
                    }

                    $user->setIsActive(true);
                    $user->setCreatedAt(time());
                    $user->setId(null); // enforce auto-generation
                    $userRepository->saveUser($user);
                    // info o aktywacji konta
                }

                //if ($auto_accept) {
                //    $this->_autoAccept($data);
                //}

                $db->commit();

            } catch (Exception $e) {
                $db->rollBack();
                throw $e;
            }

            $appName = 'app';

            // Notify user about account activation
            $message = new Zefram_Mail;
            $message->setType(Zend_Mime::MULTIPART_RELATED);
            $message->setSubject(sprintf('[%s] Twoje konto zostało aktywowane', $appName));
	    $message->addTo($user->getEmail());

            $this->view->message = $message;
            $this->view->user = $user;
            $this->view->site_url = $this->view->serverUrl() . $this->view->baseUrl('/');
            $this->view->url_forgot_password = $this->view->serverUrl() . $this->view->url('user.password.forgot');

            $name = $user->getUsername();
            if ($name === $user->getEmail()) {
                $name = substr($name, 0, strpos($name, '@'));
            }
            $this->view->name = $name;

            // TODO configurable template root dir
            $message->setBodyHtml($this->view->render('registration/mail/account-activated.twig'));
            $message->send();

            /*
            // notify staff uses about registration user_prefs
            // fetch all staff users
            $staff = $userRepository->getStaffUsers();
            if ($staff && !$auto_accept) {
                $message = new Zefram_Mail;
                $message->setType(Zend_Mime::MULTIPART_RELATED);
                $message->setSubject(sprintf('[%s] Nowe zgłoszenie rejestracyjne', $appName));
                foreach ($staff as $user) {
                    $message->addTo($user->email);
                }
                $this->view->reg = $data;
                $this->view->url_verify = $this->view->serverUrl() . $this->view->url('registration.verify', array('reg_id' => $reg->reg_id));
                $this->view->message = $message;
                $message->setBodyHtml($this->view->render('registration/mail/new_registration.twig'));
                try {
                    $message->send();
                } catch (Exception $e) {
                    $this->getResource('log')->err($e->getMessage());
                }
            } */

	    $this->view->accepted = true;
            $this->view->email = $reg->email;

            // prepare login form
            $form = new ModUser_Form_Login;
            $form->setAction($this->view->url('user.auth.login'));
            $form->setDefaults(array('username' => $reg->email));

            $this->view->form = $form;
        }
    }
}
