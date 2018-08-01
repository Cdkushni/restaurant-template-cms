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
			
			$delete = mysql_query("DELETE FROM $db where user_id='$ID'") or die ("could not delete the record");
			$delete = mysql_query("DELETE FROM cms_permissions where user_id='$ID'") or die ("could not delete the record");
			
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
		$sections = $_POST['permissions'];
		
		
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
		if($userExists == false && $emailExists == false && $email != "" && $uname != ""){
			
			$sql2 = mysql_query("UPDATE $db SET
			username = '$uname',
			email = '$email'
			WHERE user_id = '$ID'")or die("database error");
			
			//add permissions
			$delete = mysql_query("DELETE FROM cms_permissions WHERE user_id = '$ID'");
			foreach($sections as $sec){
				$insert = mysql_query("INSERT INTO cms_permissions(user_id, section_id) VALUES('$ID', '$sec')");
			}
			
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
		
		$query = "SELECT * FROM $db ORDER BY username";
		$result = mysql_query($query);
		$num_results = mysql_num_rows($result);
		
		$count = 0;
		
		for ($i = 0; $i < $num_results; $i++) {
			$row = mysql_fetch_array($result);
			
			$id = $row['user_id'];
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
				echo "<input type='hidden' name='xssid' value='" .$_COOKIE['xssid'] ."' />";
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
		
	
		$query = "SELECT * FROM $db WHERE user_id='$ID'";
		$result = mysql_query($query);
		$row = mysql_fetch_array($result);
			
		$id = $row['user_id'];
		$uname = $row['username'];
		$email = $row['email'];
		$pword = "";
		$pword2 = "";
		
		$permissions = array();
		$query = mysql_query("SELECT * FROM cms_permissions WHERE user_id = '$id'");
		if(mysql_num_rows($query)){
			while($row = mysql_fetch_array($query)){
				array_push($permissions, $row['section_id']);	
			}
		}
			
		echo "<form action='' method='post' name='edit' enctype='multipart/form-data'>";
	
		echo "<table cellpadding='3' cellspacing='0' border='0' style='width:100%;' class='removepadding'>";
		
		echo "<tr>";
		echo "<td style='width:150px;'><p>Username:</p></td>";
		echo "<td colspan='2'><input type='text' name='uname' class='input' value='" .$uname ."' /></td>";
		echo "</tr>";
		
		echo "<tr>";
		echo "<td style='width:150px;'><p>Email:</p></td>";
		echo "<td colspan='2'><input type='text' name='email' class='input' value='" .$email ."' /></td>";
		echo "</tr>";
		
		if($id != 1){
		echo "<tr>";
		echo "<td style='width:150px; padding-top:15px; border-top:1px solid #ccc;'><p><b>Permissions:</b></p></td>";
		echo "<td style='padding-top:10px; border-top:1px solid #ccc;' colspan='2'>";
		echo "<p>";
		
			$sectionqry = mysql_query("SELECT * FROM cms_sections");
			while($sec = mysql_fetch_array($sectionqry)){
				echo "<input type='checkbox' name='permissions[]' value='" .$sec['section_id']. "'" .((in_array($sec['section_id'], $permissions)) ? " checked" : ""). " /> " .$sec['section_name']. "<br />";
			}
		
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
		echo "<input type='hidden' name='section' value='" .$section ."'>";
		echo "<input type='hidden' name='xssid' value='" .$_COOKIE['xssid'] ."' />";
		echo "</form>";
		
		
	}


}
?>