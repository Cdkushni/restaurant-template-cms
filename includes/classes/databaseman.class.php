<?php
/************************
-----dbman Class------
Version: 0.1a
Author: Scott Barkman
Last Modified: Dec 8, 2011
************************/

class Databaseman{
  var $profile = false;
  var $queryprofile = array();
  var $lastquery;

  function __construct($path,$user,$pass,$db,$profile = false){

  	$this->mysqli = new mysqli($path, $user, $pass, $db);
	if ($this->mysqli->connect_errno) {
	    echo "Connect failed: ".$this->mysqli->connect_error;
	    exit();	    
	}else{
		$this->mysqli->query('SET time_zone = "-7:00"');

	}
	$this->counter = 0;

	//handle profiling if necessary
    if($profile){
      $this->mysqli->query('set profiling=1,profiling_history_size=100');
      $this->profile = true;
	  $this->counter = 0;  
    }

  }
  public function escape($data){
  	return $this->msqli->mysqli_real_escape_string($data);
  }
  public function __call($method,$args){
  	if(method_exists($this->mysqli,$method)){  
  		call_user_func(array($this->mysqli,$method),$args);
  	}else{
  		echo "Method:  '".$method."' does not exist.";
  	}
  }

  function __return_call($result){
	return $result;
  }

  function errors(){
	return $this->mysqli->error;  
  }

  function query($sql, $type=''){
	  	
	if ($result = $this->mysqli->query($sql)){
		$this->counter++;

		if ($result->num_rows){
			if ($type==''){
				// no type has been passed. Keep it object oriented. 
				if ($this->profile){

					while($row = $result->fetch_assoc()){
						$resultset[] = $row;
					}
			  		$this->sqlHistory[] = @$sql;
			  		$this->resultHistory[] = @$resultset;
			  		$this->errorHistory[] = @$errors;
			  		
			  		$deb = debug_backtrace();

			  		$this->debHistory[] = array('file'=>substr($deb[0]['file'],strrpos($deb[0]['file'],'/')+1),'line'=>$deb[0]['line']);
					
					$result->data_seek(0);
			  	}

				return $result;
				
			}else{
				switch($type){
					default:
					case MYSQLI_ASSOC:
						while($row = $result->fetch_assoc()){
							if (gettype($row)=='array'){
								$resultset = $row;
							}else{
								$resultset[] = $row;
							}
						}
					break;
					case MYSQLI_NUM:
						while($row = $result->fetch_num()){
							if (gettype($row)=='array'){
								$resultset = $row;
							}else{
								$resultset[] = $row;
							}					
						}
					break;
					case MYSQLI_BOTH:
						while($row = $result->fetch_both()){
							if (gettype($row)=='array'){
								$resultset = $row;
							}else{
								$resultset[] = $row;
							}					
						}
					break;
				}
				//get associative array
				if ($this->profile){
					$deb = debug_backtrace();

			  		@$this->sqlHistory[] = $sql;
			  		@$this->resultHistory[] = $resultset;
			  		@$this->errorHistory[] = $errors;

			  		$this->debHistory[] = array('file'=>substr($deb[0]['file'],strrpos($deb[0]['file'],'/')+1),'line'=>$deb[0]['line']);
			  	}
			  	return $resultset;
			}
		}
	}
 }


  function get_query_profile(){
  		
 	for($i=$this->counter; $i>=0; $i--){
		if ($result = $this->mysqli->query("SHOW PROFILE FOR QUERY ".$this->counter,MYSQLI_USE_RESULT)){
			
			while($row = $result->fetch_row()){
				@$qtime +=$row[1];
			}
			$pastquery = array(
				'query'=>@$this->sqlHistory[$i],
				'exec_time'=>@$qtime,
				'errors' => @$this->errorHistory[$i],
				'resultset'=>print_r(@$this->resultHistory[$i],true),
				'deb' => array(
						'file'=>@$this->debHistory[$i]['file'],
						'line' =>@$this->debHistory[$i]['line']),
				'rows'=> count(@$this->resultHistory[$i]));

			array_push($this->queryprofile,$pastquery);

			$result->close();
		}
	}	
	return array_reverse($this->queryprofile);	
  }
 
}
?>