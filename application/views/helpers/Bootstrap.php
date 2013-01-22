<?php

/**
 * 
 * @author Kanchan Karjee <kanchan.karjee@inkoniq.com>
 * @author Abhishek kumar <abhishek@inkoniq.com>
 * 
 * This is bootstarp file, contains the basic configurations for the system
 */

    class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {
        public function _initRoutes() {
            $front = Zend_Controller_Front::getInstance();
            $front->setControllerDirectory(array(
                        'default'   =>  APPLICATION_PATH.'/controllers',
                        //'admin'     =>  APPLICATION_PATH.'/modules/admin/controllers',
                        //'login'     =>  APPLICATION_PATH.'/modules/login/controllers',
                        //'posts'     =>  APPLICATION_PATH.'/modules/posts/controllers',
                        //'test'      =>  APPLICATION_PATH.'/modules/test/controllers'
                        ));
            
            include APPLICATION_PATH.'/controllers/plugin/AclPlugins.php';
            include APPLICATION_PATH.'/controllers/helper/AclDynamic.php';
            include APPLICATION_PATH.'/controllers/plugin/LanguageSelector.php';
            
            $auth = Zend_Auth::getInstance(); //fetch an instance of Zend_Auth
            if($auth->hasIdentity()) {
                $user = $auth->getIdentity();
                $role = $user['role'];
                $model = new Application_Model_ResourcePrivilege();
                $resources = $model->getAclByRole($role);
            } else {
                $role = 'guest';
                $resources = array('index','login', 'error');
            }
            $resourceModel = new Application_Model_AclResources();
            $result = $resourceModel->getAllResources();
            $helper = new Zend_Controller_Helper_AclDynamic($role);
            $front->registerPlugin(new Zend_Controller_Plugin_Acl($helper));
            $front->registerPlugin(new Zend_Controller_plugin_LanguageSelector(
                    array(
                        'module'    =>  'default',
                        'controller'=>  'index',
                        'action'    =>  'index'
                    )
                    ));
        }
        
        /**
         * Grab the log resource and set it in registry.
         */
        protected function _initLog() {
            if ($this->hasPluginResource('log')) {
                $r = $this->getPluginResource('log');
                $log = $r->getLog();
                Zend_Registry::set('log', $log);
            }
        }
        /**
         * Setting database configuration
         * mongodb - $db
         */
        protected function _initConfig() {
            $config = new Zend_Config_Ini(APPLICATION_PATH."/configs/application.ini", APPLICATION_ENV);
            $db = $config->resources->db->params;
            Zend_Registry::set('mongodb', $db);
            
            // Zend translation configuration initialization
            
        }
        
    }
    
    

