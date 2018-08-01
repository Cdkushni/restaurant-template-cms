<?php

//prevenet sql injections
function mysql_escape(){
if (count($_REQUEST)>0){
	foreach($_REQUEST AS $key => $data){
		if (is_array($_REQUEST[$key])){
			foreach($_REQUEST[$key] AS $iKey => $iData){
				if (get_magic_quotes_gpc()) {
					$_REQUEST[$key][$iKey] = stripslashes($_REQUEST[$key][$iKey]);
				}
				$_REQUEST[$key][$iKey] = mysql_real_escape_string($iData);	
			}
		}else{		
			if (get_magic_quotes_gpc()) {
				$_REQUEST[$key] = stripslashes($_REQUEST[$key]);
			}
			$_REQUEST[$key] = mysql_real_escape_string($data);	
		}
	}
}
}

$bad_items = array("-", ",", "&", "/", " ", "'", "&rsquo;", ".");
$good_items = array("", "", "", "", "", "", "", "", "");

function login () {
	global $username;
	global $password;
	
	$db = "login";

	@$query = "SELECT * FROM $db WHERE username = '" .mysql_real_escape_string($username). "'";
	$result = mysql_query($query);
	$count = mysql_num_rows($result);
	if($count > 0){
		$row = mysql_fetch_array($result);
			
		$p = $row['password'];
		$salt = $row['salt'];
		
		//encrypt password
		$encrypt = sha1_salt($password, $salt);
		$pw = $encrypt['string'];
	
		if ($p == $pw) {
			return true;
		} else {
			echo "<script type=\"text/javascript\">alert ('Invalid Login');</script>";
			return false;	
		}
	}else{
		echo "<script type=\"text/javascript\">alert ('Invalid Login');</script>";
		return false;
	}
	
}
function renderGravatar($image){
	if (is_file($image)){
		$dims = getimagesize($image);
		if ($dims[0]>=$dims[1]){
			//width is larger
			$img = "<img src='".$image."' style='max-height:30px'/>";						
		}else{
			//height is larger						
			$img = "<img src='".$image."' style='max-width: 30px;'/>";
		}		
	}else{
		$img = '';
	}
	
	return "<div class='gravatar' style='width: 30px; height: 30px;'><div style='overflow: hidden; width: 30px; height: 30px;'>".$img."</div></div>";

}

//encrypt passwords with random salts.
function sha1_salt($string, $salt='') {
    	
	if ($salt!=""){
		//use hash stored in db
		$hash['salt'] = $salt;	
	}else{
		//generate new hash	
		$chars = str_split('~`!@#$%^&*()[]{}-_\/|\'";:,.+=<>?');
	    $keys = array_rand($chars, 10);
		
	    foreach($keys as $key) {
	        $hash['salt'][] = $chars[$key];
	    }
	
	    $hash['salt'] = implode('', $hash['salt']);
	    $hash['salt'] = sha1($hash['salt']);
	}

	//do magic
	$hash['string'] = sha1($hash['salt'].$string.$hash['salt']);
    return $hash;
}

// ALERT FUNCTION
function alert ($text, $TF) {
	echo "<div style='width:600px;'>";
		if ($TF) {
			echo "<div id='alert_success'><p><b>Success!</b></p></div>";
		} else {
			echo "<div id='alert_error'><p><b>Error!</b></p></div>";
		}
		
		echo "<div id='alert_message' class='clearfix'>";
		echo $text;
		echo "</div>";
	echo "</div>";
}

// IMPORTANT FUNCTION
function important ($text) {
	
	echo "<table cellpadding='0' cellspacing='0' border='0' style='width:100%;'>";
	echo "<tr>";	
	echo "<td class='imp_top' onclick='javascript:animatedcollapse.toggle(\"important\");'><p><b>IMPORTANT!</b></p></td>";
	echo "</tr>";
	
	echo "<tr>";
	echo "<td id='imp_info'><div id='important'>";
	echo $text;
	echo "</div></td>";
	echo "</tr>";
	echo "</table>";
	
}

//create reference numbers
function create_reference ($db,$int1,$int2) {
	for ($i=$int1; $i<$int2; $i++) {
		
		$success = true;
		
		$sql = mysql_query("ALTER TABLE $db ORDER BY id")or die("Could Not Re-order Database");
		$query = "SELECT * FROM $db";
		$result = mysql_query($query);
		$num_results = mysql_num_rows($result);
		
		for ($j = 0; $j < $num_results; $j++) {
			$row = mysql_fetch_array($result);
			
			$r = $row['reference'];
			
			if ($r == $i) {
				$success = false;	
			}
			
		}

		if ($success) {
			break;	
		}
		
	}
	
	return $i;
}

//delete directory
function delete_directory($dirname) {
	if (is_dir($dirname))
		chmod($dirname, 0777);
		$dir_handle = opendir($dirname);
	if (!$dir_handle)
		return false;
	while($file = readdir($dir_handle)) {
		if ($file != "." && $file != "..") {
			chmod($dirname."/".$file, 0777);
			if (!is_dir($dirname."/".$file))
			unlink($dirname."/".$file);
			else
			delete_directory($dirname.'/'.$file);    
		}
	}
	chmod($dir_handle, 0775);
	closedir($dir_handle);
	rmdir($dirname);
}


//create safe page name
function create_pagename($name){
	$pagename = html_entity_decode($name);
	$pagename = str_replace("&rsquo;", "", strtolower($pagename));
	$pagename = str_replace(" ", "-", strtolower($pagename));
	$pagename = str_replace("/", "-", strtolower($pagename));
	$pagename = str_replace("_", "-", strtolower($pagename));
	$pagename = preg_replace('/[^a-zA-Z0-9-]/s', '', $pagename);
	$pagename = str_replace("---", "-", strtolower($pagename));
	$pagename = str_replace("--", "-", strtolower($pagename));
	
	return $pagename;
		
}
function getDomainName() {
	return "http://" . $_SERVER['SERVER_NAME'].'/';
}

//sitemap xml
function sitemapXML () {
	
	$path = getDomainName();
	$sitepages = array();
	
	$filename = "../sitemap.xml";	
	$doc = new DOMDocument('1.0');
	$doc->formatOutput = true;
	
	$root = $doc->createElement("urlset");
	$root = $doc->appendChild($root);
	$root->setAttribute("xmlns", "http://www.sitemaps.org/schemas/sitemap/0.9");
	$root->setAttribute("xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");
	$root->setAttribute("xsi:schemaLocation", "http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd");
	
	$navqry = mysql_query("SELECT * FROM nav1 WHERE showhide = 0 && type = 0")or die('sitemap error');
	while($nav = mysql_fetch_array($navqry)){
		$sitepage['url'] = $path .$nav['page']. "/";
		$sitepage['lastmod'] = $nav['last_modified'];
		$sitepage['priority'] = "0.80";	
		
		if($nav['reference'] == 1001){
			$sitepage['url'] = $path;
			$sitepage['priority'] = "1.00";	
		}
		
		array_push($sitepages, $sitepage);
		
		@$nav2qry = mysql_query("SELECT * FROM `nav2_" .$nav['reference']. "` WHERE showhide = 0 && type = 0");
		@$nav2num = mysql_num_rows($nav2qry);
		if($nav2num > 0){
			while(@$nav2 = mysql_fetch_array($nav2qry)){
				$sitepage['url'] = $path .$nav['page']. "/" .$nav2['page']. "/";
				$sitepage['lastmod'] = $nav2['last_modified'];
				$sitepage['priority'] = "0.64";	
				
				array_push($sitepages, $sitepage);
				
				@$nav3qry = mysql_query("SELECT * FROM `nav3_" .$nav['reference']. $nav2['reference']. "` WHERE showhide = 0 && type = 0");
				@$nav3num = mysql_num_rows($nav3qry);
				if($nav3num > 0){
					while(@$nav3 = mysql_fetch_array($nav3qry)){
						$sitepage['url'] = $path .$nav['page']. "/" .$nav2['page']. "/" .$nav3['page']. "/";
						$sitepage['lastmod'] = $nav3['last_modified'];
						$sitepage['priority'] = "0.64";	
						
						array_push($sitepages, $sitepage);
					}
				}
			}
		}
	}
	
	//build xml
	for($s=0; $s<count($sitepages); $s++){
		
		$url = $doc->createElement("url");
		$url = $root->appendChild($url);
		
		$loc = $doc->createElement("loc");
		$loc = $url->appendChild($loc);
		$text = $doc->createTextNode($sitepages[$s]['url']);
		$text = $loc->appendChild($text);
		
		$lastmod = $doc->createElement("lastmod");
		$lastmod = $url->appendChild($lastmod);
		$text = $doc->createTextNode($sitepages[$s]['lastmod']);
		$text = $lastmod->appendChild($text);
		
		$changefreq = $doc->createElement("changefreq");
		$changefreq = $url->appendChild($changefreq);
		$text = $doc->createTextNode("weekly");
		$text = $changefreq->appendChild($text);
		
		$priority = $doc->createElement("priority");
		$priority = $url->appendChild($priority);
		$text = $doc->createTextNode($sitepages[$s]['priority']);
		$text = $priority->appendChild($text);
	
	}

	$doc->save($filename);
	
}


//set max height and width function
function uploadImage ($dir, $image, $type, $w, $h, $newname) {
	
	if ($image['name'] != "") {
		
		$success = false;
		$file = false;
		
		if ($type == "jpg") {
			if ($image['type'] == "image/jpg" || $image['type'] == "image/jpeg" || $image['type'] == "image/pjpeg") {
				$success = true;	
			}
			
		} else if ($type == "png") {
			if ($image['type'] == "image/png") {
				$success = true;	
			}
		
		} else if ($type == "pdf") {
			if ($image['type'] == "application/pdf") {
				$success = true;	
				$file = true;
			}
		}
		
		if ($success) {
			
			if ($file) {
				if ($image['size'] > 1024000) {
					// too big
					alert ("<p><b>Error!</b> PDF filesize is too large.</p>", false);
					
				} else {
					chmod($dir, 0777);
					$large 		= $dir .$image['name'];
					copy ($image['tmp_name'], $large) or die ("Could not copy file");
					chmod($large, 0775);
					chmod($dir, 0775);
				}
				
				
			} else {
			
				if ($image['size'] > 1000000) {
					// too big
					alert ("<p><b>Error!</b> Image filesize is too large.</p>", false);
					
				} else {
					
					chmod($dir, 0777);
					
					$original 	= $dir ."or_" .$image['name'];
					if($newname != ""){
						$large 	= $dir .$newname;
					}else{
						$large 	= $dir .$image['name'];
					}
					
					
					if ($type == "png") {
						copy ($image['tmp_name'], $large) or die ("Could not copy file");
					} else if ($type == "jpg") {
						copy ($image['tmp_name'], $original) or die ("Could not copy jpg file");
						
						$dest_hires = $large;
						
						$ims = getimagesize($original);
					
						$newwidth=$ims[0];
						$newheight=$ims[1];
						
					
			
						// CREATING LARGE IMAGE-------->
						// landscape
						if ($newwidth>=$newheight) {
							if ($newwidth<$w) {
								$resizewidth=$newwidth;
								$resizeheight=$newheight;
							} else {
								$aspectRatio = $newwidth/$w;
								$resizewidth=$w;
								$resizeheight=ceil($newheight/$aspectRatio);
							}
							
						// portrait
						} else {
							
							if($newheight<$h){
								$resizewidth=$newwidth;
								$resizeheight=$newheight;
							}else{
								$resizeheight = $h;
								$aspectRatio=$newheight/$h;
								$resizewidth=ceil($newwidth/$aspectRatio);
							}
							
							
							
						}
						
						
						
						$img = imagecreatetruecolor($resizewidth,$resizeheight);
						$org_img = imagecreatefromjpeg($original);
	
						//this is the almost thumbnail sized image with everything resized to ratio
						
						imagecopyresampled($img, $org_img, 0, 0, 0, 0, $resizewidth, $resizeheight, $newwidth, $newheight);
						$img2 = imagecreatetruecolor($resizewidth,$resizeheight);
						
						
						imagecopyresized($img2, $img, 0, 0, 0, 0, $newwidth, $newheight, $newwidth, $newheight);
						imagejpeg($img2,$dest_hires,100);
						imagedestroy($img);
						imagedestroy($img2);
						
						// DONE CREATING LARGE IMAGE -->
					
					
						
						
						
						
						
						// delete the original file
						unlink($original);
						chmod($large, 0775);
						
						chmod($dir, 0775);
					}
					
				}
			}
			
			
		} else {
			// wrong filetype
			alert ("<p><b>Error!</b> Image is wrong filetype. Please ensure you image is in " .$type ." format.</p>", false);
		}
	}

	
	
}

//set specific size function
function uploadImage2 ($dir, $image, $type, $w, $h, $newname) {
	
	if ($image['name'] != "") {
		
		$success = false;
		$file = false;
		
		if ($type == "jpg") {
			if ($image['type'] == "image/jpg" || $image['type'] == "image/jpeg" || $image['type'] == "image/pjpeg") {
				$success = true;	
			}
			
		} else if ($type == "png") {
			if ($image['type'] == "image/png") {
				$success = true;	
			}
		
		} else if ($type == "pdf") {
			if ($image['type'] == "application/pdf") {
				$success = true;	
				$file = true;
			}
		}
		
		if ($success) {
			
			if ($file) {
				if ($image['size'] > 1024000) {
					// too big
					alert ("<p><b>Error!</b> PDF filesize is too large.</p>", false);
					
				} else {
					chmod($dir, 0777);
					$large 		= $dir .$image['name'];
					copy ($image['tmp_name'], $large) or die ("Could not copy file");
					chmod($large, 0775);
					chmod($dir, 0775);
				}
				
				
			} else {
			
				if ($image['size'] > 1000000) {
					// too big
					alert ("<p><b>Error!</b> Image filesize is too large.</p>", false);
					
				} else {
					
					chmod($dir, 0777);
					
					$original 	= $dir ."or_" .$image['name'];
					if($newname != ""){
						$large 	= $dir .$newname;
					}else{
						$large 	= $dir .$image['name'];
					}
					
					
					if ($type == "png") {
						copy ($image['tmp_name'], $large) or die ("Could not copy file");
					} else if ($type == "jpg") {
						copy ($image['tmp_name'], $original) or die ("Could not copy jpg file");
						
						$dest_hires = $large;
						
						$ims = getimagesize($original);
					
						$newwidth=$ims[0]; // width of original image
						$newheight=$ims[1]; // height
					
						//echo "Original Image Size: " .$newwidth ."px by " .$newheight ."px<br />";
						
						// CREATING LARGE IMAGE-------->
						// landscape
						if ($newwidth>=$newheight) {
							//echo "Landscape: ";
							if ($newwidth<$w) { // smaller than what we want
								//echo "too small<br />";
								$resizewidth=$newwidth;
								$resizeheight=$newheight;
								
							} else {
								//echo "big enough<br />";
								$aspectRatio = $newwidth/$w;
								$resizewidth=$w;
								$resizeheight=ceil($newheight/$aspectRatio);
							}
							
							//echo "New Image Size: " .$resizewidth ."px by " .$resizeheight ."px<br />";
							
							if ($resizeheight <= $h) {
								//echo "--> Redo cause new height is too small<br />";
								$aspectRatio = $newheight/$h;
								$resizewidth=ceil($newwidth/$aspectRatio);
								$resizeheight=$h;
							}
							
							
							
						// portrait
						} else {
							if ($newheight < $h) {
								//echo "too small<br />";
								$resizewidth=$newwidth;
								$resizeheight=$newheight;
							} else {
								//echo "big enough <br />";
								$resizeheight=$h;
								$aspectRatio=$newheight/$h;
								$resizewidth=ceil($newwidth/$aspectRatio);
							}
							
							
						}
						
						//echo "New Image Size: " .$resizewidth ."px by " .$resizeheight ."px<br />";
						
						
						if ($h > 0) {
							//echo "h greater than 0<br />";
							
							$img = imagecreatetruecolor($resizewidth,$resizeheight);
							$org_img = imagecreatefromjpeg($original);
		
							//this is the almost thumbnail sized image with everything resized to ratio
							
							imagecopyresampled($img, $org_img, 0, 0, 0, 0, $resizewidth, $resizeheight, $newwidth, $newheight);
							$img2 = imagecreatetruecolor($w,$h);
							//$img2 = imagecreatetruecolor($resizewidth,$resizeheight);
							
							imagecopyresized($img2, $img, 0, 0, 0, 0, $newwidth, $newheight, $newwidth, $newheight);
							imagejpeg($img2,$dest_hires,100);
							imagedestroy($img);
							imagedestroy($img2);
							
							// DONE CREATING LARGE IMAGE -->
						
						}
						
						
						//echo "Done<br /";
						
						
						// delete the original file
						unlink($original);
						chmod($large, 0775);
						
						chmod($dir, 0775);
						
						//echo "Finished.";
					}
					
				}
			}
			
			
		} else {
			// wrong filetype
			alert ("<p><b>Error!</b> Image is wrong filetype. Please ensure you image is in " .$type ." format.</p>", false);
		}
	}

	
	
}


function sec2hms ($sec, $padHours = false) {

	// start with a blank string
	$hms = "";
	
	// do the hours first: there are 3600 seconds in an hour, so if we divide
	// the total number of seconds by 3600 and throw away the remainder, we're
	// left with the number of hours in those seconds
	$hours = intval(intval($sec) / 3600); 
	
	// add hours to $hms (with a leading 0 if asked for)
	$hms .= ($padHours) 
		  ? str_pad($hours, 2, "0", STR_PAD_LEFT). ":"
		  : $hours. ":";
	
	// dividing the total seconds by 60 will give us the number of minutes
	// in total, but we're interested in *minutes past the hour* and to get
	// this, we have to divide by 60 again and then use the remainder
	$minutes = intval(($sec / 60) % 60); 
	
	// add minutes to $hms (with a leading 0 if needed)
	$hms .= str_pad($minutes, 2, "0", STR_PAD_LEFT). ":";
	
	// seconds past the minute are found by dividing the total number of seconds
	// by 60 and using the remainder
	$seconds = intval($sec % 60); 
	
	// add seconds to $hms (with a leading 0 if needed)
	$hms .= str_pad($seconds, 2, "0", STR_PAD_LEFT);
	
	// done!
	return $hms;
    
 }
  
//convert date
function convert_date($date){
	global $months;
	
	$day = substr($date, -2, 2);
	if(substr($date, -4, 1) != 0){
		$month = substr($date, -4, 2);
	}else{
		$month = substr($date, -3, 1);
	}
	$year = substr($date, -8, 4);
	
	$date_string = $months[$month]. " " .$day. ", ". $year;
	
	return $date_string;
}

  
function show_statistics ($p) {
	global $GA_email;
	global $GA_password;
	global $GA_profileID;
	
	define('ga_email',$GA_email);
	define('ga_password',$GA_password);
	define('ga_profile_id',$GA_profileID);
	
	if($GA_email!='' && $GA_password!=''){
		
		
		require '../cms/includes/gapi/gapi.class.php';
		try{
			$ga = new gapi(ga_email,ga_password);
			
			$start_date=date('Y-m-d',strtotime('1 month ago'));
			$end_date=date('Y-m-d');
			
			$start_date_values = explode("-", $start_date);
			$end_date_values = explode("-", $end_date);
			$d1 = GregorianToJD($start_date_values[1], $start_date_values[2], $start_date_values[0]);
			$d2 = GregorianToJD($end_date_values[1], $end_date_values[2], $end_date_values[0]);
			$total_days = ($d2-$d1)+1;
			
			
			echo "<p><b>Page Statistics From: " .convert_date(str_replace("-", "", $start_date)) ." to " .convert_date(str_replace("-", "", $end_date)) ."</b></p>";
			$ga->requestReportData(ga_profile_id,array("pagePath"),array('pageviews', 'avgTimeOnPage', 'exitRate', 'visitBounceRate'),'-pageviews',"", $start_date, $end_date);
			
			$flag = false;
			
			foreach($ga->getResults() as $result){
				if (strval($result->getPagePath()) == $p) {
					echo "<table cellpadding='0' cellspacing='0' border='0' style='width:100%;' class='removepadding' id='stats_overview'>";
					echo "<tr>";
					echo "<td><p><span class='alternate' style='color:#CCC;'>Pageviews <sup title='<span>Pageviews</span><p>The total number of pageviews for this page.</p>'>?</sup></span><br />" .$result->getPageviews() ."<br /><span class='alternate'>% Of Site Total: " .round($result->getPageviews()/$ga->getPageviews()*100, 2) ."%</span></p></td>";
					echo "<td><p><span class='alternate' style='color:#CCC;'>Avg. Time On Page <sup title='<span>Avg. Time On Page</span><p>The average amount of time visitors spent viewing this page or a set of pages.</p>'>?</sup></span><br />" .sec2hms($result->getAvgTimeOnPage(), true) ."<br /><span class='alternate'>Site Avg: " .sec2hms($ga->getAvgTimeOnPage(), true) ."</span></p></td>";
					echo "<td><p><span class='alternate' style='color:#CCC;'>Exit % <sup title='<span>Exit %</span><p>The percentage of site exits that occurred out of the total page views.</p>'>?</sup></span><br />" .round($result->getExitRate(), 2) ."%<br /><span class='alternate'>Site Avg: " .round($ga->getExitRate(), 2) ."%</span></p></td>";
					echo "<td class='last'><p><span class='alternate' style='color:#CCC;'>Bounce Rate <sup title='<span>Bounce Rate</span><p>The percentage of single-page visits (i.e., visits in which the person left your site from the first page).</p>'>?</sup></span><br />" .round($result->getVisitBounceRate(), 2) ."%<br /><span class='alternate'>Site Avg: " .round($ga->getVisitBounceRate(), 2) ."%</span></p></td>";
					echo "</tr>";
					echo "</table>";
					
					$flag = true;
				}
			}
			
			if (!$flag) { echo "<p><i>No Statistics Available At This Time.</i></p>"; }
		}catch(Exception $e){
			echo "<p><i>There was a problem accessing the google analytics account. Please double check the credentials in the global website settings page of the CMS.</i></p>";	
		}
		
	}
}


//check for special characters and spaces
function checkSpecialChars($string){
	if(!preg_match("#^[-A-Za-z\&0-9\&\_;' .]*$#",$string)){
		return true;
	}
	if(strstr($string, " ")){
		return true;
	}
	return false;	
	
}


/*function smtpEmail ($to, $subject, $message) {
	require_once "Mail.php";
		
	$from 		= "Pixel Army (Strategic Website Design) <noreply@pixelarmy.ca>";
	$to 		= $to;
	$subject 	= $subject;
	$body 		= $message;
	
	$host 		= "mail.emailsrvr.com";
	$username 	= "noreply@pixelarmy.ca";
	$password 	= "rebefredeS3e";
	
	
	
	$headers = array ('From' => $from,
	  'To' => $to,
	  'Subject' => $subject,
	  'MIME-Version' => '1.0', 'Content-Type' => 'text/html;charset=iso-8859-1', 'Content-Transfer-Encoding' => '8bit', 'X-Priority' => '3', 'Importance' => 'Normal');
	
	$smtp = Mail::factory('smtp',
	  array ('host' => $host,
		'auth' => true,
		'username' => $username,
		'password' => $password));
	
	
	
	$mail = $smtp->send($to, $headers, $body);
	
	if (PEAR::isError($mail)) {
	  echo("<p><b>Error: </b>" . $mail->getMessage() . "</p>");
	} else {
	  //echo("<p>Message successfully sent!</p>");
	}
}*/


?>