<?php
if ($section == "") {
	$db = "login";
	$query = "SELECT * FROM $db";
	$result = mysql_query($query);
	$num_results = mysql_num_rows($result);
		
	echo "<td><p>Total CMS Users</p></td>";
	echo "<td><p>" .$num_results ."</p></td>";
}

if ($section == "Edit/Delete User") {
	
	$db = "login";
	
	$ID = $_POST['ID'];
	
	
	if ($submit == "Cancel") {
		$ID = "";
		
	} else if ($submit  == "Delete") {
		$confirm = $_POST['confirm'];
		
		if ($confirm == "Yes") {
			
			$sql = mysql_query("ALTER TABLE $db ORDER BY id")or die("Could Not Re-order Database");
			
			$result = mysql_query("DELETE FROM $db where id=$ID") or die ("could not delete the record");
				
			$delete = mysql_query("ALTER TABLE $db DROP id") or die ("could not delete the old id field");
			$rebuild = mysql_query("ALTER TABLE $db ADD id INT NOT NULL PRIMARY KEY AUTO_INCREMENT") or die ("did not rebuild id field");
			
			$sql = mysql_query("ALTER TABLE $db ORDER BY id DESC")or die("Could Not Re-order Database");
			
			alert ("<p><b>Success!</b> User was successfully deleted.</p>", true);
			$ID = "";
			
		} else {
			alert ("<p><b>Error!</b> You must confirm deletion by selecting Yes from the pulldown menu.</p>", false);
		}
		
	
	} else if ($submit == "Save Changes") {
		
		$uname = str_replace("'", "&rsquo;", stripslashes($_POST['uname']));
		$pword = str_replace("'", "&rsquo;", stripslashes($_POST['pword']));
		$pword2 = str_replace("'", "&rsquo;", stripslashes($_POST['pword2']));
		$email = str_replace("'", "&rsquo;", stripslashes($_POST['email'])); 
		
		if($ID != 1){
			$sections = $_POST['permissions'];
			foreach($sections as $sec){
				$userpermissions .= "%%%$sec";
			}
		}else{
			$userpermissions = "All";	
		}
		
		//check if user is already there
		$userExists = false;
		$userResult = mysql_query("SELECT * FROM $db WHERE id != '$ID'");
		while($uRow = mysql_fetch_array($userResult)){
			$dbUser = $uRow['username'];
			
			if($dbUser == $uname){
				$userExists = true;	
			}
		}
		
		
		//check if email is already there
		$emailExists = false;
		$emailResult = mysql_query("SELECT * FROM $db WHERE id != $ID");
		while($eRow = mysql_fetch_array($emailResult)){
			$dbEmail = $eRow['email'];
			
			if($dbEmail == $email){
				$emailExists = true;	
			}
		}
		
		
		//validation
		if($userExists == false && $emailExists == false && $pword == $pword2 && $email != "" && $uname != ""){
			
			//encrypt password
			if($pword != ""){
				$encrypt = sha1_salt($pword);
				$salt = $encrypt['salt'];
				$pass = $encrypt['string'];
				
				$sql2 = mysql_query("UPDATE $db SET 
				password = '$pass',
				salt = '$salt'
				WHERE id = '$ID'")or die("database error");
			}
			
			$sql2 = mysql_query("UPDATE $db SET
			username = '$uname',
			email = '$email',
			permissions = '$userpermissions'
			WHERE id = '$ID'")or die("database error");
			
			
			alert ("<p><b>Success!</b> User was successfully saved.</p>", true);
			
			$ID = "";
			
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
	
	
	
	
	if ($ID == "") {
		
		
		echo "<table cellpadding='3' cellspacing='0' border='0' style='width:100%;' class='tablesorter stickyheader'>";
		
		echo "<thead>";
		echo "<tr>";
		echo "<th width='250px'><p><b>Username</b></p></th>";
		echo "<th width='250px'><p><b>Email</b></p></th>";
		
		echo "<th>&nbsp;</th>";
		echo "</tr>";
		echo "</thead>";
		
		echo "<tbody>";
		
		$sql = mysql_query("ALTER TABLE $db ORDER BY id DESC")or die("Could Not Re-order Database 1");
		$query = "SELECT * FROM $db ORDER BY username";
		$result = mysql_query($query);
		$num_results = mysql_num_rows($result);
		
		$count = 0;
		
		for ($i = 0; $i < $num_results; $i++) {
			$row = mysql_fetch_array($result);
			
			$id = $row['id'];
			$uname = $row['username'];
			$email = $row['email'];
			
			
		
			$count++;
			
			
			if ($count % 2) {
				echo "<tr class='row1'>";
			} else {
				echo "<tr class='row2'>";
			}
			
			if($id == 1){
				echo "<td><p><b style='color:#3C6C46;'>" .$uname ."</b></p></td>";
				echo "<td><p><b style='color:#3C6C46;'>" .$email ."</b></p></td>";
			}else{
				echo "<td><p>" .$uname ."</p></td>";
				echo "<td><p>" .$email ."</p></td>";
			}
			
	
			
			echo "<td align='right'>";
			
				echo "<form action='' method='post'>";
				echo "<input type='submit' name='submit' class='submit' value='Edit' />";
				echo "<input type='hidden' name='ID' value='" .$id ."' />";
				echo "<input type='hidden' name='userid' value='" .$userid ."' />";
				
				echo "<input type='hidden' name='section' value='" .$section ."'>";
				echo "<input type='hidden' name='username' value='" .$username ."' />";
				echo "<input type='hidden' name='password' value='" .$password ."' />";
				echo "</form>";
			
			echo "</td>";
			
			
			echo "</tr>";
			
		}
	
		echo "</tbody>";
		echo "</table>";
	
	
	
	} else {
		
	
		// IMPORTANT -->
		important("<p>To change the account password, you must login as the user and go to 'My Login Information'.</p>");
		// -->
		
	
		$query = "SELECT * FROM $db WHERE id='$ID'";
		$result = mysql_query($query);
		$row = mysql_fetch_array($result);
			
		$id = $row['id'];
		$uname = $row['username'];
		$email = $row['email'];
		$pword = "";
		$pword2 = "";
		$userpermissions = $row['permissions'];
			
		echo "<form action='' method='post' name='edit' enctype='multipart/form-data'>";
	
		echo "<table cellpadding='3' cellspacing='0' border='0' style='width:100%;' class='removepadding'>";
		
		echo "<tr>";
		echo "<td style='width:150px;'><p>Username:</p></td>";
		echo "<td colspan='2'><input type='text' name='uname' class='input' value='" .$uname ."' /></td>";
		echo "</tr>";
		
		/*echo "<tr>";
		echo "<td><p>Change Password To:</p></td>";
		echo "<td width='245px'><input type='password' name='pword' id='p' onkeyup='javascript:testPassword();' value='" .$pword ."' onfocus='javascript:changeStyle(this);' class='input' /></td>";
		echo "<td><img src='../cms/images/password0.jpg' alt='' border='0' name='password_strength' id='password_strength' /></td>";
		echo "</tr>";
		
		echo "<tr>";
		echo "<td><p>Re-type Password:</p></td>";
		echo "<td width='245px'><input type='password' name='pword2' id='p2' onkeyup='javascript:doubleCheckPassword();' value='" .$pword2. "' onfocus='javascript:changeStyle(this);' class='input' /></td>";
		echo "<td><img src='../cms/images/check0.jpg' alt='' border='0' name='password_same' id='password_same' /></td>";
		echo "</tr>";*/
		
		echo "<tr>";
		echo "<td style='width:150px;'><p>Email:</p></td>";
		echo "<td colspan='2'><input type='text' name='email' class='input' value='" .$email ."' /></td>";
		echo "</tr>";
		
		if($id != 1){
		echo "<tr>";
		echo "<td style='width:150px; padding-top:15px; border-top:1px solid #ccc;'><p><b>Permissions:</b></p></td>";
		echo "<td style='padding-top:10px; border-top:1px solid #ccc;' colspan='2'>";
		echo "<p>";
		
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
		}
		
		
		echo "<tr>";
		
		echo "<td class='footer' style='text-align:left;'>";
		echo "<select name='confirm' class='select' style='width: 50px;'><option>No</option><option>Yes</option></select> <input type='submit' name='submit' class='submit' value='Delete'";
		if($id == 1){ echo " DISABLED"; }
		echo " />";
		echo "</td>";
		
		echo "<td class='footer' colspan='2'><input type='submit' name='submit' value='Cancel' class='submit' /> <input type='submit' name='submit' value='Save Changes' class='submit' /></td>";
		echo "</tr>";
		
		echo "</table>";
		
		
		echo "<input type='hidden' name='ID' value='" .$ID ."'>";
		echo "<input type='hidden' name='userid' value='" .$userid ."' />";
		echo "<input type='hidden' name='section' value='" .$section ."'>";
		echo "<input type='hidden' name='username' value='" .$username ."' />";
		echo "<input type='hidden' name='password' value='" .$password ."' />";
		echo "</form>";
		
		
	}


}
?>