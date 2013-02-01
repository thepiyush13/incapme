<?php


class AuthController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $this->_forward('login');
    }
    public function loginAction()
    {
    }
    public function identifyAction()
    {
        if ($this->getRequest()->isPost()) { 
            $formData = $this->_request->getPost(); 
            if (empty($formData['username']) 
            || empty($formData['password'])) {
                $this->_flashMessage('Empty username or password.'); 
            } else {
                // do the authentication
                $authAdapter = $this->_getAuthAdapter($formData); 
                $auth = Zend_Auth::getInstance();
                $result = $auth->authenticate($authAdapter); 
                if (!$result->isValid()) {
                    $this->_flashMessage('Login failed');
                } else {
                    $data = $authAdapter->getResultRowObject(null,'password');
                    $auth->getStorage()->write($data); 
                    $this->_redirect($this->_redirectUrl); 
                    return; 
                }
            }
        }
        $this->_redirect('/auth/login');
    }

    protected function _flashMessage($message) {
    $flashMessenger = $this->_helper->FlashMessenger; 
    $flashMessenger->setNamespace('actionErrors'); 
    $flashMessenger->addMessage($message); 
    }
    protected function _getAuthAdapter($formData)
    {
        $this->mysqli = new mysqli('localhost','root','','incapme');
        $authAdapter = new Zend_Auth_Adapter_DbTable($this->mysqli);
        $authAdapter->setTableName('users') 
        ->setIdentityColumn('username') 
        ->setCredentialColumn('password') 
        ->setCredentialTreatment('SHA1(?)'); 
        // get "salt" for better security 
        
        $salt = 'usgfdj14327hdyfyu'; 
        $password = $salt.$formData['password']; 
        $authAdapter->setIdentity($formData['username']); 
        $authAdapter->setCredential($password); 
        return $authAdapter;
    }

}
?>