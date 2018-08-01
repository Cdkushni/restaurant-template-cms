<?php

//Main administrator must have a user_id of 1 to automatically receive all permissions
$permissions = array();
$permissionsqry = mysql_query("SELECT * FROM cms_permissions WHERE user_id = '$cms_userid'");
if(mysql_num_rows($permissionsqry)){
	while($perm = mysql_fetch_array($permissionsqry)){
		array_push($permissions, $perm['section_id']);								
	}
}

// Navigation
echo "<div class='applemenu'>";

// Home
echo "<div class='admin_nav'>";
	echo "<table cellpadding='0' cellspacing='0' border='0'><tr>";
	echo "<td class='admin_nav1'><img src='../cms/images/icon_home.jpg' alt='' border='0' /></td>";
	echo "<td class='admin_nav2'><p>Home</p></td>";
	echo "</tr></table>";
echo "</div>";
echo "<div class='submenu'><table cellpadding='0' cellspacing='0' border='0'>";
	echo "<form action='' method='POST'>";
	
	echo "<tr><td><input type='submit' name='section' value='CMS Overview' class='admin_btn' /></td></tr>";
	echo "<tr><td><input type='submit' name='section' value='Website Statistics' class='admin_btn' /></td></tr>";
	echo "<tr><td><input type='submit' name='section' value='Global Website Settings' class='admin_btn' /></td></tr>";
	
	echo "<input type='hidden' name='xssid' value='" .$_COOKIE['xssid'] ."' />";
	echo "</form>";
echo "</table></div>";


// Change Login
echo "<div class='admin_nav'>";
	echo "<table cellpadding='0' cellspacing='0' border='0'><tr>";
	echo "<td class='admin_nav1'><img src='../cms/images/icon_lock.jpg' alt='' border='0' /></td>";
	echo "<td class='admin_nav2'><p>My Login Information</p></td>";
	echo "</tr></table>";
echo "</div>";
echo "<div class='submenu'><table cellpadding='0' cellspacing='0' border='0'>";
	echo "<form action='' method='POST'>";
	
	echo "<tr><td><input type='submit' name='section' value='Edit Login Information' class='admin_btn' /></td></tr>";
	
	echo "<input type='hidden' name='xssid' value='" .$_COOKIE['xssid'] ."' />";
	echo "</form>";
echo "</table></div>";


// Manage Users
if($cms_userid == 1 || in_array("1", $permissions)){
echo "<div class='admin_nav'>";
	echo "<table cellpadding='0' cellspacing='0' border='0'><tr>";
	echo "<td class='admin_nav1'><img src='../cms/images/icon_people.jpg' alt='' border='0' /></td>";
	echo "<td class='admin_nav2'><p>Manage Users</p></td>";
	echo "</tr></table>";
echo "</div>";
echo "<div class='submenu'><table cellpadding='0' cellspacing='0' border='0'>";

	echo "<form action='' method='POST'>";
	echo "<tr><td><input type='submit' name='section' value='Add User' class='admin_btn' /></td></tr>";
	echo "<tr><td><input type='submit' name='section' value='Edit/Delete User' class='admin_btn' /></td></tr>";
	
	echo "<input type='hidden' name='xssid' value='" .$_COOKIE['xssid'] ."' />";
	echo "</form>";
echo "</table></div>";
}


// Manage Page Content
if($cms_userid == 1 || in_array("2", $permissions)){
echo "<div class='admin_nav'>";
	echo "<table cellpadding='0' cellspacing='0' border='0'><tr>";
	echo "<td class='admin_nav1'><img src='../cms/images/icon_managetext.jpg' alt='' border='0' /></td>";
	echo "<td class='admin_nav2'><p>Manage Page Content</p></td>";
	echo "</tr></table>";
echo "</div>";
echo "<div class='submenu'><table cellpadding='0' cellspacing='0' border='0'>";

	echo "<form action='' method='POST'>";
	echo "<tr><td><input type='submit' name='section' value='Edit Page' class='admin_btn' /></td></tr>";
	
	echo "<input type='hidden' name='xssid' value='" .$_COOKIE['xssid'] ."' />";
	echo "</form>";
echo "</table></div>";
}


// Add New Page
if($cms_userid == 1 || in_array("3", $permissions)){
echo "<div class='admin_nav'>";
	echo "<table cellpadding='0' cellspacing='0' border='0'><tr>";
	echo "<td class='admin_nav1'><img src='../cms/images/icon_addpage.jpg' alt='' border='0' /></td>";
	echo "<td class='admin_nav2'><p>Add New Page</p></td>";
	echo "</tr></table>";
echo "</div>";
echo "<div class='submenu'><table cellpadding='0' cellspacing='0' border='0'>";
	echo "<form action='' method='POST'>";
	
	echo "<tr><td><input type='submit' name='section' value='Add Main Page' class='admin_btn' /></td></tr>";
	echo "<tr><td><input type='submit' name='section' value='Add Sub Page' class='admin_btn' /></td></tr>";
	
	echo "<input type='hidden' name='xssid' value='" .$_COOKIE['xssid'] ."' />";
	echo "</form>";
echo "</table></div>";
}


// Support
echo "<div class='admin_nav'>";
	echo "<table cellpadding='0' cellspacing='0' border='0'><tr>";
	echo "<td class='admin_nav1'><img src='../cms/images/icon_support.jpg' alt='' border='0' /></td>";
	echo "<td class='admin_nav2'><p>Support</p></td>";
	echo "</tr></table>";
echo "</div>";
echo "<div class='submenu'><table cellpadding='0' cellspacing='0' border='0'>";
	echo "<form action='' method='POST'>";
	
	echo "<tr><td><input type='submit' name='section' value='Contact Support' class='admin_btn' /></td></tr>";
	
	echo "<input type='hidden' name='xssid' value='" .$_COOKIE['xssid'] ."' />";
	echo "</form>";
echo "</table></div>";

// Logout
echo "<div class='admin_nav'>";
	echo "<table cellpadding='0' cellspacing='0' border='0'><tr>";
	echo "<td class='admin_nav1'><img src='../cms/images/icon_lock.jpg' alt='' border='0' /></td>";
	echo "<td class='admin_nav2'><p>Logout</p></td>";
	echo "</tr></table>";
echo "</div>";
echo "<div class='submenu'><table cellpadding='0' cellspacing='0' border='0'>";
	echo "<form action='' method='POST'>";
	
	echo "<tr><td><input type='submit' name='section' value='Confirm Logout' class='admin_btn' /></td></tr>";
	
	echo "<input type='hidden' name='username' value='' />";
	echo "<input type='hidden' name='password' value='' />";
	echo "</form>";
echo "</table></div>";

echo "</div>";



?>
