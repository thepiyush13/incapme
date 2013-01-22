<?php
class Zend_View_Helper_LoggedInAs extends Zend_View_Helper_Abstract 
{
    public function loggedInAs ()
    {
        //$translate = Zend_Registry::get('Zend_Translate');
        $auth = Zend_Auth::getInstance();
        if (1 || $auth->hasIdentity()) {
            $username = $auth->getIdentity();
            $username = 'Lakshmi Jagganathan';
            $logoutUrl = $this->view->url(array('module' => 'default','controller'=>'login', 'action'=>'logout'), null, true);
            //return 'Welcome ' . $username['firstname'] .  '. <a href="'.$logoutUrl.'">Logout</a>';
            
            return '<ul class="nav btn-group">
                <li class="btn btn-inverse" ><a title="" href="#"><i class="icon icon-user"></i> <span class="text">' . $username .  '</span></a></li>
				<li class="btn btn-inverse"><a title="" href="'.$logoutUrl.'"><i class="icon icon-share-alt"></i> <span class="text">Logout</span></a></li>
            </ul>';
        } 
        
        //$request = Zend_Controller_Front::getInstance()->getRequest();
        //$controller = $request->getControllerName();
        //$action = $request->getActionName();
        //if($controller == 'login' && $action == 'index') {
        //    return '';
        //} else {
          //  echo $translate->_('welcome Guest');
           // $loginUrl = $this->view->url(array('module' => 'default','controller'=>'login', 'action'=>'index'));
            //return '<a href="'.$loginUrl.'">Login</a>';
        //}
    }
    
    
}