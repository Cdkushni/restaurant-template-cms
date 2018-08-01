<?php
include("../includes/database.php");
$message = "";
$getinfo = false;
@$getinfo = $_POST['getinfo'];


if ($getinfo == true) {

	$pwemail = stripslashes($_POST['pwemail']);
	
	$pwquery = "SELECT * FROM login WHERE email='$pwemail'";
	

	$pwresult = mysql_query($pwquery);
	$pwrow = mysql_fetch_array($pwresult);
	
	$User = $pwrow['username'];
	
	 // create 8 digit password
	$valid_nums = array(2, 3, 4, 5, 6, 7, 8, 9);
	$valid_alph = array("a", "b", "c", "d", "e", "f", "g", "h", "j", "k", "m", "n", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");
	
	@$char1 = $valid_alph[rand(0, count($valid_alph))];
	@$char2 = $valid_alph[rand(0, count($valid_alph))];
	@$char3 = $valid_nums[rand(0, count($valid_nums))];
	@$char4 = strtoupper($valid_alph[rand(0, count($valid_alph))]);
	@$char5 = $valid_nums[rand(0, count($valid_nums))];
	@$char6 = $valid_alph[rand(0, count($valid_alph))];
	@$char7 = strtoupper($valid_alph[rand(0, count($valid_alph))]);
	@$char8 = $valid_nums[rand(0, count($valid_nums))];
	$pWord = $char1 .$char2 .$char3 .$char4 .$char5 .$char6 .$char7 .$char8;
	
	//encrypt password
	$encrypt = sha1_salt($pWord);
	$salt = $encrypt['salt'];
	$pass = $encrypt['string'];
	
		
	if(trim($pwemail) != "" && isset($User)){
		
		//update db with reset password
        $update = mysql_query("UPDATE login SET password = '$pass', salt = '$salt' WHERE email='$pwemail'");
		
		//send email
		$mailHeaders = "From: Pixel Army <info@pixelarmy.ca> \n";
		$mailHeaders .= "Reply-To: Pixel Army <info@pixelarmy.ca> \n";
		$mailHeaders .= "Return-Path: Pixel Army <info@pixelarmy.ca> \n";
		$mailHeaders .= "X-Mailer: PHP v" .phpversion() ." \n";
		$mailHeaders .= "Mime-Version: 1.0 \n";
		$mailHeaders .= "Content-Transfer-Encoding: 8bit \n";
		$mailHeaders .= "X-Priority: 3 \n";
		$mailHeaders .= "Importance: Normal \n";
		
		
		$message = "CONTENT MANAGEMENT SYSTEM INQUIRY\n------------------------------------------------------------------------------------\n\nYour username and password are as follows:\n\nUsername: " .$User. "\nPassword: " .$pWord. "\n\nNote: You can change your password any time you want when you login to your content management system.\n\nPlease notify us if you have any questions or concerns.\n\n-Pixel Army Administration\n\n";
		
		
		mail ($pwemail, "CMS Inquiry", $message, $mailHeaders);
		
		
		$message = "<p style='font-size:10px; color:#3C6C46;'>Success! Your login information has been sent to you.</p><br />";
		
		
	}else{
		
		$message = "<p style='font-size:10px; color:#c70000;'><b>Error!</b> There is no account listed under that email address.</p><br />";
		
	}
	
}

	
echo "<form action='' method='post' name='forgot' id='forgot' enctype='multipart/form-data'>";

echo "<table cellpadding='0' cellspacing='0' border='0' style='width:100%;' class='removepadding'>";

echo "<tr>";
echo "<td colspan='2'><br /><p style='font-size:10px; color:#666;'>Enter your email address below to have your password reset and your<br />account information emailed to you.</p><br />" .$message. "</td>";
echo "</tr>";

echo "<tr>";
echo "<td width='100px'><p>Email Address:</p></td>";
echo "<td align='right'><input type='text' name='pwemail' id='pwemail' value='' class='input' /></td>";
echo "</tr>";

echo "<tr>";
echo "<td align='right' colspan='2'><br /><input class='submit' type='submit' value='Submit' name='btn' /></td>";
echo "</tr>";

echo "</table>";

echo "<input type='hidden' name='getinfo' value='true' />";
echo "</form>";

?>