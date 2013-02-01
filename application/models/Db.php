<?php
class Application_Model_Db {
    
    protected $_db;
    
    public function __construct() {
        $this->_db = Zend_Registry::get('db');
         
    }
    
}

?>
