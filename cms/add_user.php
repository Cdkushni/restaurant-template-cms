<?php

// MAIN ADMINISTRATOR MUST HAVE ID = 1 AND PERMISSIONS = All

if ($section == "Add User") {
	
	$db = "login";
	
	
	if ($submit == "Create User") {
		
		$uname = str_replace("'", "&rsquo;", stripslashes($_POST['uname']));
		$pword = str_replace("'", "&rsquo;", stripslashes($_POST['pword']));
		$pword2 = str_replace("'", "&rsquo;", stripslashes($_POST['pword2']));
		$email = str_replace("'", "&rsquo;", stripslashes($_POST['email'])); 
		$sections = $_POST['permissions'];
		foreach($sections as $sec){
			$userpermissions .= "%%%$sec";
		}
		
		
		//check if username is already there
		$userExists = false;
		$userResult = mysql_query("SELECT * FROM $db");
		while($uRow = mysql_fetch_array($userResult)){
			$dbUser = $uRow['username'];
			
			if($dbUser == $uname){
				$userExists = true;	
			}
		}
		
		//check if email is already there
		$emailExists = false;
		$emailResult = mysql_query("SELECT * FROM $db");
		while($eRow = mysql_fetch_array($emailResult)){
			$dbEmail = $eRow['email'];
			
			if($dbEmail == $email){
				$emailExists = true;	
			}
		}
		
		
		//validation
		if($userExists == false && $emailExists == false && $pword == $pword2 && $pword != "" && $email != "" && $uname != ""){
			
			$query = "SELECT * FROM $db";
			$result = mysql_query($query);
			$num_results = mysql_num_rows($result);
			$num_insert = $num_results+1;
			
			//encrypt password
			$encrypt = sha1_salt($pword);
			$salt = $encrypt['salt'];
			$pass = $encrypt['string'];
				
			$sql = "INSERT INTO $db (id, username, password, salt, email, permissions) VALUES ('$num_insert', '$uname', '$pass', '$salt', '$email', '$userpermissions');";
			$result = mysql_query($sql);
			
				
			alert("<p><b>Success!</b> User was successfully created.</p>", true);
		
			
			$uname = "";
			$pword = "";
			$pword2 = "";
			$email = "";
			$userpermissions = "";
			
			
		}else if($userExists == true){
			alert ("<p><b>Error!</b> A user already exists with that username.</p>", false);
		}else if($emailExists == true){
			alert ("<p><b>Error!</b> A user already exists under that email address.</p>", false);
		}else if($pword != $pword2){
			alert ("<p><b>Error!</b> Your passwords do not match. Please type them again.</p>", false);
		}else{
			alert ("<p><b>Error!</b> Please fill out all the fields.</p>", false);
		}
		
	}
	
		// IMPORTANT -->
		important("<p>Do not use any special characters for your username or password. Limit your characters to lowercase &amp; uppercase letters and numbers.</p>");
		// -->
		
		// create 8 digit password
		/*$valid_nums = array(2, 3, 4, 5, 6, 7, 8, 9);
		$valid_alph = array("a", "b", "c", "d", "e", "f", "g", "h", "j", "k", "m", "n", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");
		
		@$char1 = $valid_alph[rand(0, count($valid_alph))];
		@$char2 = $valid_alph[rand(0, count($valid_alph))];
		@$char3 = $valid_nums[rand(0, count($valid_nums))];
		@$char4 = strtoupper($valid_alph[rand(0, count($valid_alph))]);
		@$char5 = $valid_nums[rand(0, count($valid_nums))];
		@$char6 = $valid_alph[rand(0, count($valid_alph))];
		@$char7 = strtoupper($valid_alph[rand(0, count($valid_alph))]);
		@$char8 = $valid_nums[rand(0, count($valid_nums))];
		
		if(!isset($pword)){
			$pword = $char1 .$char2 .$char3 .$char4 .$char5 .$char6 .$char7 .$char8;
		}*/
	
	
		echo "<form action='' method='post' name='managecontent' enctype='multipart/form-data'>";
		
		echo "<table cellpadding='3' cellspacing='0' border='0' style='width:100%;' class='removepadding'>";
		
		
		echo "<tr>";
		echo "<td style='width:150px;'><p>Username:</p></td>";
		echo "<td colspan='2'><input type='text' name='uname' class='input' value='" .$uname ."' /></td>";
		echo "</tr>";
		
		echo "<tr>";
		echo "<td><p>Password:</p></td>";
		echo "<td width='245px'><input type='password' name='pword' id='p' onkeyup='javascript:testPassword();' value='" .$pword ."' onfocus='javascript:changeStyle(this);' class='input' /></td>";
		echo "<td><img src='../cms/images/password0.jpg' alt='' border='0' name='password_strength' id='password_strength' /></td>";
		echo "</tr>";
		
		echo "<tr>";
		echo "<td><p>Re-type Password:</p></td>";
		echo "<td width='245px'><input type='password' name='pword2' id='p2' onkeyup='javascript:doubleCheckPassword();' value='" .$pword2. "' onfocus='javascript:changeStyle(this);' class='input' /></td>";
		echo "<td><img src='../cms/images/check0.jpg' alt='' border='0' name='password_same' id='password_same' /></td>";
		echo "</tr>";
		
		echo "<tr>";
		echo "<td style='width:150px;'><p>Email:</p></td>";
		echo "<td colspan='2'><input type='text' name='email' class='input' value='" .$email ."' /></td>";
		echo "</tr>";
		
		
		echo "<tr>";
		echo "<td style='width:150px; padding-top:15px; border-top:1px solid #ccc;'><p><b>Permissions:</b></p></td>";
		echo "<td style='padding-top:10px; border-top:1px solid #ccc;' colspan='2'>";
		echo "<table cellpadding='0' cellspacing='5' border='0' width='550px;'>";
		echo "<tr>";
		echo "<td><p>";
		
				
		echo "<input type='checkbox' name='permissions[]' value='Manage Page Content'";
		if(strstr($userpermissions, "Manage Page Content")){ echo " checked='checked'"; }
		echo " /> Manage Page Content<br />";
		echo "<input type='checkbox' name='permissions[]' value='Add Pages'";
		if(strstr($userpermissions, "Add Pages")){ echo " checked='checked'"; }
		echo " /> Add Pages<br />";
		echo "<input type='checkbox' name='permissions[]' value='Manage Garage Doors'";
		if(strstr($userpermissions, "Manage Garage Doors")){ echo " checked='checked'"; }
		echo " /> Manage Garage Doors<br />";
		echo "<input type='checkbox' name='permissions[]' value='Manage Testimonials'";
		if(strstr($userpermissions, "Manage Testimonials")){ echo " checked='checked'"; }
		echo " /> Manage Testimonials<br />";
		echo "<input type='checkbox' name='permissions[]' value='Manage Specials'";
		if(strstr($userpermissions, "Manage Specials")){ echo " checked='checked'"; }
		echo " /> Manage Specials<br />";
		echo "<input type='checkbox' name='permissions[]' value='Manage FAQs'";
		if(strstr($userpermissions, "Manage FAQs")){ echo " checked='checked'"; }
		echo " /> Manage FAQs<br />";
		echo "<input type='checkbox' name='permissions[]' value='Manage Showroom Photos'";
		if(strstr($userpermissions, "Manage Showroom Photos")){ echo " checked='checked'"; }
		echo " /> Manage Showroom Photos<br />";
		
		echo "</p></td>";
		echo "</tr>";
		echo "</table>";
		echo "</td>";
		echo "</tr>";
		
		
		echo "<tr>";
		echo "<td class='footer' colspan='3'><input type='submit' name='submit' value='Create User' class='submit' /></td>";
		echo "</tr>";
		
		echo "</table>";
		
		echo "<input type='hidden' name='username' value='" .$username ."' />";
		echo "<input type='hidden' name='password' value='" .$password ."' />";
		echo "<input type='hidden' name='section' value='" .$section ."' />";
		echo "<input type='hidden' name='ID' value='" .$id ."' />";
		echo "</form>";
		
	

}
								
?>