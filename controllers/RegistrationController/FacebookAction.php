<?php

/**
 * Create user using Facebook OAuth.
 * This only works directly when registration verification is off. If it is
 * on, this feature is unavailable. External account can be connected via
 * settings.
 *
 * @deprecated
 */
class ModUser_RegistrationController_FacebookAction
    extends Maniple_Controller_Action_Standalone
{
    public function run()
    {
        if ($this->getSecurityContext()->isAuthenticated()) {
            $this->_helper->redirector->gotoUrl($this->view->baseUrl('/'));
            return;
        }

        $config = $this->getResource('config');

        $facebook = new Facebook($config->facebook->toArray());
        $user = $facebook->getUser();

        try {
            $info = $facebook->api('/me');
        } catch (Exception $e) {
            // OAuthException: Error validating access token: The user has not authorized application
        }

        $url = $this->view->baseUrl('mod-user/registration/facebook');
        if (empty($info)) {
            if (isset($_GET['error'])) {
                switch (strtolower($_GET['error'])) {
                    case 'access_denied':
                        $this->view->access_denied = true;
                        $this->_helper->viewRenderer->setNoRender(true);
                        echo '<h2>Logowanie nie powiodło się</h2>';
                        echo '<p>Aby skorzystać z opcji logowania za pomocą konta na Facebooku ';
                        echo 'musisz wyrazić zgodę na dostęp do Twoich podstawowych danych oraz ';
                        echo 'adresu e-mail.</p>';
                        echo '<p>Ponów próbę <a href="' . $url . '">logowania za pomocą Facebooka</a>.</p>';
                        echo '<a href="'.$this->view->baseUrl('/').'">Powrót do strony głównej</a>';
                        return;
                }
            }

            $params = array(
                'redirect_uri' => $this->view->serverUrl() . $this->view->baseUrl('mod-user/registration/facebook'),
                'display' => 'page',
                'scope' => 'email',
            );
            $redirect = $facebook->getLoginUrl($params);
            header('Location: '. $redirect, true, 302);
            exit;
        }

        // we gained access to basic user data, but not to his email
        if (empty($info['email'])) {
            $this->view->access_denied = true;
            $this->_helper->viewRenderer->setNoRender(true);
            echo '<h2>Logowanie nie powiodło się</h2>';
            echo '<p>Aby skorzystać z opcji logowania za pomocą konta na Facebooku ';
            echo 'musisz wyrazić zgodę na dostęp do Twoich podstawowych danych oraz ';
            echo 'adresu e-mail.</p>';
            echo '<p>Aby ustawić odpowiednie uprawnienia dla naszej strony ';
            echo 'otwórz <a href="http://www.facebook.com/settings?tab=applications" target="_blank">ustawienia aplikacji na Facebooku</a>, ';
            echo 'usuń z niej aplikcaję [[ app ]], a następnie spróbuj <a href="'.$this->view->baseUrl('mod-user/registration/facebook').'">zalogować sie ponownie</a>.</p>';
            echo '<a href="'.$this->view->baseUrl('/').'">Powrót do strony głównej</a>';
            return;
        }

        // TODO identify user by ID rather than email...
        $userManager = $this->getUserManager();
        $user = $userManager->getUserByEmail($info['email']);

        if (!$user) {
            $registrationClosed = false;

            if ($registrationClosed) {
                $this->_helper->flashMessenger->addErrorMessage('Rejestracja nowych użytkowników jest zamknięta');
                $this->_helper->redirector->gotoRoute('user.auth.login');
                return;
            }
            
            $user = $userManager->createUser($info);
            $user->setUsername($info['email']);
            $user->setActive(true);
            $user->setCreatedAt(time());
            $user->setId(null);
            $userManager->saveUser($user);
            $fh = fopen(APPLICATION_PATH . '/../storage/facebook_ids.txt', 'a+');
            fprintf($fh, "%d %s %s\n", $user->getId(), $facebook->getUser(), $info['email']);
            fclose($fh);
        }

        $this->getSecurityContext()->getUserStorage()->setUser($user);
        $this->_helper->redirector->gotoUrl($this->view->baseUrl('/'));

        // find preference where facebook.id = ...
        // if not exists create account

        // https://developers.facebook.com/policy/
        //   I. Features and Functionality, 6.:
        //   Your website must offer an explicit "Log Out" option that also
        //   logs the user out of Facebook.

        // http://developers.facebook.com/bugs/405252409526009/
        //   getLogoutUrl() redirects to the Facebook home page if the
        //   supplied access_token is invalid.

        // wylogowanie razem z wylogowaniem z Facebooka
        // $this->_getFacebook()->destroySession();
        // $url = $this->getLogoutUrl($url);
        // header('Location: ' . $url, true, 302);
        // exit;

        // usuniecie lokalnej sesji bez wylogowania z Facebooka
        // $this->_getFacebook()->destroySession();
        //
        /* if ($url) {
            $params = array('next' => $url);
        } else {
            $params = array();
        }

        return $this->_getFacebook()->getLogoutUrl($params);
         */
    }
}
