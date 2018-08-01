<?php

echo "<table cellpadding='10' cellspacing='0' border='0' id='login_table' align='center' style='margin-top: 20px;'>";
echo "<tr>";
echo "<td class='admin_header' style='padding: 0px;'><img src='../cms/images/top_logo.jpg' alt='' border='0' /></td>";
echo "</tr>";

echo "<tr>";
echo "<td>";

echo "<form action='' method='post'>";
echo "<table cellpadding='2' cellspacing='0' border='0' width='100%' class='removepadding'>";

echo "<tr>";
echo "<td width='100px'><p>Username: </p></td>";
echo "<td><input type='text' name='username' class='input' /></td>";
echo "</tr>";

echo "<tr>";
echo "<td><p>Password: </p></td>";
echo "<td><input type='password' name='password' class='input' /></td>";
echo "</tr>";


echo "<tr>";
echo "<td></td>";
echo "<td align='right'><input type='submit' name='submit' value='Login' class='submit' /></td>";
echo "</tr>";

echo "</table>";
echo "</form>";

echo "<a href='index.php?forgot=true'>Forgot Password?</a>";
@$forgot = $_GET['forgot'];
if($forgot == true){
	include("../cms/forgot_password.php");
}

echo "</td>";
echo "</tr>";
echo "</table>";

?>