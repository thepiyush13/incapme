<?php

class ActivityController extends Zend_Controller_Action
{
    //private $error = array();
    public function init()
    {
        /* Initialize action controller here */
        $this->_helper->viewRenderer->setNoRender();
        $this->view = new Zend_View();
       
       $this->view->setScriptPath(APPLICATION_PATH .'/views/scripts/');
       $this->view->setHelperPath(APPLICATION_PATH .'/views/helpers/');
    }

    public function indexAction()
    {
       $this->view->output  = $this->_getList();
       echo $this->view->render('activity/index.phtml'); 
        
    }
    public function deleteAction(){
        $output = array();
        $activityId = $this->_request->getPost('activityId');
       if ( $activityId ){
             
            //if user has permission to delete the activity           
           $mdlDelete  = new Application_Model_Activity();
           $output = $mdlDelete->deleteActivity($activityId);
           $this->view->output  = $output ;          
           
       }
       
       echo $this->view->render('activity/index.phtml'); 
    }
    public function editAction($activityId) {
        
        
    }
    public function saveAction() {
        
    }
    public function addAction() {
        
//init
        
        //provides navigation path for the next view
        $nextForm = array('new'=>'insertActivityInfo','insertActivityInfo'=>'insertContributors','insertContributors'=>'insertParticipants','insertParticipants'=>'insertOutcomes','insertOutcomes'=>'activityDetails');
        $type = $this->_getParam('type','new');
        // if request is post - check vaidation and call respective model 
        // else just display the default form view
        if($this->_request->getPost()) {
            $data = $this->_request->getPost();
            $isValid = $this->_validation($data, $type);
            if ( $isValid['error'] == TRUE ){
                $this->view->output = $isValid ;
                echo $this->view->render('activity/'.$type.'.phtml'); 
            }
            else{
                    $mdl  = new Application_Model_Activity();
                    //$type = insertActivityInfo etc posted by form 
                    //$output = $mdl->$type($data);  // calling model dynamiclly as [ model name ~ form name ]
                    $output = $mdl->$type($data);
                    $this->view->output = $output ;

                    if(isset( $output['error'] ) ){
                        echo $this->view->render('activity/'.$type.'.phtml'); 
                    }
                    else{
                        
                         echo $this->view->render('activity/'.$nextForm[$type].'.phtml');
                    }
           }
        }
        else{
            //echo $this->view->render('activity/'.$nextForm['new'].'.phtml');
            echo $this->view->render('activity/'.$type.'.phtml');
        }


            
            
        
       
    }
    
    protected function getListActivity(){
        
    }
    protected function _insertActivityInfo() {
            $data = $this->_request->getPost();
            //$data = $this->getAllParams();
            $isValid = $this->_validation($data, __METHOD__);
            
            if ( $isValid['status'] == FALSE ){
                $this->view->output['error']  = $isValid['data'] ;
                echo $this->view->render('activity/insertActivityInfo.phtml'); 
            }
            
            $mdlInsertActivityInfo  = new Application_Model_Activity();
            $output = $mdlInsertActivityInfo->insertActivityInfo($data);
            $this->view->output = $output ;
            
            if($output['error']){
                echo $this->view->render('activity/insertActivityInfo.phtml'); 
            }
            
            echo $this->view->render('activity/insertContributors.phtml');
 
    }
    protected function _insertContributors() {
            $data = $this->_request->getPost();
            //$data = $this->getAllParams();
            $isValid = $this->_validation($data, __METHOD__);

            if ( $isValid['status'] == FALSE ){
                $this->view->output['error']  = $isValid['data'] ;
                echo $this->view->render('activity/insertActivityInfo.phtml'); 
            }

            $mdlInsertActivityInfo  = new Application_Model_Activity();
            $output = $mdlInsertActivityInfo->insertActivityInfo($data);
            $this->view->output = $output ;

            if($output['error']){
                echo $this->view->render('activity/insertActivityInfo.phtml'); 
            }

            echo $this->view->render('activity/insertContributors.phtml');
    }
    protected function insertParticipants() {
        
    }
    protected function insertOutcomes () {
       
    }
   
    protected function _validation($data, $type){
//         $activityInfo = array(
//             'requiered' => array('name', 'type', 'date'),
//             'date' => array(),
//         );
//        $required['insertActivityInfo'] = array('name'=>'zend::alpha',''=>'');
//        $required[$block];
        $output = array();
        $required = array(
        'insertActivityInfo'=>array( 'field_name'=>'zend::alpha',''=>''), 
        'insertContributors'=>array( 'field_name'=>'zend::alpha',''=>''),
        'insertParticipants'=>array( 'field_name'=>'zend::alpha',''=>''),
        'insertOutcomes'=>array( 'field_name'=>'zend::alpha',''=>'')
        );
        foreach($required[$type] as $field=>$validation){
            $vR = 'success';//$validation($data[field]);
            if( !$vR ){
                $output['error'] = 'eror';//$vR->getMessage();
            }
            else{
                $output['error'] = FALSE;
            }
        }
        return $output;
        
    }
    protected function _getList(){
        $mdlList  = new Application_Model_Activity();
        $output = $mdlList->getList();
        return  $output ;
    }
    public function uploadparticipantsAction(){
        $this->_helper->layout()->disableLayout();
         $data  = $_FILES['fileParticipant'];
         $activityId = $this->_request->getPost('activity_id');
         $data['activity_id'] = $activityId;
        if($data['size'] != 0 ){           
            $mdl  = new Application_Model_Activity();
            $output = $mdl->uploadParticipants($data);
            echo $output;
            
        }
        else{
            echo 'Error Uploading file';
        }
    }
    
    
     


}

