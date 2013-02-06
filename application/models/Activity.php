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
        //add institute field in the view file
        $output = array(); 
        
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
            $output['success'] = 'Successfully inserted the activity'; 
            $output['activity_id'] = $this->mysqli->insert_id;            
            }
        else{
            $output['error'] = 'Could not Insert the activity:'.$this->mysqli->error;
            
        }
        
        return $output;
    }
    function insertContributors($data){
    // get values to be saved in the contributor table
        $output = array();         
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
            $output['success'] = 'Successfully updated the activity'; 
                  
            }
        else{
            $mysqli->rollback();
            $output['error'] = 'Could not Insert the activity:'.$this->mysqli->error;
            
        }
        
    
        //$mysqli->close();
         $output['activity_id'] = $activityId;   
        return $output;
    }
    public function insertParticipants($data) {
        /**
         * the form has the uploaded files and the fields are upated as per the upload
         * get the uploaded file 
         * validate the template 
         * insert the data to the respective column 
         * get the inserted data - if success - call the next view
         * 
         */
        $output = array();         
        $aR = array();  
        $vals = array();
        $sql = array();
        $activityId  = mysql_real_escape_string ( $data['activity_id'] );
        $output['activity_id'] = $activityId; 
        //$data  = array_map('mysql_real_escape_string', $data);
        //calulate count fields 
        $activitybeneficiaries_number = mysql_real_escape_string ($data['activitybeneficiaries_number']) ;
        $activitybeneficiaries_institute = mysql_real_escape_string ($data['activitybeneficiaries_institute']);
        $activitybeneficiaries_fees = mysql_real_escape_string ($data['activitybeneficiaries_fees']) ; 
        
        $vals = json_decode( $data['participantDetails'] ) ;
        $sql = 'DELETE FROM '.$options['table'].' WHERE activity_id='.$activityId;
        $qR = $this->mysqli->query($sql); 
        $query  = 'INSERT INTO '.$options['table']. $cols .' VALUES'.implode(',',$vals); 
        $qR = $this->mysqli->query($sql); 
        $vals = array(); //decode json sent by front end  
        // 
        // the main activity table
        $stmt = 'activitybeneficiaries_number="'.$activitybeneficiaries_number.'",activitybeneficiaries_institute="'.$activitybeneficiaries_institute.'"';        $sql = 'UPDATE activity SET '.$stmt.' WHERE activity_id='.$activityId;   
        $qR = $this->mysqli->query($sql);  
        if(!$qR){
            $output['error'] = 'Could not Insert the activity:'.$this->mysqli->error; 
            return $output; 
        }
        $output['success'] = 'Successfully updated the activity'; 
         
        return $output; 
    }
    function insertOutcomes ($data) {
        // get values to be saved in the contributor table
        $output = array();         
        $aR = array();  
        $vals = array();
        $sql = array();
        $activityId  = mysql_real_escape_string ( $data['activity_id'] );
        $output['activity_id'] = $activityId; 
        $menteeslistFile = $_FILES['activityoutcome_menteeslist']  ;
        $mentorslistFile = $_FILES['activityoutcome_mentorslist']  ;
        //$data  = array_map('mysql_real_escape_string', $data);
        /* reading the files for mentees and mentor list  */
        if($menteeslistFile['size'] != 0){
            $options = array('mime'=>'text/csv','size'=>50000);
            $options['fileFormat'] = array("S. No.","Category","First name","Last name","Date of birth","M/F","email","phone","Mentored by whom (first name, last name)");
            $options['cols'] = array();
            $options['cols']  = "(category,first_name,last_name,dob,gender,email,phone,mentored_by,activity_id,human_id)";
            $options['table'] = 'activity_outcome_mentees_list';
            $result = $this->_uploadFile($menteeslistFile,$options);
            if($result['error'] ){
               $output['error'] = $result['error'];
			   return $output;                
             }
            if (($handle = fopen($result['data'], "r")) == FALSE) {
            $output['error'] =  'Unknown Error Occured,Please Try again';
            return $output; 
            }
            /* all good so go ahead */
            $row = 1;
            while (($line = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if($row ==1){

                    //$cols  =  '("'.implode('","',$line ).'")';
                    /* put file format logic here */
                    for($count =0;$count<count($line);$count++){
                        if( !($line[$count]==$options['fileFormat'][$count]) ){
                            $output['error'] =  'Incorrect File Format uploaded , please correct the file column format and try again';
                        return $output; 
                        } 
                    }             

                }
                else{
                    array_shift($line); /* to remove first S No column*/ 
                    $vals[] = '("'. implode('","',$line).'",'.$activityId.',null)';
                }
                
                
                
                $row++;
            }
            fclose($handle);
            
        }
        else{
            $menteeslist = $data['activityoutcome_menteeslist']  ;
            foreach($menteeslist as $key=>$val){
            $val = explode('|',mysql_real_escape_string( $val) );
            $vals[]= '('.$activityId.',"'.implode('","',$val) .'")' ;
            }
        }
         //updating the contributor table
        
        $sql[] = 'DELETE FROM activity_outcome_mentees_list WHERE activity_id='.$activityId;        
        $sql[]='INSERT INTO activity_outcome_mentees_list'.$options['cols'].' VALUES'.implode(',',$vals);      
        $vals = array();
        
        if($mentorslistFile['size']!=0){
            $options = array('mime'=>'text/csv','size'=>50000);
            $options['fileFormat'] = array('S. No.','Apellation','First name','Last name','Designation','Organisation','email','phone','Mentoring whom (first name, last name)');
            $options['cols'] = array();
            $options['cols']  = "(apellation,first_name,last_name,dob,gender,email,phone,mentoring_whom,activity_id,human_id)";
            $options['table'] = 'activity_outcome_mentors_list';
            $result = $this->_uploadFile($mentorslistFile,$options);
            if($result['error'] ){
               $output['error'] = $result['error'];
			   return $output;            
             }
            if (($handle = fopen($result['data'], "r")) == FALSE) {
            $output['error'] =  'Unknown Error Occured,Please Try again';
            return $output; 
            }
            /* all good so go ahead */
            $row = 1;
            while (($line = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if($row ==1){

                    //$cols  =  '("'.implode('","',$line ).'")';
                    /* put file format logic here */
                    for($count =0;$count<count($line);$count++){
                        if( !($line[$count]==$options['fileFormat'][$count]) ){
                            $output['error'] =  'Incorrect File Format uploaded , please correct the file column format and try again';
                        return $output; 
                        } 
                    }             

                }
                else{
                    array_shift($line); /* to remove first S No column*/ 
                    $vals[] = '("'. implode('","',$line).'",'.$activityId.',null)';
                }
                
                $row++;
            }
            fclose($handle);
        }else{
            $mentorslist = $data['activityoutcome_mentorslist'] ;
            foreach($mentorslist as $key=>$val){
            $val = explode('|',mysql_real_escape_string( $val) );
            $vals[]= '('.$activityId.',"'.implode('","',$val) .',null")' ;
            }
        }
        //updating the presenter table
        
        $sql[] = 'DELETE FROM activity_outcome_mentors_list WHERE activity_id='.$activityId;  
        $sql[] = 'INSERT INTO activity_outcome_mentors_list'.$options['cols'].' VALUES'.implode(',',$vals);
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
            try{ 
                $qR = $qR && $this->mysqli->query($value);
                //throw new exception('Could not update mentors list: Query='.$val.'  ['.$this->mysqli->error.']');
                }
            catch(Exception $e){
                $output['error'] = $e->getMessage();
                return $output;
            }           
        }
       if ( (bool)$qR ){
            $out = $this->mysqli->commit();                    
            $output['success'] = 'Successfully updated the activity'; 
                  
            }
        else{
            $this->mysqli->rollback();
            $output['error'] = 'Could not Insert the activity:'.$this->mysqli->error;
            $output['data'] = implode(';',$sql);
            
        }
        
        
        
           
        
        //$mysqli->close();
        return $output;
    }
    function deleteActivity($activityId) {
        $output = array(); 
       
        //cascade delete the id from the tables 
        // start transaction and rollback if not commited
        $sql = 'SELECT * FROM institute1';
        //$sql = 'DELETE * FROM institute1 WHERE id='.$activityId;
       
        if ( $qR = $this->_db->query($sql)  ){
            $output['success'] = 'Successfully deleted the product'; 
            $output['data'] = $qR;            
            }
        else{
            $output['error'] = 'Could not delete the activity';
            
        }
        
        return $output;
        
    }
    protected function validate(){
        
    }
    function getList(){
        $output = array();
        $sql = 'SELECT * FROM institute1';
        
        if ( $qR = $this->_db->fetchAll($sql,2)){
           // $output['success'] = 'Successfully deleted the product'; 
            
            
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
            $output['data'] = $html;
            
            }
        else{
            $output['error'] = 'Could not retrieve the activity list';
            
        }
        
        return $output;
    }
    public function saveActivity($data)
            {
        $output = array(); 
        
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
            $output['success'] = 'Successfully updated the activity'; 
             $output['activity_id'] = $activity_id;          
            }
        else{
            $output['error'] = 'Could not Insert the activity:'.$mysqli->error;
            
        }
        
        return $output;
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
            $output['error'][] = $e->getmessage();
        }

        try {
            $target = "uploads/".$_FILES[$data['name']]['name'].".csv";
            $source  = $_FILES[$data['name']]['tmp_name']; 
            if (!move_uploaded_file ($source,$target) ){
            $goodtogo = false;
            throw new exception ("There was an error moving the file.");
            }
            
        } catch (exception $e) {
            $output['error'][] =  $e->getmessage();
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
   
   protected function _uploadFile($data,$options){
        $query = array();
        $vals = array();
        $goodtogo = true;
        $allowedMimes = $options['mime'];
        $fileFormat = $options['fileFormat']; // array('column1','column2','column3')
        $size = $options['size'];
        
       
        if ( ($data['size'] == 0) || ($data['size'] > $size)   ){
            $goodtogo = false;
            $result['error'] =   "Could not upload the file: Invalid File Format";
            return $result;
        }
      

        try {
            $target = "../uploads/".$data['name'].".csv";
            $source  = $data['tmp_name']; 
            if (!move_uploaded_file ($source,$target) ){
            $goodtogo = false;
            throw new exception ("There was an error moving the file.");
            }
            
        } catch (exception $e) {
            $result['error'] =  $e->getmessage();
            return $result;
        }
        
        if(!$goodtogo){
            $result['error'] =  'File Not Uploaded,Please Try again';
            return $result;
        }
        $result['data'] = $target; 
        $result['error'] = "";
        return $result;
         
       
   }
   public function result($msg){
       $result = $msg;       
       $result['activity_id'] = $data['activity_id'];
       return $result;
   }
   function uploadParticipants($data){
        $output = array();         
        $aR = array();  
        $vals = array();
        $sql = array();
        $activityId  = mysql_real_escape_string ( $data['activity_id'] );
        $output['activity_id'] = $activityId; 
        //$data  = array_map('mysql_real_escape_string', $data);
        //calulate count fields 
        //$activitybeneficiaries_number = mysql_real_escape_string ($data['activitybeneficiaries_number']) ;
        //$activitybeneficiaries_institute = mysql_real_escape_string ($data['activitybeneficiaries_institute']);
        //$activitybeneficiaries_fees = mysql_real_escape_string ($data['activitybeneficiaries_fees']) ;         
       $participantFile = $_FILES['fileParticipant']  ;  
       
        if($participantFile){
            $options = array('mime'=>'text/csv','size'=>50000);
            $options['fileFormat'] = array("S. No.","Category","First name","Last name","Date of birth","M/F","email","phone","Performance remarks");
            $options['cols']  = "(category,first_name,last_name,dob,gender,email,phone,performance_remarks,activity_id,human_id)";
            $options['table'] = 'activity_participants';
            $result = $this->_uploadFile($participantFile,$options);
            if($result['error'] ){
               $output['error'] = $result['error'];
			   return $output;
             }
            if (($handle = fopen($result['data'], "r")) == FALSE) {
            $output['error'] =  'Could not open the file,Please Try again';
            return $output; 
            }
            /* all good so go ahead */
            $row = 1;
            while (($line = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if($row ==1){

                    //$cols  =  '("'.implode('","',$line ).'")';
                    /* put file format logic here */
                    for($count =0;$count<count($line);$count++){
                        if( !($line[$count]==$options['fileFormat'][$count]) ){
                            $output['error'] =  'Incorrect File Format uploaded , please correct the file column format and try again';
                        return $output; 
                        } 
                    }             

                }
                else{
                    array_shift($line); /* to remove first S No column*/ 
                    $vals[] = '("'. implode('","',$line).'",'.$activityId.',null)';
                }
                    
                
                
                $row++;
            }
            fclose($handle);
            $cols = $options['cols'];
            $query  = 'INSERT INTO '.$options['table']. $cols .' VALUES'.implode(',',$vals); 
 
            $this->mysqli->autocommit(FALSE);
            $qR = $this->mysqli->query($query);

            if(!$qR){
                $this->mysqli->rollback();
                $output['error'] = 'Could not Insert the data:'.$this->mysqli->error;  
                return $output; 
                 }

            $out = $this->mysqli->commit();
            $this->mysqli->autocommit(TRUE);
            
            $qR = $this->mysqli->query('SELECT DISTINCT COUNT(*) as count FROM activity_participants WHERE activity_id='.$activityId);
            $row = $qR->fetch_assoc();
            $activitybeneficiaries_number  = $row['count'] ;
            
            $qR = $this->mysqli->query('SELECT DISTINCT COUNT(*) as count FROM activity_participants WHERE activity_id='.$activityId.' AND category="student"');
            $row = $qR->fetch_assoc();
            $activitybeneficiaries_institute  = $row['count'] ;
            
            $output = array('activitybeneficiaries_institute'=>$activitybeneficiaries_institute,
                'activitybeneficiaries_number'=>$activitybeneficiaries_number,
                'data' =>$vals);
            
            return json_encode($output);
       
        }
        
        
        
   }
}


