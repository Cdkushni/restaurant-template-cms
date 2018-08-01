<?php

// MAIN ADMINISTRATOR MUST HAVE ID = 1 AND PERMISSIONS = All

//GET USER PERMISSIONS
$permqry = "SELECT * FROM login WHERE username = '$username'";
$permresult= mysql_query($permqry);
$perm = mysql_fetch_array($permresult);
$permissions = $perm['permissions'];

// NAVIGATION
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
	
	echo "<input type='hidden' name='username' value='" .$username ."' />";
	echo "<input type='hidden' name='password' value='" .$password ."' />";
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
	
	echo "<input type='hidden' name='username' value='" .$username ."' />";
	echo "<input type='hidden' name='password' value='" .$password ."' />";
	echo "</form>";
echo "</table></div>";


// Manage Users
if(strstr($permissions, "Manage Users") != false || $permissions == "All"){
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
	
	echo "<input type='hidden' name='username' value='" .$username ."' />";
	echo "<input type='hidden' name='password' value='" .$password ."' />";
	echo "</form>";
echo "</table></div>";
}


// Manage Page Content
if(strstr($permissions, "Manage Page Content") != false || $permissions == "All"){
echo "<div class='admin_nav'>";
	echo "<table cellpadding='0' cellspacing='0' border='0'><tr>";
	echo "<td class='admin_nav1'><img src='../cms/images/icon_managetext.jpg' alt='' border='0' /></td>";
	echo "<td class='admin_nav2'><p>Manage Page Content</p></td>";
	echo "</tr></table>";
echo "</div>";
echo "<div class='submenu'><table cellpadding='0' cellspacing='0' border='0'>";

	echo "<form action='' method='POST'>";
	echo "<tr><td><input type='submit' name='section' value='Edit Page' class='admin_btn' /></td></tr>";
	
	echo "<input type='hidden' name='username' value='" .$username ."' />";
	echo "<input type='hidden' name='password' value='" .$password ."' />";
	echo "</form>";
echo "</table></div>";
}


// Add New Page
if(strstr($permissions, "Add Pages") != false || $permissions == "All"){
	
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
		
		echo "<input type='hidden' name='username' value='" .$username ."' />";
		echo "<input type='hidden' name='password' value='" .$password ."' />";
		echo "</form>";
	echo "</table></div>";

}



// Manage Garage Doors
if(strstr($permissions, "Manage Garage Doors") != false || $permissions == "All"){
echo "<div class='admin_nav'>";
	echo "<table cellpadding='0' cellspacing='0' border='0'><tr>";
	echo "<td class='admin_nav1'><img src='../cms/images/icon_garagedoor.jpg' alt='' border='0' /></td>";
	echo "<td class='admin_nav2'><p>Manage Garage Doors</p></td>";
	echo "</tr></table>";
echo "</div>";
echo "<div class='submenu'><table cellpadding='0' cellspacing='0' border='0'>";
	echo "<form action='' method='POST'>";
	echo "<tr><td><input type='submit' name='section' value='Add Garage Door' class='admin_btn' /></td></tr>";
	echo "<tr><td><input type='submit' name='section' value='Edit/Delete Garage Doors' class='admin_btn' /></td></tr>";
	echo "<input type='hidden' name='username' value='" .$username ."' />";
	echo "<input type='hidden' name='password' value='" .$password ."' />";
	echo "</form>";
echo "</table></div>";
}

// Manage Specials
if(strstr($permissions, "Manage Specials") != false || $permissions == "All"){
echo "<div class='admin_nav'>";
	echo "<table cellpadding='0' cellspacing='0' border='0'><tr>";
	echo "<td class='admin_nav1'><img src='../cms/images/icon_prize.jpg' alt='' border='0' /></td>";
	echo "<td class='admin_nav2'><p>Manage Specials</p></td>";
	echo "</tr></table>";
echo "</div>";
echo "<div class='submenu'><table cellpadding='0' cellspacing='0' border='0'>";
	echo "<form action='' method='POST'>";
	echo "<tr><td><input type='submit' name='section' value='Add Special' class='admin_btn' /></td></tr>";
	echo "<tr><td><input type='submit' name='section' value='Edit/Delete Specials' class='admin_btn' /></td></tr>";
	echo "<input type='hidden' name='username' value='" .$username ."' />";
	echo "<input type='hidden' name='password' value='" .$password ."' />";
	echo "</form>";
echo "</table></div>";

}

// Manage FAQs
if(strstr($permissions, "Manage FAQs") != false || $permissions == "All"){
echo "<div class='admin_nav'>";
	echo "<table cellpadding='0' cellspacing='0' border='0'><tr>";
	echo "<td class='admin_nav1'><img src='../cms/images/icon_faq.jpg' alt='' border='0' /></td>";
	echo "<td class='admin_nav2'><p>Manage FAQs</p></td>";
	echo "</tr></table>";
echo "</div>";
echo "<div class='submenu'><table cellpadding='0' cellspacing='0' border='0'>";
	echo "<form action='' method='POST'>";

	echo "<tr><td><input type='submit' name='section' value='Add FAQ' class='admin_btn' /></td></tr>";
	echo "<tr><td><input type='submit' name='section' value='Edit/Delete FAQs' class='admin_btn' /></td></tr>";
	echo "<input type='hidden' name='username' value='" .$username ."' />";
	echo "<input type='hidden' name='password' value='" .$password ."' />";
	echo "</form>";
echo "</table></div>";
}

// Manage Photos
if(strstr($permissions, "Manage Showroom Photos") != false || $permissions == "All"){
echo "<div class='admin_nav'>";
	echo "<table cellpadding='0' cellspacing='0' border='0'><tr>";
	echo "<td class='admin_nav1'><img src='../cms/images/icon_photogalleries.jpg' alt='' border='0' /></td>";
	echo "<td class='admin_nav2'><p>Manage Showroom</p></td>";
	echo "</tr></table>";
echo "</div>";
echo "<div class='submenu'><table cellpadding='0' cellspacing='0' border='0'>";
	echo "<form action='' method='POST'>";

	echo "<tr><td><input type='submit' name='section' value='Add Photo' class='admin_btn' /></td></tr>";
	echo "<tr><td><input type='submit' name='section' value='Edit/Delete Photos' class='admin_btn' /></td></tr>";
	echo "<input type='hidden' name='username' value='" .$username ."' />";
	echo "<input type='hidden' name='password' value='" .$password ."' />";
	echo "</form>";
echo "</table></div>";
}if(strstr($permissions, "Manage Testimonials") != false || $permissions == "All"){
echo "<div class='admin_nav'>";
	echo "<table cellpadding='0' cellspacing='0' border='0'><tr>";
	echo "<td class='admin_nav1'><img src='../cms/images/icon_blog.jpg' alt='' border='0' /></td>";
	echo "<td class='admin_nav2'><p>Manage Testimonials</p></td>";
	echo "</tr></table>";
echo "</div>";
echo "<div class='submenu'><table cellpadding='0' cellspacing='0' border='0'>";
	echo "<form action='' method='POST'>";
	echo "<tr><td><input type='submit' name='section' value='Add Testimonial' class='admin_btn' /></td></tr>";
	echo "<tr><td><input type='submit' name='section' value='Edit/Delete Testimonials' class='admin_btn' /></td></tr>";
	echo "<input type='hidden' name='username' value='" .$username ."' />";
	echo "<input type='hidden' name='password' value='" .$password ."' />";
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
	
	echo "<input type='hidden' name='username' value='" .$username ."' />";
	echo "<input type='hidden' name='password' value='" .$password ."' />";
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
