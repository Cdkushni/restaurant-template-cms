<?php


if ($section == "Edit Page") {
	
	echo "<p>Click on a page below to edit the main content section.</p>";
	
	echo "<div class='tree_legend'>";
		echo "<p class='legend_title'><b>Legend:</b></p>";
		echo "<p><span class='legend_icon hidden'></span> <b>Hidden: </b> Can be accessed by direct link only.</p>";
		echo "<p><span class='legend_icon disabled'></span> <b>Disabled: </b> Cannot be accessed at all.</p>";
	echo "</div>";
	
	echo "<ul id='navigation'>";
	
	
		//lvl1
		$site_map = "";
		for ($i=0; $i<count($pages); $i++) {
			$site_map .= "<li><form action='' method='POST'>";
			$site_map .= "<input type='submit' name='name' value='" . ($pages[$i][0][0][0][0][3] != '' ? $pages[$i][0][0][0][0][3] . " - " : '') .$pages[$i][0][0][0][0][0] ."' class='admin_btn" . ($pages[$i][0][0][0][0][4] > 0 ? ($pages[$i][0][0][0][0][4] == 1 ? ' hidden' : ' disabled') : '') . "' />\n";
			$site_map .= "<input type='hidden' name='page' value='" .$pages[$i][0][0][0][0][2] ."' />\n";
			$site_map .= "<input type='hidden' name='section' value='Manage Page Content' />";
			$site_map .= "<input type='hidden' name='database' value='" .$pages[$i][0][0][0][0][1] ."' />";
			$site_map .= "<input type='hidden' name='username' value='" .$username ."' />";
			$site_map .= "<input type='hidden' name='password' value='" .$password ."' />";
			
			$site_map .= "<input type='hidden' name='p1' value='' />";
			$site_map .= "<input type='hidden' name='p2' value='' />";
			$site_map .= "<input type='hidden' name='p3' value='' />";
			$site_map .= "<input type='hidden' name='p4' value='' />";
			
			$site_map .= "</form>";
			$site_map .= "<ul>\n";
			
			//lvl2
			for ($j=1; $j<count($pages[$i]); $j++) {
				$site_map .= "<li><form action='' method='POST'>";
				$site_map .= "<input type='submit' name='name' value='" . ($pages[$i][$j][0][0][0][3] != '' ? $pages[$i][$j][0][0][0][3] . " - " : '') .$pages[$i][$j][0][0][0][0] ."' class='admin_btn" . ($pages[$i][$j][0][0][0][4] > 0 ? ($pages[$i][$j][0][0][0][4] == 1 ? ' hidden' : ' disabled') : '') . "' />\n";
				$site_map .= "<input type='hidden' name='page' value='" .$pages[$i][$j][0][0][0][2] ."' />\n";
				$site_map .= "<input type='hidden' name='section' value='Manage Page Content' />";
				$site_map .= "<input type='hidden' name='database' value='" .$pages[$i][$j][0][0][0][1] ."' />";
				$site_map .= "<input type='hidden' name='username' value='" .$username ."' />";
				$site_map .= "<input type='hidden' name='password' value='" .$password ."' />";
				
				$site_map .= "<input type='hidden' name='p1' value='" .$pages[$i][0][0][0][0][2] ."' />";
				$site_map .= "<input type='hidden' name='p2' value='' />";
				$site_map .= "<input type='hidden' name='p3' value='' />";
				$site_map .= "<input type='hidden' name='p4' value='' />";
				
				$site_map .= "</form>";
				$site_map .= "<ul>\n";
				
					//lvl3
					for ($k=1; $k<count($pages[$i][$j]); $k++) {
						$site_map .= "<li><form action='' method='POST'>";
						$site_map .= "<input type='submit' name='name' value='" . ($pages[$i][$j][$k][0][0][3] != '' ? $pages[$i][$j][$k][0][0][3] . " - " : '') .$pages[$i][$j][$k][0][0][0] ."' class='admin_btn" . ($pages[$i][$j][$k][0][0][4] > 0 ? ($pages[$i][$j][$k][0][0][4] == 1 ? ' hidden' : ' disabled') : '') . "' />";
						$site_map .= "<input type='hidden' name='page' value='" .$pages[$i][$j][$k][0][0][2] ."' />\n";
						$site_map .= "<input type='hidden' name='section' value='Manage Page Content' />";
						$site_map .= "<input type='hidden' name='database' value='" .$pages[$i][$j][$k][0][0][1] ."' />";
						$site_map .= "<input type='hidden' name='username' value='" .$username ."' />";
						$site_map .= "<input type='hidden' name='password' value='" .$password ."' />";
						
						$site_map .= "<input type='hidden' name='p1' value='" .$pages[$i][0][0][0][0][2] ."' />";
						$site_map .= "<input type='hidden' name='p2' value='" .$pages[$i][$j][0][0][0][2] ."' />";
						$site_map .= "<input type='hidden' name='p3' value='' />";
						$site_map .= "<input type='hidden' name='p4' value='' />";
						
						$site_map .= "</form>";
						$site_map .= "<ul>\n";
						
							//lvl4
							for ($l=1; $l<count($pages[$i][$j][$k]); $l++) {
								$site_map .= "<li><form action='' method='POST'>";
								$site_map .= "<input type='submit' name='name' value='" . ($pages[$i][$j][$k][$l][0][3] != '' ? $pages[$i][$j][$k][$l][0][3] . " - " : '') .$pages[$i][$j][$k][$l][0][0] ."' class='admin_btn" . ($pages[$i][$j][$k][$l][0][4] > 0 ? ($pages[$i][$j][$k][$l][0][4] == 1 ? ' hidden' : ' disabled') : '') . "' />";
								$site_map .= "<input type='hidden' name='page' value='" .$pages[$i][$j][$k][$l][0][2] ."' />\n";
								$site_map .= "<input type='hidden' name='section' value='Manage Page Content' />";
								$site_map .= "<input type='hidden' name='database' value='" .$pages[$i][$j][$k][$l][0][1] ."' />";
								$site_map .= "<input type='hidden' name='username' value='" .$username ."' />";
								$site_map .= "<input type='hidden' name='password' value='" .$password ."' />";
								
								$site_map .= "<input type='hidden' name='p1' value='" .$pages[$i][0][0][0][0][2] ."' />";
								$site_map .= "<input type='hidden' name='p2' value='" .$pages[$i][$j][0][0][0][2] ."' />";
								$site_map .= "<input type='hidden' name='p3' value='" .$pages[$i][$j][$k][0][0][2] ."' />";
								$site_map .= "<input type='hidden' name='p4' value='' />";
								
								$site_map .= "</form>";
								$site_map .= "<ul>\n";
								
									//lvl5
									for ($m=1; $m<count($pages[$i][$j][$k][$l]); $m++) {
										$site_map .= "<li><form action='' method='POST'>";
										$site_map .= "<input type='submit' name='name' value='" . ($pages[$i][$j][$k][$l][$m][3] != '' ? $pages[$i][$j][$k][$l][$m][3] . " - " : '') .$pages[$i][$j][$k][$l][$m][0] ."' class='admin_btn" . ($pages[$i][$j][$k][$l][$m][4] > 0 ? ($pages[$i][$j][$k][$l][$m][4] == 1 ? ' hidden' : ' disabled') : '') . "' />";
										$site_map .= "<input type='hidden' name='page' value='" .$pages[$i][$j][$k][$l][$m][2] ."' />\n";
										$site_map .= "<input type='hidden' name='section' value='Manage Page Content' />";
										$site_map .= "<input type='hidden' name='database' value='" .$pages[$i][$j][$k][$l][$m][1] ."' />";
										$site_map .= "<input type='hidden' name='username' value='" .$username ."' />";
										$site_map .= "<input type='hidden' name='password' value='" .$password ."' />";
										
										$site_map .= "<input type='hidden' name='p1' value='" .$pages[$i][0][0][0][0][2] ."' />";
										$site_map .= "<input type='hidden' name='p2' value='" .$pages[$i][$j][0][0][0][2] ."' />";
										$site_map .= "<input type='hidden' name='p3' value='" .$pages[$i][$j][$k][0][0][2] ."' />";
										$site_map .= "<input type='hidden' name='p4' value='" .$pages[$i][$j][$k][$l][0][2] ."' />";
										
										$site_map .= "</form>";
										$site_map .= "</li>\n";
									}
									
									$site_map .= "</ul>\n";
									$site_map .= "</li>\n";
								
							}
							
							$site_map .= "</ul>\n";
							$site_map .= "</li>\n";
							
						}
					
					$site_map .= "</ul>\n";
					$site_map .= "</li>\n";
				
			}
			
			$site_map .= "</ul>\n";
			$site_map .= "</li>\n";
			
		}
	
	//remove extra uls
	$site_map = str_replace("<ul>\n</ul>\n", "", $site_map);
	
	echo $site_map;
	
	echo "</ul>";
	echo "</li>";
	echo "</ul>";
								
}
?>