<?php 
	if ($section==''){
		$result = mysql_query("SELECT * FROM promo_codes WHERE active = '0'");
		echo "<td><p>Total Active Promo Codes</p></td>";
		echo "<td><p>".number_format(mysql_num_rows($result))."</p></td>";
	}
	if ($section=='Add Promo Code' || $section=='Edit/Delete Promo Codes'){
		
		if ($_POST['submit']=="Delete" && $_POST['confirm']=="yes"){
			
			$result = mysql_query("DELETE FROM promo_codes WHERE promo_id = '".$_POST['id']."'");
			if (!mysql_error()){
				alert("<p>Promo Code has been deleted.</p>",true);
				$_POST['id']='';
			}
		}else if ($_POST['submit']=="Save Promo Code"){
			//validate
			if (!is_numeric($_POST['discount']) || $_POST['discount']<=0 || $_POST['discount']>100){
				alert('<p>Discount must be numeric and between 1 and 100</p>',false);
				$errors = true;
			}
			//check to see if code was used already
			$result = mysql_query("SELECT * FROM promo_codes WHERE code = '".$_POST['code']."'");
			if (mysql_num_rows($result)){
				alert("<p>Promo code <i>".$_POST['code']."</i> already in use</p>",false);
				$errors = true;
			}

			if (!$errors){
				$query = "INSERT INTO promo_codes (promo_id, code, discount, active) VALUES ('".$_POST['id']."','".$_POST['code']."','".$_POST['discount']."','".$_POST['active']."') ON DUPLICATE KEY UPDATE discount = '".$_POST['discount']."', code='".$_POST['code']."', active='".$_POST['active']."'";
				$result = mysql_query($query);
				

				if (!mysql_error()){
					alert("<p>Promo code sucessfully saved!</p>",true);		
					$_POST['id']="";
				}else{
					alert("<p>There was an error saving this promo code. ".mysql_error()."</p>",false);
				}
			}else{
				foreach($_POST AS $key=>$data){
					$promo[$key] = $data;					
				}
			}
		}		
		
		if (($_POST['id']=="") && ($section=="Edit/Delete Promo Codes")){
			//display table of articles
			echo "<table cellpadding='3' cellspacing='0' border='0' style='width:100%;' class='removepadding tablesorter stickytableheader'>";		
			echo "<thead>";
			echo "<th style='border-bottom: 1px solid #3C6C46;' width='40px' align='left'><p><b>Active</b></p></th>";
			echo" <th style='border-bottom: 1px solid #3C6C46;' align='left'><p><b>Code</b></p></th>";
			echo "<th style='border-bottom: 1px solid #3C6C46;' align='left'><p><b>Discount</b></p></th>";
			echo "<th style='border-bottom: 1px solid #3C6C46;' align='left'><p><b>Used</b></p></th>";
			echo "<th style='border-bottom: 1px solid #3C6C46;' width='50px' align='right' class='{sorter: false}'>&nbsp;</th>";
			echo "</thead>";
			
			$result = mysql_query("SELECT promo_codes.*, nu.num_used FROM promo_codes LEFT JOIN (SELECT count(promo_id) AS num_used, promo_id FROM orders GROUP BY promo_id) nu ON nu.promo_id = promo_codes.promo_id ORDER BY promo_codes.promo_id DESC");
			echo mysql_error();
			$count = 0;
			while($promo = mysql_fetch_array($result)){
				echo "<tr class='row".(($count%2)+1)."'>";
					echo "<td><p>".($promo['active']=='0' ? "<img src='../cms/images/icon_check.png'/>" : "")."</p></td>";
					echo "<td><p>".$promo['code']."</p></td>";
					echo "<td><p>".$promo['discount']."%</p></td>";
					echo "<td><p>".number_format($promo['num_used'])."</p></td>";
					echo "<td style='text-align:right'><form action='' method='post' style='margin: 0px;'><input type='hidden' name='id' value='".$promo['promo_id']."'/><input type='hidden' name='username' value='".$_POST['username']."'/><input type='hidden' name='password' value='".$_POST['password']."'/><input type='hidden' name='section' value='".$_POST['section']."'/><input type='submit' name='submit' value='Edit'/></form></td>";
				echo "</tr>";
				$count++;
					
			}
		}else if ($_POST['id']!="" || $section=="Add Promo Code"){
			if ($_POST['id'] && (!$errors)){
				$result = mysql_query("SELECT promo_codes.*, nu.num_used FROM promo_codes LEFT JOIN (SELECT count(promo_id) AS num_used, promo_id FROM orders GROUP BY promo_id) nu ON nu.promo_id = promo_codes.promo_id WHERE promo_codes.promo_id = '".$_POST['id']."'");
				$promo = mysql_fetch_array($result);
			}
			echo "<form action='' method='post' enctype='multipart/form-data'>";
			
			echo "<table cellpadding='3' cellspacing='0' border='0' width='100%' class='removepadding'>";
				echo "<tr>";
					echo "<td width='150px'><p>Discount: *</p></td>";
					echo "<td><p><input type='text' name='discount' value='".$promo['discount']."' class='input'/><sup class='help' title='<span>Discount</span><p>This is the percentage off that will be applied to orders if user uses this promo code.<br /><br /><b>Example:</b> 25</p>'>?</sup></p></td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td width='150px'><p>Code: </p></td>";
					echo "<td><p><input type='text' name='code' value='".$promo['code']."' class='input'/><sup class='help' title='<span>Code</span><p>This is the code that will need to be entered in order to receieve the discount during the purchase.<br /><br /><b>Example:</b> SAVE25</p>'>?</sup></p></td>";
				echo "</tr>";			
				echo "<tr>";
				echo "<td ><p>Active:</p></td>";
				echo "<td><select name='active' class='select'>";
				if($promo['showhide'] == 0){
					echo "<option value='0' selected='selected'>Active</option><option value='1'>Inactive</option>";
				}else{
					echo "<option value='0'>Active</option><option value='1' selected='selected'>Inactive</option>";
				}
				echo "</select></td>";
				echo "</tr>";
				echo "<tr>";
					if ($_POST['id']!="" && number_format($promo['num_used'],0)==0){
						echo "<td class='footer' align='left'><p><select name='confirm' class='select' style='width:50px'><option value='no'>No</option><option value='yes'>Yes</option></select><input type='submit' name='submit' value='Delete'/></td>";
					}else{
						echo "<td class='footer'>&nbsp;</td>";	
					}
					echo "<td style='border-top: 1px solid #3C6C46' align='right' class='footer'><p style='text-align:right'><input type='hidden' name='username' value='".$_POST['username']."'/><input type='hidden' name='password' value='".$_POST['password']."'/><input type='hidden' name='section' value='".$_POST['section']."'/><input type='hidden' name='id' value='".$_POST['id']."'/><input type='submit' name='submit' value='Save Promo Code'/></p></td>";
				echo "</tr>";
			echo "</table>";

		}
	}
?>