<?php

class LoginController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $form = new Application_Form_Loginform();
        $request = $this->getRequest();
        $formdata = $request->getPost();
        $model = new Application_Model_Users();
        if($request->isPost()) {
            if($form->isValid($formdata)) {
                if($request->getPost('lang')) {
                    $lang = $request->getPost('lang');
                } 
                elseif(isset ($_COOKIE['lang'])) {
                    $lang = $_COOKIE['lang'];
                }  else $lang = 'en';
                if(isset($_COOKIE['lang']) && $_COOKIE['lang'] !== $lang) {
                    setcookie('lang', $lang, time()-3600,'/', $_SERVER['SERVER_NAME']);
                }
                else setcookie('lang', $lang, time()+7200,'/', $_SERVER['SERVER_NAME']);
                if($this->_process($form->getValues())) {
                    $this->_redirect('/');
                }
            }
            else $form->populate ($formdata);
        }
        $this->view->form = $form;  
    }

    /**
        * 
        * @param type $values
        * @return boolean
        * process the authentication process.
        */
    protected function _process($values) {
    // Get our authentication adapter and check credentials
        $auth = Zend_Auth::getInstance();
        $collection = new Application_Model_Users();
        $authAdapter = new Zend_Auth_Adapter_MongoDb($collection->_getMongoCollection());
        $authAdapter->setIdentityKeyPath('username');
        $authAdapter->setCredentialKeyPath('password');
        $authAdapter->setIdentity($values['username']);
        $authAdapter->setCredential(md5($values['password']));
        $result =  $auth->authenticate($authAdapter);
        if ($result->isValid()) {
            $user = $authAdapter->getResultDocObject();
            $auth->getStorage()->write($user);
            return true;
        }
        return false;
    }

    /**
        * user logout
        */
    public function logoutAction() {
        Zend_Auth::getInstance()->clearIdentity();
        $this->_helper->redirector('login'); // back to login page
    }

    /**
        * 
        * @return type zend auth identity
        */
    public function _auth() {
        $auth = Zend_Auth::getInstance();
        return $auth->getIdentity();
    }


}

