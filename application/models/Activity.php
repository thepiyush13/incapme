<?php

class Application_Model_Activity extends Application_Model_Db
{
    public function __construct() {
        //parent::__construct();
       //$this->db = Zend_Db::factory($config->database);
        $this->mysqli = new mysqli('localhost','root','','incapme');
       
    }
    public function call($type,$data){
        $this->$type($data);
    }
    protected function getListActivity(){
        
    }
    
    public function insertActivityInfo($data) {
        $result = array(); 
        
        $aR = array();
        foreach($data as $col=>$val){
            $col = mysql_real_escape_string($col);
            $val = '"'.mysql_real_escape_string($val).'"';
            $aR[$col] = $val;
        }
        $cols  =  implode(',',  array_keys($aR));
        $vals = implode(',', array_values($aR));
        
        $sql = 'INSERT INTO activity('.$cols.') VALUES('.$vals.')';
        $qR = $this->mysqli->query($sql);
        
       if ( $qR ){
            $result['success'] = 'Successfully inserted the activity'; 
            $result['activity_id'] = $this->mysqli->insert_id;            
            }
        else{
            $result['error'] = 'Could not Insert the activity:'.$this->mysqli->error;
            
        }
        
        return $result;
    }
    function insertContributors($data){
    // get values to be saved in the contributor table
        $result = array();         
        $aR = array();  
        $vals = array();
        $sql = array();
        //$data  = array_map('mysql_real_escape_string', $data);
        $contributors = $data['activitycontributor_coordinator']  ;
        $leadPresenters = $data['activitycontributor_speaker'] ;
        $activityId  = mysql_real_escape_string ( $data['activity_id'] );
        
        //updating the contributor table
        foreach($contributors as $key=>$val){
            $vals[]= '('.$activityId.',null,"'.mysql_real_escape_string( $val) .'")' ;
        }
        $sql[] = 'DELETE FROM activity_contributor_corodinator WHERE activity_id='.$activityId;        
        $sql[]='INSERT INTO activity_contributor_corodinator(activity_id,human_id,unmapped_coordinator) VALUES'.implode(',',$vals);      
        $vals = array();
        
        //updating the presenter table
        foreach($leadPresenters as $key=>$val){
            $vals[]= '('.$activityId.',null,"'.mysql_real_escape_string( $val) .'")' ;
        }
        $sql[] = 'DELETE FROM activity_contributor_lead_presenter WHERE activity_id='.$activityId;  
        $sql[] = 'INSERT INTO activity_contributor_lead_presenter(activity_id,human_id,unmapped_presenter) VALUES'.implode(',',$vals);
        $vals = array();   
        // the main activity table
        $stmt = 'activitycontributor_sponsors="'.$data['activitycontributor_sponsors'].'",activitycontributor_partner="'.$data['activitycontributor_partner'].'"';        
        $cols  = implode(',', array_keys($aR));
        $vals  = implode(',', array_values($aR));
        
        $sql[] = 'UPDATE activity SET '.$stmt.' WHERE activity_id='.$activityId;        
        
        
        //save to database;
        $query = implode(';',$sql);
        $this->mysqli->autocommit(FALSE);
        $qR = TRUE;
        foreach ($sql as $key => $value) {
            $qR = $qR & $this->mysqli->query($value);           
        }
       if ( $qR ){
            $out = $this->mysqli->commit();                    
            $result['success'] = 'Successfully updated the activity'; 
                  
            }
        else{
            $mysqli->rollback();
            $result['error'] = 'Could not Insert the activity:'.$this->mysqli->error;
            
        }
        
    
        //$mysqli->close();
         $result['activity_id'] = $activityId;   
        return $result;
    }
    public function insertParticipants($data) {
        $result = array();
        $activityId  = mysql_real_escape_string ( $data['activity_id'] );
        $result['activity_id'] = $activityId; 
        return $result;
    }
    function insertOutcomes ($data) {
        // get values to be saved in the contributor table
        $result = array();         
        $aR = array();  
        $vals = array();
        $sql = array();
        //$data  = array_map('mysql_real_escape_string', $data);
        $menteeslist = $data['activityoutcome_menteeslist']  ;
        $mentorslist = $data['activityoutcome_mentorslist'] ;
        $activityId  = mysql_real_escape_string ( $data['activity_id'] );
        
        //updating the contributor table
        foreach($menteeslist as $key=>$val){
            $val = explode('|',mysql_real_escape_string( $val) );
            $vals[]= '('.$activityId.',"'.implode('","',$val) .'")' ;
        }
        $sql[] = 'DELETE FROM activity_outcome_mentees_list WHERE activity_id='.$activityId;        
        $sql[]='INSERT INTO activity_outcome_mentees_list(activity_id,first_name,last_name,email) VALUES'.implode(',',$vals);      
        $vals = array();
        
        //updating the presenter table
        foreach($mentorslist as $key=>$val){
            $val = explode('|',mysql_real_escape_string( $val) );
            $vals[]= '('.$activityId.',"'.implode('","',$val) .'")' ;
        }
        $sql[] = 'DELETE FROM activity_outcome_mentors_list WHERE activity_id='.$activityId;  
        $sql[] = 'INSERT INTO activity_outcome_mentors_list(activity_id,first_name,last_name,email) VALUES'.implode(',',$vals);
        $vals = array();   
        // the main activity table
        $stmt = 'activityoutcome_instructor="'.$data['activityoutcome_instructor']
                .'",activityoutcome_course="'.$data['activityoutcome_course']
                .'",activityoutcome_org="'.$data['activityoutcome_org']
                .'",activityoutcome_plan="'.$data['activityoutcome_plan']
                .'",activityoutcome_detail="'.$data['activityoutcome_detail']
                .'",activityoutcome_idea="'.$data['activityoutcome_idea']
                .'",activityoutcome_institution="'.$data['activityoutcome_institution'].'"'
                ;        

        
        $sql[] = 'UPDATE activity SET '.$stmt.' WHERE activity_id='.$activityId;        
        
        
        //save to database;
        $query = implode(';',$sql);
        $this->mysqli->autocommit(FALSE);
        $qR = TRUE;
        foreach ($sql as $key => $value) {
            $qR = $qR & $this->mysqli->query($value);           
        }
       if ( $qR ){
            $out = $this->mysqli->commit();                    
            $result['success'] = 'Successfully updated the activity'; 
                  
            }
        else{
            $this->mysqli->rollback();
            $result['error'] = 'Could not Insert the activity:'.$this->mysqli->error;
            
        }
        
        $activityDetails  = $this->activityDetails($activityId);
        
        $result['activity_id'] = $activityId;    
        
        //$mysqli->close();
        return $result;
    }
    function deleteActivity($activityId) {
        $result = array(); 
       
        //cascade delete the id from the tables 
        // start transaction and rollback if not commited
        $sql = 'SELECT * FROM institute1';
        //$sql = 'DELETE * FROM institute1 WHERE id='.$activityId;
       
        if ( $qR = $this->_db->query($sql)  ){
            $result['success'] = 'Successfully deleted the product'; 
            $result['data'] = $qR;            
            }
        else{
            $result['error'] = 'Could not delete the activity';
            
        }
        
        return $result;
        
    }
    protected function validate(){
        
    }
    function getList(){
        $result = array();
        $sql = 'SELECT * FROM institute1';
        
        if ( $qR = $this->_db->fetchAll($sql,2)){
           // $result['success'] = 'Successfully deleted the product'; 
            
            
                $header  = '<th>Edit</th><th>Delete</th><th>'.implode('</th><th>',  array_keys($qR[0])).'</th>';
            
            $html = '<table class="table">'.$header;
            foreach($qR as $row){
                
                $html .= '<tr  >';
                $html .= '<td><a href="activity/edit/'.$row['id'].'/" >EDIT</a></td><td><a href="activity/delete/'.$row['id'].'/">DELETE</a></td>';
              foreach($row as $column=>$val){                
                $html .= '<td>'.$val.'</td>';
              }
               
              
              $html.='</tr>';
            }
            $html.='</table>';
            $result['data'] = $html;
            
            }
        else{
            $result['error'] = 'Could not retrieve the activity list';
            
        }
        
        return $result;
    }
    public function saveActivity($data)
            {
        $result = array(); 
        
        $aR = array();
        foreach($data as $col=>$val){
            $col = mysql_real_escape_string($col);
            
            if($col == 'activity_id'){
                $activity_id = mysql_real_escape_string($val);
            }
            else{
                $val = '"'.mysql_real_escape_string($val).'"';
                $aR[] = $col.'='.$val;
            }
            
        }
        $stmt  = implode(',', $aR);
        $sql = 'UPDATE activity SET '.$stmt.' WHERE activity_id='.$activity_id;
        $qR = $this->_db->query($sql);
        
        $qR = $mysqli->query($sql);
        
       if ( $qR ){
            $result['success'] = 'Successfully updated the activity'; 
             $result['activity_id'] = $activity_id;          
            }
        else{
            $result['error'] = 'Could not Insert the activity:'.$mysqli->error;
            
        }
        
        return $result;
    }


    function activityDetails($activityId){
       $sql = array();
       $sql['activity'] = 'SELECT * FROM activity WHERE activity_id='.$activityId;
       $sql['activity'] = 'SELECT * FROM activity WHERE activity_id='.$activityId;       
       
    }
    function processCsv($data){
        //$data = array('table'=>'activity','name'=>'image','size'=>'50000');
        $query = array();
        $vals = array();
        $goodtogo = true;
        $allowedmimes = array ("");
        try{
            if ( ($_FILES[$data['name']]['size'] == 0) || ($_FILES[$data['name']]['size'] > $data['size'])   ){
                $goodtogo = false;
                throw new exception ("Could not upload the file: Invalid File Format");
            }
        }
        catch (exception $e) {
            $result['error'][] = $e->getmessage();
        }

        try {
            $target = "uploads/".$_FILES[$data['name']]['name'].".csv";
            $source  = $_FILES[$data['name']]['tmp_name']; 
            if (!move_uploaded_file ($source,$target) ){
            $goodtogo = false;
            throw new exception ("There was an error moving the file.");
            }
            
        } catch (exception $e) {
            $result['error'][] =  $e->getmessage();
        }
        
        
        if($goodtogo){
                $row = 1;
                if (($handle = fopen($target, "r")) !== FALSE) {
                    while (($line = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        if($row ==1){
                            $cols  = '("'.implode('","',$line ).'")';
                        }else{
                            $vals[] = '("'. implode('","',$line).'")';
                        }
                        $row++;
                    }
                    fclose($handle);
                }
            }
        
         
         
         $query  = 'INSERT INTO '.$data['table']. $cols .' VALUES'.implode(',',$vals); 
          return $query;
   }
    
}


