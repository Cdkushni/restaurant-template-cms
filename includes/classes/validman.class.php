<?php 
/***********************************
		   validMAN
	   Version: 0.1a
	  December 9, 2011
***********************************/

class Validman{
	function __construct($errorClass='errorField'){
		
		$this->errorFields = array();
		$this->errorMessages = array();
		$this->errors = false;
		$this->errorClass = $errorClass;
		
		$this->messages['required'] = "is required.";
		$this->messages['numeric'] = "must be numeric.";
		$this->messages['non_numeric'] = "must not be numeric.";
		$this->messages['email'] = "must be a valid email address.";
		$this->messages['postal_code'] = "must be a valid postal code.";
		$this->messages['captcha'] = "is incorrect.";
		$this->messages['min'] = "A minimum of {var} {name} is required.";
		$this->messages['max'] = "A maximum of {var} {name} is required.";
		$this->messages['minsize'] = "{name} must be at least {var} characters long.";
		$this->messages['maxsize'] = "{name} can't be longer than {var} characters.";
				
	}
		
	public function errorField($field){
		if (in_array($field,$this->errorFields)){
			return $this->errorClass;	
		}
	}
	function errors(){
		return $this->errors;	
	}
	
	public function validate($fields, $values){
		$this->errorFields = array();
		$this->errorMessages = array();
		
		foreach($fields AS $key=>$data){
			$arraypresent = strpos($data[0],'|');
			
			$flags = array();
			if ($arraypresent>0){
				//array present	

				$flags = explode('|',$data[0]);
			
			}else{
				$flags[0] = $data[0];
			}


			foreach ($flags AS $flag){
				//check to see if already in the errorFields array due to being required and empty
				if (!in_array($key, $this->errorFields)){
					//continue with the validation
					if (strpos($flag,':')){

						$var = substr($flag,strpos($flag,':')+1);

						$flag = substr($flag,0,(strpos($flag,':')));
				
						if (is_callable(array('validman','_'.$flag))){
							if (!call_user_func(array('validman','_'.$flag),$var,$values[$key])){
								$this->errors = true;
								array_push($this->errorFields,$key);
								$tmpmessage = str_replace('{name}',$data[1], $this->messages[$flag]);
								$tmpmessage = str_replace('{var}',$var,$tmpmessage);
								$this->errorMessages[$key] = array($flag, $tmpmessage);
							}
						}
					}else{
						if (is_callable(array('validman',"_".$flag))){
							if (!call_user_func(array('validman',"_".$flag),$values[$key])){
						
								$this->errors = true;
								array_push($this->errorFields,$key);
								$this->errorMessages[$key] = array($flag,$data[1]." ".$this->messages[$flag]);
							}
						}
					}
				}	
			}
		}
		if ($this->errors){
			
			return false;
		}else{
			
			return true;
		}
	}
	function alertErrors(){
		if ($this->errors){
			echo "<div class='error'>Error</div>";
			echo "<div class='alert'><p>";
				foreach($this->errorMessages AS $key=> $data){
					if ($data[1]!=''){
						echo $data[1] ."<br />";
					}
				}
			echo "</p></div>";
		}		
	}
	function returnErrors(){
		if ($this->errors){
			$error = "<div class='error'>Error</div>";
			$error .="<div class='alert'><p>";
				foreach($this->errorMessages AS $key=>$data){
					if ($data[1]!=''){
						$error .= $data[1]."<br />";
					}
				}
			$error .= "</p></div>";
			return $error;
		}
	}
	function setError($field, $message){
		$this->errors = true;
		array_push($this->errorFields, $field);
		$this->errorMessages[$field] = array($field,$message);
	}
		
	private function _min($var, $data){
		if (count($data) < $var){
			return false;	
		}
		return true;
	}
	
	private function _max($var, $data){

		if (count($data) > $var){
			return false;	
		}
		return true;
	}
	private function _minsize($var, $data){
		if (strlen($data) < $var){
			return false;
		}
		return true;
	}
	private function _maxsize($var, $data){
		if (strlen($data) > $var){
			return false;
		}
		return true;
	}	
	private function _numeric($data){
		if (!is_numeric($data) && $data!=''){
			return false;
		}
		return true;
	}
						  
	private function _required($data){
		if ($data==""){
			return false;
		}
		return true;
	}
	public function captcha_image(){
		return "<img src='".$path."includes/formvalidator/img.php?a=".time()."'/>";
	}
	private function _captcha($data){
		if (is_file('includes/formvalidator/SPAF_FormValidator.class.php')){
	
			include('includes/formvalidator/SPAF_FormValidator.class.php');
			$obj = new SPAF_FormValidator();
	
			if ($obj->validRequest($data)){
				$obj->destroy();
				return true;
			}else{

				return false;
			}	
		}
	}
	
	public function _email($data){
		if ($data!=''){
			if (preg_match("/[a-z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/", $data)) {
				return true;
			}
	
		}
  		
		return false;
	}
}