<?php 

if ($section == "Global Website Settings"){
	
	if ($submit == "Save Settings"){
		
		$company_name = str_replace("'", "&rsquo;", stripslashes($_POST['company_name']));	
		$contact_address = str_replace("'", "&rsquo;", stripslashes($_POST['contact_address']));
		$contact_address2 = str_replace("'", "&rsquo;", stripslashes($_POST['contact_address2']));			
		$contact_city = str_replace("'", "&rsquo;", stripslashes($_POST['contact_city']));			
		$contact_province = str_replace("'", "&rsquo;", stripslashes($_POST['contact_province']));			
		$contact_postal_code = str_replace("'", "&rsquo;", stripslashes($_POST['contact_postal_code']));			
		$contact_country = str_replace("'", "&rsquo;", stripslashes($_POST['contact_country']));			
		$contact_phone = str_replace("'", "&rsquo;", stripslashes($_POST['contact_phone']));			
		$contact_fax = str_replace("'", "&rsquo;", stripslashes($_POST['contact_fax']));		
		$contact_toll_free = str_replace("'", "&rsquo;", stripslashes($_POST['contact_toll_free']));			
		$contact_email = str_replace("'", "&rsquo;", stripslashes($_POST['contact_email']));			
		$facebook = str_replace("'", "&rsquo;", stripslashes($_POST['facebook']));
		if(trim($facebook) != ""){
			$facebook = str_replace("http://", "", $facebook);
			$facebook = str_replace("https://", "", $facebook);
			$facebook = "http://" .$facebook;
		}
		$facebook_appid = str_replace("'", "&rsquo;", stripslashes($_POST['facebook_appid']));			
		$facebook_secret = str_replace("'", "&rsquo;", stripslashes($_POST['facebook_secret']));
		$twitter = str_replace("'", "&rsquo;", stripslashes($_POST['twitter']));
		if(trim($twitter) != ""){
			$twitter = str_replace("http://", "", $twitter);
			$twitter = str_replace("https://", "", $twitter);
			$twitter = "http://" .$twitter;
		}
		$pinterest = str_replace("'", "&rsquo;", stripslashes($_POST['pinterest']));
		if(trim($pinterest) != ""){
			$pinterest = str_replace("http://", "", $pinterest);
			$pinterest = str_replace("https://", "", $pinterest);
			$pinterest = "http://" .$pinterest;
		}
		$googleplus = str_replace("'", "&rsquo;", stripslashes($_POST['googleplus']));
		if(trim($googleplus) != ""){
			$googleplus = str_replace("http://", "", $googleplus);
			$googleplus = str_replace("https://", "", $googleplus);
			$googleplus = "http://" .$googleplus;
		}
		$linkedin = str_replace("'", "&rsquo;", stripslashes($_POST['linkedin']));
		if(trim($linkedin) != ""){
			$linkedin = str_replace("http://", "", $linkedin);
			$linkedin = str_replace("https://", "", $linkedin);
			$linkedin = "http://" .$linkedin;
		}
		$youtube = str_replace("'", "&rsquo;", stripslashes($_POST['youtube']));
		if(trim($youtube) != ""){
			$youtube = str_replace("http://", "", $youtube);
			$youtube = str_replace("https://", "", $youtube);
			$youtube = "http://" .$youtube;
		}
		$sioppa = str_replace("'", "&rsquo;", stripslashes($_POST['sioppa']));
		if(trim($sioppa) != ""){
			$sioppa = str_replace("http://", "", $sioppa);
			$sioppa = str_replace("https://", "", $sioppa);
			$sioppa = "http://" .$sioppa;
		}
		$meta_title = str_replace("'", "&rsquo;", stripslashes($_POST['meta_title']));			
		$meta_description = str_replace("'", "&rsquo;", stripslashes($_POST['meta_description']));			
		$meta_keywords = str_replace("'", "&rsquo;", stripslashes($_POST['meta_keywords']));		
		$googleanalytics = str_replace("'", "&rsquo;", stripslashes($_POST['googleanalytics']));			
		$ga_email = str_replace("'", "&rsquo;", stripslashes($_POST['ga_email']));			
		$ga_profile = str_replace("'", "&rsquo;", stripslashes($_POST['ga_profile']));			
		$ga_password = str_replace("'", "&rsquo;", stripslashes($_POST['ga_password']));			
		$ga_tracking = str_replace("'", "&rsquo;", stripslashes($_POST['ga_tracking']));			
		$order_email = str_replace("'", "&rsquo;", stripslashes($_POST['order_email']));			
		$consultants_email = str_replace("'", "&rsquo;", stripslashes($_POST['consultants_email']));			
		$contactus_email = str_replace("'", "&rsquo;", stripslashes($_POST['contactus_email']));		
		$advertising_email = str_replace("'", "&rsquo;", stripslashes($_POST['advertising_email']));			
		$publish_email = str_replace("'", "&rsquo;", stripslashes($_POST['publish_email']));
		
		
		//update
		$query = mysql_query("UPDATE global_settings SET 
		company_name = '$company_name', 
		contact_address = '$contact_address', 
		contact_address2 = '$contact_address2', 
		contact_city = '$contact_city', 
		contact_province = '$contact_province', 
		contact_postal_code = '$contact_postal_code', 
		contact_country = '$contact_country', 
		contact_phone = '$contact_phone', 
		contact_fax = '$contact_fax', 
		contact_toll_free = '$contact_toll_free', 
		contact_email = '$contact_email', 
		facebook = '$facebook', 
		twitter = '$twitter',
		pinterest = '$pinterest',
		linkedin = '$linkedin',
		googleplus = '$googleplus',
		youtube = '$youtube',
		sioppa = '$sioppa',
		meta_title = '$meta_title', 
		meta_description = '$meta_description', 
		meta_keywords = '$meta_keywords', 
		ga_email = '$ga_email', 
		ga_password = '$ga_password', 
		ga_profile = '$ga_profile', 
		ga_tracking='$ga_tracking' 
		WHERE id='1'");
		
		if (!mysql_error()){
			alert("<p>Global website settings updated!</p>",true);
			
		}else{
			alert("<p>There was an error saving your settings.".mysql_error()."</p>",false);
			
		}
	}
	
	// IMPORTANT -->
	important("<p>Valid Google Analytics email and password are required for analytics.</b></p>");
	// -->
	
	$result = mysql_query("SELECT * FROM global_settings WHERE id = '1'");
	$row = mysql_fetch_array($result);
	echo "<form action='' method='post' enctype='multipart/form-data'>";
	echo "<div class='tabs tab-ui'>";
		echo "<ul>";
			echo "<li><a href='#contact'>Contact Information</a></li>";
			echo "<li><a href='#social'>Social Networking</a></li>";
			echo "<li><a href='#seodata'>Global SEO Data</a></li>";
			echo "<li><a href='#analytics'>Google Analytics</a></li>";
		echo "</ul>";
		echo "<div id='contact'>";
			echo "<table cellpadding='3' cellspacing='0' border='0' width='100%' class='removepadding'>";
				echo "<tr>";
					echo "<td width='175px'><p>Company Name:</p></td>";
					echo "<td><input type='text' name='company_name' class='input' value='" .$row['company_name']. "' /></td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td><p>Email:</p></td>";
					echo "<td><p><input type='text' name='contact_email' class='input' value='" .$row['contact_email']. "' /> <sup class='help' title='<span>Email</span><p>This is the address that all orders and contact form submissions will send to. This does NOT support muliple email addresses.</p>'>?</sup></p></td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td width='175px'><p>Street Address:</p></td>";
					echo "<td><input type='text' class='input' name='contact_address' value='" .$row['contact_address']. "' /></td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td><p>Suite/Apt. Number:</p></td>";
					echo "<td><input type='text' name='contact_address2' class='input' value='" .$row['contact_address2']. "' /></td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td><p>City:</p></td>";
					echo "<td><input type='text' name='contact_city' class='input' value='" .$row['contact_city']. "' /></td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td><p>State/Province:</p></td>";
					echo "<td><input type='text' name='contact_province' class='input' value='" .$row['contact_province']. "' /></td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td><p>Postal/Zip Code:</p></td>";
					echo "<td><input type='text' name='contact_postal_code' class='input' value='" .$row['contact_postal_code']. "' /></td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td><p>Country:</p></td>";
					echo "<td><input type='text' name='contact_country' class='input' value='" .$row['contact_country']. "' /></td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td><p>Phone:</p></td>";
					echo "<td><input type='text' name='contact_phone' class='input' value='" .$row['contact_phone']. "' /></td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td><p>Fax:</p></td>";
					echo "<td><input type='text' name='contact_fax' class='input' value='" .$row['contact_fax']. "' /></td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td><p>Toll Free:</p></td>";
					echo "<td><input type='text' name='contact_toll_free' class='input' value='" .$row['contact_toll_free']. "' /></td>";
				echo "</tr>";

			echo "</table>";	

		echo "</div>";
		echo "<div id='social'>";
			echo "<table cellpadding='3' cellspacing='0' border='0' width='100%' class='removepadding'>";
				echo "<tr>";
					echo "<td width='175px;'><p>Facebook:</p></td>";
					echo "<td><p><input type='text' name='facebook' class='input' value='" .$row['facebook']. "' /> <sup class='help' title='<span>Facebook</span><p>Enter the full url to your facebook page.</p>'>?</sup></td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td><p>Twitter:</p></td>";
					echo "<td><p><input type='text' name='twitter' class='input' value='" .$row['twitter']. "' /> <sup class='help' title='<span>Twitter</span><p>Enter the full url to your twitter page.</p>'>?</sup></td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td><p>Pinterest:</p></td>";
					echo "<td><p><input type='text' name='pinterest' class='input' value='" .$row['pinterest']. "' /> <sup class='help' title='<span>Pinterest</span><p>Enter the full url to your pinterest page.</p>'>?</sup></td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td><p>Google+:</p></td>";
					echo "<td><p><input type='text' name='googleplus' class='input' value='" .$row['googleplus']. "' /> <sup class='help' title='<span>Google+</span><p>Enter the full url to your google+ page.</p>'>?</sup></td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td><p>LinkedIn:</p></td>";
					echo "<td><p><input type='text' name='linkedin' class='input' value='" .$row['linkedin']. "' /> <sup class='help' title='<span>LinkedIn</span><p>Enter the full url to your linkedin page.</p>'>?</sup></td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td><p>Youtube:</p></td>";
					echo "<td><p><input type='text' name='youtube' class='input' value='" .$row['youtube']. "' /> <sup class='help' title='<span>Youtube</span><p>Enter the full url to your youtube page.</p>'>?</sup></td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td><p>Sioppa:</p></td>";
					echo "<td><p><input type='text' name='sioppa' class='input' value='" .$row['sioppa']. "' /> <sup class='help' title='<span>Sioppa</span><p>Enter the full url to your sioppa store.</p>'>?</sup></td>";
				echo "</tr>";
			echo "</table>";

		echo "</div>";
		echo "<div id='seodata'>";
			echo "<table cellpadding='3' cellspacing='0' border='0' width='100%' class='removepadding'>";
				 echo "<tr>";
					echo "<td width='175px;'><p>Default SEO Title:</p></td>";
					echo "<td><p><input type='text' name='meta_title' class='input' value='" .$row['meta_title']. "' /> <sup class='help' title='<span>SEO Title</span><p>The title of the website. Usually just the name of the company, however can also include a keyword-rich slogan. This will aid in search engine optimization.<br /><br /><b>Example</b><br />Pixel Army - Edmonton Web Design</p>'>?</sup></p></td>";
				 echo "</tr>";
				 echo "<tr>";
					echo "<td style='width:150px;'><p>Default SEO Description:</p></td>";
					echo "<td><p><textarea name='meta_description' class='textarea'>" .$row['meta_description'] ."</textarea>";
					echo " <sup title='<span>SEO Description</span><p>A keyword-rich description of the page used for search engine optimization.</p>'>?</sup></p></td>";
				 echo "</tr>";
				echo "<tr>";
					echo "<td style='width:150px;'><p>Default SEO Keywords:<br /><span style='font-size:11px; color:#999;'>(separate with a comma)</span></p></td>";
					echo "<td><p><textarea name='meta_keywords' class='textarea'>" .$row['meta_keywords'] ."</textarea>";
					echo " <sup title='<span>SEO Keywords</span><p>Important keywords relevant to the page which will aid in search engine optimization.</p>'>?</sup></p></td>";
				echo "</tr>";
				
			echo "</table>";
		echo "</div>";
		echo "<div id='analytics'>";
			echo "<div id='ga_error'></div>";
			echo "<table cellpadding='3' cellspacing='0' border='0' width='100%' class='removepadding'>";
				echo "<tr>";
					echo "<td width='175px;'><p>Google Email:</p></td>";
					echo "<td><p><input type='text' id='ga_email' name='ga_email' value='".$row['ga_email']."' class='input'/> <sup class='help' title='<span>Google Email</span><p>The username of your google analytics account.<br /><br /><b>Example:</b><br />PixelArmy</p>'>?</sup></p></td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td width='175px;'><p>Google Password:</p></td>";
					echo "<td><p><input type='text' id='ga_password' name='ga_password' value='".$row['ga_password']."' class='input'/> <sup class='help' title='<span>Google Password</span><p>The password of your google analytics account.<br /><br /><b>Example:</b><br />*********</p>'>?</sup></p><br /> <small><a onclick='getProfile();'><small>Retrieve Profiles</small></a></small></td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td width='175px;'><p>Profile ID:</p></td>";
					echo "<td id='ga_profile_wrapper'><p><input type='text' id='ga_profile' name='ga_profile' value='".$row['ga_profile']."' class='input' /> <sup class='help' title='<span>Profile ID</span><p>Your profile ID of the website that you have created inside your google analytics account.<br /><br /><b>Example:</b> <br />123456789</p>'>?</sup></p></td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td width='175px;'><p>Tracking ID:</p></td>";
					echo "<td id='ga_tracking_wrapper'><p><input type='text' name='ga_tracking' id='ga_tracking' value='".$row['ga_tracking']."' class='input'/> <sup class='help' title='<span>Tracking ID</span><p>The tracking ID of the website that you have created inside your google analytics account.<br /><br /><b>Example:</b><br />UA-12345678-1</p>'>?</sup></p></td>";
				echo "</tr>";
			echo "</table>";
		echo "</div>";
	echo "</div>";
	echo "<table cellpadding='3' cellspacing='0' border='0' width='100%' class='removepadding'>";
		echo "<td class='footer' align='right'><input type='submit' name='submit' value='Save Settings' class='submit' /></td>";
	echo "</table>";


	echo "<input type='hidden' name='xssid' value='" .$_COOKIE['xssid']. "'/>";
	echo "<input type='hidden' name='section' value='" .$_POST['section']. "'/>";
	echo "<input type='hidden' name='id' value='" .$_POST['id']. "'/>";
		
	echo "</form>";
	//echo "<script type='text/javascript'>getProfile();";
}
?>
