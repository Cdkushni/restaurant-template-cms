<?php
if ($section == "Edit Login Information") {
	
	$db = "login";
	
	$save = $_POST['save'];
	
	$saved = false;
	
	if ($save == true) {
	
		$u = stripslashes($_POST['u']);
		$p = stripslashes($_POST['p']);
		$p2 = stripslashes($_POST['p2']);
		
		$ID = $_POST['ID'];
		
		//check if user is already there
		$userExists = false;
		$userResult = mysql_query("SELECT * FROM $db WHERE user_id != '$ID'");
		while($uRow = mysql_fetch_array($userResult)){
			$dbUser = $uRow['username'];
			
			if($dbUser == $u){
				$userExists = true;	
			}
		}
		
		if($p == $p2 && $p != "" && $u != "" && $userExists == false){
			
			$encrypt = sha1_salt($p);
			$salt = $encrypt['salt'];
			$pass = $encrypt['string'];

			
			$sql = mysql_query("UPDATE $db SET
			username = '$u',
			password = '$pass',
			salt = '$salt'
			WHERE user_id = '$ID'")or die("login error");
			
			
			alert("<p><b>Success!</b> Login Information was successfully saved.</p>", true);
			
			$saved = true;
			
			echo "<form action='' method='post'>";
			echo "<input type='submit' name='submit' value='Continue' class='submit' />";
			
			echo "<input type='hidden' name='xssid' value='" .$_COOKIE['xssid']. "'/>";
			echo "<input type='hidden' name='section' value='' />";
			echo "</form>";
		
		}else if($userExists == true){
			alert ("<p><b>Error!</b> A user already exists with that username.</p>", false);
		}else if($p != $p2){
			alert("<p><b>Error!</b> Your passwords do  not match. Please type them again.</p>", false);
		}else{
			alert("<p><b>Error!</b> Please fill out all the fields.</p>", false);
		}
		
	} 
	
	if (!$saved) {
		$query = "SELECT * FROM $db WHERE user_id='$cms_userid'";
		$result = mysql_query($query);
		$row = mysql_fetch_array($result);
		
		$id = $row['user_id'];
		$u = $row['username'];
		$p = $password;
		
		
		// IMPORTANT -->
		important("<p>Do not use any special characters for your username or password. Limit your characters to lowercase &amp; uppercase letters and numbers.</p><p>Password strength is determined by the following:</p><ul><li>Longer than 4 characters</li><li>Contains one uppercase letter</li><li>Contains one numeral</li></ul>");
		// -->
		
		
		
		
		echo "<form action='' method='post' name='managelogin' id='managelogin' enctype='multipart/form-data'>";
		
		echo "<table cellpadding='3' cellspacing='0' border='0' style='width:100%;' class='removepadding'>";
		
		echo "<tr>";
		echo "<td style='width:150px;'><p>Username:</p></td>";
		echo "<td style='width:240px;'><input type='text' name='u' class='input' value='" .$u ."' /></td>";
		echo "</tr>";
		
		echo "<tr>";
		echo "<td><p>Password:</p></td>";
		echo "<td><input type='password' name='p' id='p' onkeyup='javascript:testPassword();' value='" .$p ."' onfocus='javascript:changeStyle(this);' class='input' /></td>";
		echo "<td><img src='../cms/images/password0.jpg' alt='' border='0' name='password_strength' id='password_strength' /></td>";
		echo "</tr>";
		
		echo "<tr>";
		echo "<td><p>Re-type Password:</p></td>";
		echo "<td><input type='password' name='p2' id='p2' onkeyup='javascript:doubleCheckPassword();' onfocus='javascript:changeStyle(this);' class='input' /></td>";
		echo "<td><img src='../cms/images/check0.jpg' alt='' border='0' name='password_same' id='password_same' /></td>";
		echo "</tr>";
		
		echo "<tr>";
		echo "<td class='footer' colspan='3'><input class='submit' type='button' value='Save Changes' name='btn' onclick='javascript:testLogin();'  /></td>";
		echo "</tr>";
		
		echo "</table>";
		
		
		echo "<input type='hidden' name='xssid' value='" .$_COOKIE['xssid'] ."' />";
		echo "<input type='hidden' name='section' value='" .$section ."' />";
		echo "<input type='hidden' name='ID' value='" .$id ."' />";
		echo "<input type='hidden' name='save' value='true' />";
		echo "</form>";
		
		echo "<script language='javascript' type='text/javascript'>testPassword();</script>";							
										
	}

}
?>