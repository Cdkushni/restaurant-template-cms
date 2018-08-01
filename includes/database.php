<?php
if($db_host==''){
	require_once('config.php');
}
mysql_connect($db_host, $db_username, $db_password) or die ("Cannot connect to database");
mysql_select_db($db_schema) or die("Cannot select database");
?>