<?php

$bad_items = array("-", ",", "&", "/", " ", "'", "&rsquo;", ".");
$good_items = array("", "", "", "", "", "", "", "", "");


// Google Analytics Account
$GA_query = mysql_query("SELECT * FROM global_settings");
$GA_row = mysql_fetch_array($GA_query);
$GA_email = $GA_row['ga_email'];
$GA_password = $GA_row['ga_password'];
$GA_profileID = $GA_row['ga_profile'];


$months[1] = "January";
$months[2] = "February";
$months[3] = "March";
$months[4] = "April";
$months[5] = "May";
$months[6] = "June";
$months[7] = "July";
$months[8] = "August";
$months[9] = "September";
$months[10] = "October";
$months[11] = "November";
$months[12] = "December";


$t_month = date("n");
$t_date = date("j");
$t_year = date("Y");

//***************************
		//	Various Global Variables
		//***************************
$states['CA'] = array(
			"AB"=>"Alberta", 
			"BC"=>"British Columbia",
			"MB"=>"Manitoba", 
			"NB"=>"New Brunswick",
			"NL"=>"Newfoundland and Labrador", 
			"NT"=>"Northwest Territories", 
			"NS"=>"Nova Scotia", 
			"NU"=>"Nunavut",
			"ON"=>"Ontario",   
			"PE"=>"Prince Edward Island", 
			"QC"=>"Quebec", 
			"SK"=>"Saskatchewan",    
			"YT"=>"Yukon Territory");
		
$states['US'] = array(
			'AL'=>"Alabama",  
			'AK'=>"Alaska",  
			'AZ'=>"Arizona",  
			'AR'=>"Arkansas",  
			'CA'=>"California",  
			'CO'=>"Colorado",  
			'CT'=>"Connecticut",  
			'DE'=>"Delaware",  
			'DC'=>"District Of Columbia",  
			'FL'=>"Florida",  
			'GA'=>"Georgia",  
			'HI'=>"Hawaii",  
			'ID'=>"Idaho",  
			'IL'=>"Illinois",  
			'IN'=>"Indiana",  
			'IA'=>"Iowa",  
			'KS'=>"Kansas",  
			'KY'=>"Kentucky",  
			'LA'=>"Louisiana",  
			'ME'=>"Maine",  
			'MD'=>"Maryland",  
			'MA'=>"Massachusetts",  
			'MI'=>"Michigan",  
			'MN'=>"Minnesota",  
			'MS'=>"Mississippi",  
			'MO'=>"Missouri",  
			'MT'=>"Montana",
			'NE'=>"Nebraska",
			'NV'=>"Nevada",
			'NH'=>"New Hampshire",
			'NJ'=>"New Jersey",
			'NM'=>"New Mexico",
			'NY'=>"New York",
			'NC'=>"North Carolina",
			'ND'=>"North Dakota",
			'OH'=>"Ohio",  
			'OK'=>"Oklahoma",  
			'OR'=>"Oregon",  
			'PA'=>"Pennsylvania",  
			'RI'=>"Rhode Island",  
			'SC'=>"South Carolina",  
			'SD'=>"South Dakota",
			'TN'=>"Tennessee",  
			'TX'=>"Texas",  
			'UT'=>"Utah",  
			'VT'=>"Vermont",  
			'VA'=>"Virginia",  
			'WA'=>"Washington",  
			'WV'=>"West Virginia",  
			'WI'=>"Wisconsin",  
			'WY'=>"Wyoming");
$countries['CA'] = "Canada";
$countries['US'] = "United States";

$pages = array();

include "../includes/database.php";
$db = "nav1";

$query = "SELECT * FROM $db WHERE reference != 1404 ORDER BY ordering, id";
$result = mysql_query($query);
$num_results = mysql_num_rows($result);

for ($i = 0; $i < $num_results; $i++) {
	$row = mysql_fetch_array($result);
	
	
	$pages[$i][0][0][0][0][0] = $row['name'];
	$pages[$i][0][0][0][0][1] = $db;
	$pages[$i][0][0][0][0][2] = $row['page'];
	$pages[$i][0][0][0][0][3] = $row['ordering'] != 101 ? $row['ordering'] : '';
	$pages[$i][0][0][0][0][4] = $row['showhide'] != 0 ? $row['showhide'] : '';
	
	// Skip listing the Careers pages
	if($row['page'] == "careers") { continue; }
	
	$db2 = "nav2_" .$row['reference'];
	
	$exists = @mysql_query("SELECT * FROM $db2");
	if ($exists) {
	
		$query2 = "SELECT * FROM $db2 ORDER BY ordering, id";
		$result2 = mysql_query($query2);
		$num_results2 = mysql_num_rows($result2);
		
		for ($j = 0; $j < $num_results2; $j++) {
			$row2 = mysql_fetch_array($result2);
			
			$pages[$i][($j+1)][0][0][0][0] = $row2['name'];
			$pages[$i][($j+1)][0][0][0][1] = $db2;
			$pages[$i][($j+1)][0][0][0][2] = $row2['page'];
			$pages[$i][($j+1)][0][0][0][3] = $row2['ordering'] != 101 ? $row2['ordering'] : '';
			$pages[$i][($j+1)][0][0][0][4] = $row2['showhide'] != 0 ? $row2['showhide'] : '';
			
			$db3 = "nav3_" .$row['reference']. $row2['reference'];
	
			$exists2 = @mysql_query("SELECT * FROM $db3");
			if ($exists2) {
			
			
				$query3 = "SELECT * FROM $db3 ORDER BY ordering, id";
				$result3 = mysql_query($query3);
				$num_results3 = mysql_num_rows($result3);
				
				for ($k = 0; $k < $num_results3; $k++) {
					$row3 = mysql_fetch_array($result3);
					
					$pages[$i][($j+1)][($k+1)][0][0][0] = $row3['name'];
					$pages[$i][($j+1)][($k+1)][0][0][1] = $db3;
					$pages[$i][($j+1)][($k+1)][0][0][2] = $row3['page'];
					$pages[$i][($j+1)][($k+1)][0][0][3] = $row3['ordering'] != 101 ? $row3['ordering'] : '';
					$pages[$i][($j+1)][($k+1)][0][0][4] = $row3['showhide'] != 0 ? $row3['showhide'] : '';
					
					
					$db4 = "nav4_" .$row['reference']. $row2['reference']. $row3['reference'];
	
					$exists3 = @mysql_query("SELECT * FROM $db4");
					if ($exists3) {
					
						
						$query4 = "SELECT * FROM $db4 ORDER BY ordering, id";
						$result4 = mysql_query($query4);
						$num_results4 = mysql_num_rows($result4);
						
						for ($l = 0; $l < $num_results4; $l++) {
							$row4 = mysql_fetch_array($result4);
							
							$pages[$i][($j+1)][($k+1)][($l+1)][0][0] = $row4['name'];
							$pages[$i][($j+1)][($k+1)][($l+1)][0][1] = $db4;
							$pages[$i][($j+1)][($k+1)][($l+1)][0][2] = $row4['page'];
							$pages[$i][($j+1)][($k+1)][($l+1)][0][3] = $row4['ordering'] != 101 ? $row4['ordering'] : '';
							$pages[$i][($j+1)][($k+1)][($l+1)][0][4] = $row4['showhide'] != 0 ? $row4['showhide'] : '';
							
							$db5 = "nav5_" .$row['reference']. $row2['reference']. $row3['reference']. $row4['reference'];
	
							$exists4 = @mysql_query("SELECT * FROM $db5");
							if ($exists4) {
							
						
								$query5 = "SELECT * FROM $db5 ORDER BY ordering, id";
								$result5 = mysql_query($query5);
								$num_results5 = mysql_num_rows($result5);
								
								for ($m = 0; $m < $num_results5; $m++) {
									$row5 = mysql_fetch_array($result5);
									
									$pages[$i][($j+1)][($k+1)][($l+1)][($m+1)][0] = $row5['name'];
									$pages[$i][($j+1)][($k+1)][($l+1)][($m+1)][1] = $db5;
									$pages[$i][($j+1)][($k+1)][($l+1)][($m+1)][2] = $row5['page'];
									$pages[$i][($j+1)][($k+1)][($l+1)][($m+1)][3] = $row5['ordering'] != 101 ? $row5['ordering'] : '';
									$pages[$i][($j+1)][($k+1)][($l+1)][($m+1)][4] = $row5['showhide'] != 0 ? $row5['showhide'] : '';
									
								}
							}
							
							
						}
					}
					
					
				}
			}
			
			
		}
	}
	
}


?>