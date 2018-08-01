<form action='' method='post' style='clear:both; width: 333px;'>
	<label for='name'>Name</label><br /><input type='text' name='name' class='input' value='<?php echo $_POST['name'];?>'/><br />
	<label for='email'>Email</label><br /><input type='text' class='input' name='email' id='email' value='<?php echo $_POST['email'];?>'/><br />
	<label for='message'>Message</label><br />
	<textarea name='message' id='message' class='input textarea'><?php echo $_POST['message'];?></textarea><br />
	<label for='security'>Security</label><br /><img src='/includes/formvalidator/img.php?t=<?php echo time();?>' class='security' style='float: left; clear: left'/><input type='text' name='security' id='security' value='<?php echo $_POST['security'];?>' class='input securitycode' /><br />	
	<input type='submit' class='button submit f_right' />
</form>