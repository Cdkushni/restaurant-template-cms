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
			
			//encrypt password
			$encrypt = sha1_salt($pword);
			$salt = $encrypt['salt'];
			$pass = $encrypt['string'];
				
			$sql = "INSERT INTO $db (username, password, salt, email) VALUES ('$uname', '$pass', '$salt', '$email');";
			$result = mysql_query($sql);
			$user_id = mysql_insert_id();
			
			//add permissions
			foreach($sections as $sec){
				$insert = mysql_query("INSERT INTO cms_permissions(user_id, section_id) VALUES('$user_id', '$sec')");
			}
				
			alert("<p><b>Success!</b> User was successfully created.</p>", true);
		
			
			$uname = "";
			$pword = "";
			$pword2 = "";
			$email = "";
			$sections = "";
			
			
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
		
		$sectionqry = mysql_query("SELECT * FROM cms_sections");
		while($sec = mysql_fetch_array($sectionqry)){
			echo "<input type='checkbox' name='permissions[]' value='" .$sec['section_id']. "' /> " .$sec['section_name']. "<br />";
		}
		
		echo "</p></td>";
		echo "</tr>";
		echo "</table>";
		echo "</td>";
		echo "</tr>";
		
		
		echo "<tr>";
		echo "<td class='footer' colspan='3'><input type='submit' name='submit' value='Create User' class='submit' /></td>";
		echo "</tr>";
		
		echo "</table>";
		
		echo "<input type='hidden' name='xssid' value='" .$_COOKIE['xssid'] ."' />";
		echo "<input type='hidden' name='section' value='" .$section ."' />";
		echo "<input type='hidden' name='ID' value='" .$id ."' />";
		echo "</form>";
		
	

}
								
?>