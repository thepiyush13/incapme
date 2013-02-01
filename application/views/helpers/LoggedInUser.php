<?php

class Zend_View_Helper_LoggedInUser
{
    protected $view;
    function setView($view) 
    { 
        $this->view = $view; 
    } 
    function loggedInUser()
    {
        $auth = Zend_Auth::getInstance(); 
        if($auth->hasIdentity()) 
        {
            $logoutUrl = $this->view->url(array('controller'=>'auth','action'=>'logout'));
            $user = $auth->getIdentity(); 
            $username = $this->view->escape(ucfirst($user->username));
            $string = 'Logged in as ' . $username . ' | <a href="' .
            $logoutUrl . '">Log out</a>';
        } else {
            $loginUrl = $this->view-> url(array('controller'=>'auth','action'=>'identify')); 
            $string = '<a href="'. $loginUrl . '">Log in</a>'; 
        }
        return $string;
    }
}
?>
