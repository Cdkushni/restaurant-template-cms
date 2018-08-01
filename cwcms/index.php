<?php
error_reporting(0);
session_start();

include "../includes/database.php";
include "../cms/functions.php";
mysql_escape();
include "../cms/config.php";
include("../fckeditor/fckeditor.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>C&amp;W Garage Doors | Content Management System</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="robots" content="noindex, nofollow" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />

<link rel="stylesheet" href="../cms/css/admin_stylesheet.css" type="text/css" />

<!--jquery-->
<script type="text/javascript" src="../cms/js/jquery-1.5.2.min.js"></script>

<!--jquery tools-->
<script type="text/javascript" src="../cms/js/jquery.tools.min.js"></script>

<!--ddaccordion-->
<script type="text/javascript" src="../cms/js/ddaccordion.js"></script>

<!--treeview-->
<link rel="stylesheet" href="../cms/css/jquery.treeview.css" />
<script type="text/javascript" src="../cms/js/jquery.treeview.js"></script>

<!--pretty photo-->
<link rel="stylesheet" href="../cms/css/prettyPhoto.css" type="text/css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
<script type="text/javascript" src="../cms/js/jquery.prettyPhoto.js" charset="utf-8"></script>

<!--highcharts-->
<link rel="stylesheet" href="../cms/css/custom-theme/jquery-ui-1.8.13.custom.css" type="text/css" />
<script type="text/javascript" src="../cms/js/jquery-ui-1.8.13.custom.min.js"></script>
<script type="text/javascript" src="../cms/js/highcharts.js"></script>

<!--jcrop-->
<script type="text/javascript" src="../cms/js/jquery.Jcrop.js"></script>
<link rel="stylesheet" href="../cms/css/jquery.Jcrop.css" type="text/css" />

<!--tablesorter/stickeyheader-->
<script type="text/javascript" src="../cms/js/jquery.tablesorter.min.js"></script>
<script type="text/javascript" src="../cms/js/jquery.stickytableheader.js"></script>
<link rel="stylesheet" href="../cms/css/jquery.tablesorter.css" type="text/css" />

<!--scripts-->
<script type="text/javascript" src="../cms/js/admin_script.js"></script>
<script type="text/javascript" src="../cms/js/animatedcollapse.js"></script>

</head>

<body>


<?php

$submit = $_POST['submit'];
$section = $_POST['section'];
$search = $_POST['search'];

//xss prevention
if($_POST['xssid'] != $_COOKIE['xssid']){
	logout();
}

//log out
if($section == "Confirm Logout"){
	logout();
}

$logged_in = $_SESSION['cms_loggedin'];

//login
if($logged_in == false){
	$username = $_POST['username'];
	$password = $_POST['password'];
	if (trim($username) != "" && trim($password) != "") {
		$logged_in = login();
	}
}
$cms_userid = $_SESSION['cms_userid'];


// WE ARE NOT LOGGED IN
if ($logged_in == false) {
	include "../cms/login_table.php";
	
	
// WE ARE LOGGED IN
} else if ($logged_in == true) {
	
	echo "<table width='100%' cellpadding='0' cellspacing='0' border='0' id='login_table'>";
	echo "<tr>";
	echo "<td colspan='3' class='admin_header' style='width: 100px;'><img src='../cms/images/top_logo.jpg' alt='' border='0' /></td>";
	echo "</tr>";
	
	echo "<tr>";
	echo "<td id='admin_left'>";
	
	include "../cms/navigation.php";
	
	echo "</td>";
	echo "<td id='admin_center'><img src='../cms/images/admin_center.jpg' alt='' border='0' /></td>";
	echo "<td id='admin_right'>";
	
	// change password alert
	if(trim($username) == "admin" && trim($password) == "password1"){
		echo "<p class='notice'><b>Important:</b> Your username and password are not secure. Please change your login information immediately.</p>";	
	}
	
	if ($section == "CMS Overview") {$section = "";}
	
	// WELCOME SCREEN
	if ($section == "") {
		
		echo "<h1>Welcome to the Administrator Section.</h1>";
		echo "<p>Within this section you will have the ability to complete a variety of tasks. To start editing, simply select the feature that you wish to manage from the navigation list on the left.</p>";
		
		
		echo "<table cellpadding='5' cellspacing='0' border='0' class='overview_tbl'>";
		echo "<tr>";
		echo "<td class='overview_top' style='width:400px;'><p><b>Website &amp; CMS Overview</b></p></td>";
		echo "<td class='overview_top'>&nbsp;</td>";
		
		
		echo "<tr class='row1'>";
		include "../cms/maintext.php";
		echo "</tr>";
		
		echo "<tr class='row2'>";
		include "../cms/edit_user.php";
		echo "</tr>";
		
		echo "</table>";
		
		

	// EDITING CONTENT
	} else {
	
		echo "<h1>" .$section ."</h1>";
		if (isset($_SESSION['jcrop_success'])){
			alert($_SESSION['jcrop_success'],true);
			unset($_SESSION['jcrop_success']);
		}
		include "../cms/statistics.php";
		include "../cms/manage_login.php";
		include "../cms/manage_global_settings.php";
		
		include "../cms/add_user.php";
		include "../cms/edit_user.php";
		
		include "../cms/maintext.php";
		include "../cms/maintextnav.php";
		
		include "../cms/add_page.php";
		include "../cms/add_page2.php";
		//include "../cms/add_page3.php";
		//include "../cms/add_page4.php";
		//include "../cms/add_page5.php";
		include "../cms/aed_testimonials.php";
		include "../cms/aed_garage_doors.php";
		include "../cms/aed_specials.php";
		include "../cms/aed_communities.php";
		include "../cms/aed_photos.php";
		include "../cms/aed_community_photos.php";
		include "../cms/aed_faqs.php";
		include "../cms/aed_careers.php";
		include "../cms/support.php";
		include "../cms/manage_jcrop.php";

	}
	
	echo "</td>";
	echo "</tr>";
	echo "</table>";
}

?>

<!--pretty photo/tooltip-->
<script type="text/javascript" charset="utf-8">
$(document).ready(function(){
	$("a[rel^='prettyPhoto']").prettyPhoto({animation_speed:'normal', slideshow:6000, autoplay_slideshow: false, overlay_gallery: false, deeplinking: false, social_tools: false});
	$("sup[title]").tooltip();
});
</script>

</body>
</html>
