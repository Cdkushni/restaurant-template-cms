<?php 
/***********************************
		   authMAN
	   Version: 0.1a
	  December 9, 2011
***********************************/
class Authman{
	function __construct(){
		
		$this->config->requireEmail = true;				//Force Email to be required. Default: true;
		$this->config->unique = 'email';				//Fields to force as unique. (Values: username, email) Default: email
		$this->config->validateAgainst = 'email';		//Fields to validate against. (Values: username,email) Default: email
		$this->config->autologin = true;				//Autologin user after successful registration. Default: true
		$this->config->registrationValidation = false;	//Determines if validation is required
		$this->default_profile_image = '/images/pets/default.jpg';
	}
	public function is_active_member(){
		//checks to see if current logged in member is a valid member
		if ($this->is_logged_in()){
			$result = mysql_query("SELECT expires FROM registrations WHERE account_id IN (SELECT account_id FROM accounts WHERE account_id = '".$this->get_account_id()."' AND banned <> '1') ORDER BY expires DESC LIMIT 1");

			if (mysql_num_rows($result)){
				if (strtotime(mysql_result($result,0,'expires'))>time()){
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}
	}
	public function get_profile_gps($id=''){
		if ($id==''){
			if ($this->is_logged_in()){
				$result = mysql_query("SELECT * FROM profiles WHERE account_id = '".$this->get_account_id()."' AND gps_warning <> '1'");
			}
		}else{
			$result = mysql_query("SELECT * FROM profiles WHERE account_id = '".$id."' AND gps_warning <> '1'");
		}

		if (mysql_num_rows($result)){

			$row = mysql_fetch_array($result,1);
			
			if ($row['gps_lat']=='' && $row['gps_long']==''){
		 		//no gps coords found, grab new ones. 
		 		require_once ('geoman.class.php');
		 		$geoman = new geoman();
				$response = $geoman->geocode($row['street_address'].' '.$row['city'].' '.$row['province']. ' '.$row['postal_code'].' '.$row['country']);
		 		if (count($response->Placemark)>1){
		 			//may not be accurate. Multiple places detected
					//update gps warning
		 			$result2 = mysql_query("UPDATE profiles SET gps_warning = '1' WHERE account_id = '".$row['account_id']."'");
		 			return false;
		 		}else{
		 			//success
					$result2 = mysql_query("UPDATE profiles SET gps_lat = '".$response->Placemark[0]->Point->coordinates[1]."', gps_lng = '".$response->Placemark[0]->Point->coordinates[0]."' WHERE account_id = '".$row['account_id']."'");
					return array('gps_lat'=>$response->Placemark[0]->Point->coordinates[1],'gps_lng'=>$response->Placemark[0]->Point->coordinates[0]);
				}
		 	}else{
		 		return array('gps_lat'=>$row['gps_lat'],'gps_lng'=>$row['gps_lng']);
		 	}
		}else{
			return false;
		}
	}
	public function get_country(){
		$result = mysql_query("SELECT country FROM profiles WHERE account_id = '".$_SESSION['auth']['account_id']."'");

		return mysql_result($result,0,'country');
	
	}
	public function get_expiry(){
		if ($this->is_logged_in()){
			$result = mysql_query("SELECT expires FROM registrations WHERE account_id = '".$this->get_account_id()."' ORDER BY expires DESC LIMIT 1");
			if (mysql_num_rows($result)){
				return mysql_result($result,0,'expires');
			}else{
				return false;
			}
		}
	}

	public function get_email(){
		if ($this->is_logged_in()){
			return $_SESSION['auth']['email'];
		}else{
			return false;
		}
	}
	public function get_account_id(){
		if ($this->is_logged_in()){
			return $_SESSION['auth']['account_id'];	
		}else{
			return false;	
		}
	}
	public function get_username(){
		if ($this->is_logged_in()){
			return $_SESSION['auth']['username'];
		}else{
			return false;	
		}
	}
	public function get_last_login(){
		if ($this->is_logged_in()){
			return $_SESSION['auth']['last_login'];
		}else{
			return false;
		}
	}
	public function get_name(){
		if ($this->is_logged_in()){
			return $_SESSION['auth']['name'];
		}else{
			return false;	
		}
	}
	public function get_profile_image($id=''){
		if ($id!=''){
			$result = mysql_query("SELECT * FROM pets WHERE account_id = '".$id."' ORDER BY use_as_profile DESC limit 1");
			if (mysql_num_rows($result)){
				if (is_file('images/pets/'.mysql_result($result,0,'pet_id').'.jpg')){
					return '/images/pets/'.mysql_result($result,0,'pet_id').'.jpg';
				}else{
					return $this->default_profile_image;
				}
			}else{
				return $this->default_profile_image;
			}
		}else{
			//current user
			
			$result = mysql_query("SELECT * FROM pets WHERE account_id = '".$this->get_account_id()."' ORDER BY use_as_profile DESC");
			if (mysql_num_rows($result)){
				if (is_file('images/pets/'.mysql_result($result,0,'pet_id').'.jpg')){

					return '/images/pets/'.mysql_result($result,0,'pet_id').'.jpg';

				}
			}
			return $this->default_profile_image;			
		}
	}	
	//*** Register
	//---------------------------
	// 	Registers users based on credentials provided
	//
	//
	//*** Returns
	//--------------------------
	//	Email required
	//	Username in use
	//	Email in use
	function register($username, $password, $email='',$autologin=true){
		if ($this->config->requireEmail){
			if ($email==''){
				$message['error'];
				$message['errorMessage'] = "Email required";
				return $message;
			}
		}
		if ($this->config->unique=='username'){
			//check duplicate username
			$dupcheck = mysql_query("SELECT * FROM acounts WHERE username = '".$email."'");
			if (mysql_num_rows($dupcheck)){
				$message['error'] = true;
				$message['errorMessage'] = "Username in use";
				return $message;
			}
			
		}else if ($this->config->unique=='email'){
			//check duplicate email
			$dupcheck = mysql_query("SELECT * FROM accounts WHERE email = '".$email."'");
			if (mysql_num_rows($dupcheck)){
				$message['error'] = true;
				$message['errorMessage'] = "Email in use";
				return $message;
			}
			
		}		
	
		//account not found, unique values. 
		$cred = $this->_sha1_salt($password);
		$result = mysql_query("INSERT INTO accounts (username, password, salt, email, date_joined) VALUES ('".$username."','".$cred['password']."','".$cred['salt']."','".$email."',NOW())") or die(mysql_error());
		if(!mysql_error()){
			//inserted
			if ($autologin){
				if ($this->config->unique=='username'){
					$this->login($username,$password);	
				}else{
					$this->login($email,$password);	
				}
			}
			return mysql_insert_id();
		}	
		return mysql_error();		
		
	}	
	function confirm_password($password){
		switch($this->config->validateAgainst){
			case 'username':
				$result = mysql_query("SELECT accounts.* FROM accounts WHERE username = '".$this->get_username()."'");
			break;
			case 'email':
				$result = mysql_query("SELECT accounts.* FROM accounts WHERE email = '".$this->get_email()."'");
			break;
		}
		if (mysql_num_rows($result)){
			$account = mysql_fetch_array($result,1);
			$suppliedpass = $this->_sha1_salt($password,$account['salt']);
			if ($suppliedpass['password']==$account['password']){
				return true;
			}
		}
		return false;
	}
	//*** Login
	//----------------------------------------
	// Logs in user with provided credentials
	//
	//*** Returns
	//---------------
	// 	true / false	
	function login($account='', $password='', $rememberme=false){
		switch($this->config->validateAgainst){
			case 'username':
				$result = mysql_query("SELECT accounts.*, profiles.first_name, profiles.last_name FROM accounts LEFT JOIN profiles ON profiles.account_id = accounts.account_id WHERE username='".$account."'");
			break;
			case 'email':
				$result = mysql_query("SELECT accounts.*, profiles.first_name, profiles.last_name FROM accounts LEFT JOIN profiles ON profiles.account_id = accounts.account_id WHERE email = '".$account."'");
			break;
		}
		if (mysql_num_rows($result)){
			//valid user supplied
			$account = mysql_fetch_array($result,1);
			$suppliedpass = $this->_sha1_salt($password,$account['salt']);
		
			if ($account['password']==$suppliedpass['password']){
				
				//check to see if account has been validated
				if (($this->config->registrationValidation && $account['validated']=='1') || $this->config->registrationValidation==false){
				
					$_SESSION['auth']['account_id'] = $account['account_id'];
					$_SESSION['auth']['email'] = $account['email'];
					$_SESSION['auth']['username'] = $account['username'];
					$_SESSION['auth']['name'] = $account['first_name'].' '.$account['last_name'];
					$_SESSION['auth']['last_login'] = time();
					$_SESSION['auth']['facebook'] = $account['facebook'];
					//update last login
					$result = mysql_query("UPDATE accounts SET last_login = now() WHERE account_id = '".$account['account_id']."'");
					//valid login. 
					if ($rememberme){
						$remeid = $this->_generate_remeid();
						mysql_query("UPDATE accounts SET remeid = '".$remeid."', remetimeout='".date('Y-m-d',strtotime('+1 month'))."' WHERE account_id = '".$account['account_id']."'");

						$_COOKIE['remeid'] = $remeid;

						setcookie('auth[remeid]',$remeid,(time()+60*60*24*30),'/');
						
					}else{
						//remove remember me. 
						unset($_COOKIE['auth']['remeid']);
						mysql_query("UPDATE accounts SET remeid = '', remetimeout='' WHERE account_id = '".$account['account_id']."'");
											
					}
					return true;			
				}else{
					//validated
					return "validation error";							
				}
			}
		}
		
		return false;
	}
	//*** logout
	//----------------------------
	//  logs a user out, and destroys any remember me cookie
	//
	//*** Returns
	//----------------------------
	//	<nothing>
	function logout(){
		unset($_SESSION['auth']);
		setcookie('auth[remeid]','--',time() - 3600,'/');
	}
	//*** is_logged_in
	//--------------------------
	// 	checks if user is logged in, if not, will check to see if remember me cookie is present
	//
	//*** Returns
	//--------------------------
	//	true/false
	function is_logged_in(){
		//check if user logged in
		if (isset($_SESSION['auth']['account_id'])){
			return true;
		}else{
			if (isset($_COOKIE['auth']['remeid'])){
				//remember cookie is there, check to see if it matches an account	
				$result = mysql_query("SELECT * FROM accounts LEFT JOIN profiles ON profiles.account_id = accounts.account_id WHERE remeid = '".$_COOKIE['auth']['remeid']."' AND remetimeout >= now()");
				if (mysql_num_rows($result)){
					//account exist, log user in
					$account = mysql_fetch_array($result);
					$_SESSION['auth']['account_id'] = $account['account_id'];
					$_SESSION['auth']['name'] = $account['first_name'].' '.$account['last_name'];
					$_SESSION['auth']['email'] = $account['email'];
					$_SESSION['auth']['username'] = $account['username'];
					$_SESSION['auth']['name'] = $account['first_name'];

					return true;
				}
			}
		}
		
		return false;
	}
	function is_admin(){
		if ($_COOKIE['auth']['role']=='9001'){
			return true;	
		}
		return false;
	}
	function is_role($role_id){
	
	}
	
	function generate_reset_code($id){
		
		$resetcode = md5($this->_generate_password(15,true));
		$result = mysql_query("UPDATE accounts SET resetcode = '".$resetcode."',  resettimeout= NOW() + INTERVAL 1 HOUR WHERE account_id = '".$id."'");
		
		return $resetcode;	
		
	}
	
	public function changePassword($password, $salt="", $account_id=''){
		if ($account_id == ''){
			$account_id = $this->get_account_id();
		}
		$cred = $this->_sha1_salt($password,$salt);
		$result = mysql_query("UPDATE accounts SET password = '".$cred['password']."', salt='".$cred['salt']."',resetcode='',resettimeout='' WHERE account_id = '".$account_id."'");
		if (!mysql_error()){
			return true;
		}else{
			return mysql_error();
		}
		
	}
	//*** get_profile
	//----------------------------
	//  retrieves a users profile from profile table
	//
	//*** Returns
	//----------------------------
	//	<nothing>
	function get_profile($id=''){
		if ($id==''){
			if ($this->is_logged_in()){
				//logged in
				$id = $this->get_account_id();
			}
		}

		$result = mysql_query("SELECT * FROM profiles WHERE account_id = '".$id."'");
		if (mysql_num_rows($result)){			
			return mysql_fetch_array($result,1);
		}else{
			return "Couldn't find account profile.";	
		}
	}
	//encrypts supplied password using salt provided. If no salt/password detected, it will generate.
	public function _sha1_salt($password='', $salt=''){
		 	
		if ($password == ''){
			$password = $this->_generate_password(10);	
		}

		if ($salt!=""){
			//use hash stored in db
			$hash['salt'] = $salt;	
		}else{
			//generate new hash	
			$chars = str_split('~`!@#$%^&*()[]{}-_\/|\'";:,.+=<>?');
		    $keys = array_rand($chars, 10);
			
		    foreach($keys as $key) {
		        $hash['salt'][] .= $chars[$key];
		    }
		
		    $hash['salt'] = implode('', $hash['salt']);
		    $hash['salt'] = sha1($hash['salt']);
		}

		$hash['password'] = sha1($hash['salt'].$password.$hash['salt']);
    	return $hash;
	}
	
	public function _generate_password($length,$special=false){
		
		if ($special){
			$chars = str_split('abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789!@#$%^&*()_+=-~');
		}else{
			$chars = str_split('abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789');
		}
		
		$keys = array_rand($chars,$length);
		
		foreach($keys as $key){
			$pass .= $chars[$key];	
		}
	
		return $pass;
	
	}	
	public function _generate_remeid($length = 32){
		$chars = str_split('abcdefghijklmnopqrstuvwxyz!@#$%^&*()_+=-~');
		$keys = array_rand($chars,$length);
		
		$remeid = '';
		foreach($keys as $key){
			$remeid .= $chars[$key];
		}
		
			
		return md5($remeid);
	}
}

?>