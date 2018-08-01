<?php 
	if ($section==''){
		$result = mysql_query("SELECT * FROM testimonials");
		echo "<td><p>Total Testimonials</p></td>";
		echo "<td><p>".number_format(mysql_num_rows($result))."</p></td>";
	}
	
	if ($section=='Add Testimonial' || $section=='Edit/Delete Testimonials'){
		
		if ($_POST['submit']=="Delete" && $_POST['confirm']=="yes"){
			//DELETE PROJECT

			$result = mysql_query("DELETE FROM testimonials WHERE testimonial_id = '".$_POST['id']."'");
			if (!mysql_error()){
				alert("<p>Testimonial successfully deleted.</p>",true);	
			}
			unset($_POST['id']);
			
			
		}else if ($_POST['submit']=="Save Testimonial"){
			//validate
			$errors = false;
			foreach($_POST AS $key=>$data){
				$_POST[$key] = trim($data);	
			}
			
			if ($_POST['name']==""){
				$errors = true;
				alert("<p>Testimonial is required.</p>",false);
			}
			
			if (!$errors){
				//go!				
				$query = "INSERT INTO testimonials (testimonial_id, name, wherefrom, testimonial, date_added) VALUES ('".$_POST['id']."','".$_POST['name']."','".$_POST['wherefrom']."','".$_POST['testimonial']."',now()) ON DUPLICATE KEY UPDATE name='".$_POST['name']."', wherefrom='".$_POST['wherefrom']."', testimonial='".$_POST['testimonial']."'";
				
				$result = mysql_query($query);
				
				if (!mysql_error()){
					alert("<p>Testimonial sucessfully saved!</p>",true);
					unset($testimonial);
					$_POST['id']="";
				}else{
					echo mysql_error();	
				}
			}else{
				foreach($_POST AS $key=>$data){
					$testimonial[$key] = $data;	
				
				}
			}	
		
		}
		
		if (($_POST['id']=="") && ($section=="Edit/Delete Testimonials")){
			//display table of articles
			echo "<table cellpadding='3' cellspacing='0' border='0' style='width:100%;' class='removepadding'>";		
			echo "<tr>";
			echo "<td style=' border-bottom: 1px solid #3C6C46; width: 200px;'><p><b>Name</b></p></td>";		
			echo "<td style='width: 200px; border-bottom: 1px solid #3C6C46; width: 80%;' align='right' ><p><b>Testimonial</b></p></td>";
			echo "<td style='border-bottom: 1px solid #3C6C46;' width='50px' align='right'>&nbsp;</td>";
			echo "</tr>";
			
			$result = mysql_query("SELECT * FROM testimonials");
			$count = 0;
			while($testimonial = mysql_fetch_array($result)){
				echo "<tr class='row".(($count%2)+1)."'>";
					echo "<td><p>".$testimonial['name']."</p></td>";
					echo "<td><p>".$testimonial['testimonial']."</p></td>";
					echo "<td><p><form action='' method='post' style='margin: 0px;'><input type='hidden' name='id' value='".$testimonial['testimonial_id']."'/><input type='hidden' name='username' value='".$_POST['username']."'/><input type='hidden' name='password' value='".$_POST['password']."'/><input type='hidden' name='section' value='".$_POST['section']."'/><input type='submit' name='submit' value='Edit' class='f_right'/></form></p></td>";
				echo "</tr>";
				$count++;
					
			}
		}else if ($_POST['id']!="" || $section=="Add Testimonial"){
			//ID is set, show form
			if ($_POST['id'] && (!$errors)){
				$result = mysql_query("SELECT * FROM testimonials WHERE testimonial_id = '".$_POST['id']."'");
				$testimonial = mysql_fetch_array($result);
			}
			echo "<form action='' method='post' enctype='multipart/form-data'>";

			echo "<table cellpadding='3' cellspacing='0' border='0' width='100%' class='removepadding'>";
				echo "<tr>";
					echo "<td width='135px'><p>Name:</p></td>";
					echo "<td><input type='text' name='name' value='".@$testimonial['name']."' class='input'/></td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td width='135px'><p>From:</p></td>";
					echo "<td><input type='text' name='wherefrom' value='".@$testimonial['wherefrom']."' class='input'/></td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td><p>Testimonial:</p></td>";
					echo "<td><p><textarea name='testimonial' class='input textarea'>".@$testimonial['testimonial']."</textarea></p></td>";
				echo "</tr>";			
				echo "<tr>";
					if ($_POST['id']!=""){
						echo "<td class='footer' align='left'><p><select name='confirm' class='select' style='width: 50px;'><option value='no'>No</option><option value='yes'>Yes</option></select><input type='submit' name='submit' value='Delete'/></p></td>";
					}else{
						echo "<td class='footer'>&nbsp;</td>";	
					}
					echo "<td class='footer' align='right'><input type='hidden' name='username' value='".$_POST['username']."'/><input type='hidden' name='password' value='".$_POST['password']."'/><input type='hidden' name='section' value='".$_POST['section']."'/><input type='hidden' name='id' value='".$_POST['id']."'/><input type='submit' name='submit' value='Save Testimonial'/></td>";
				echo "</tr>";
			echo "</table>";
			echo "</form>";
		}
	}
?>