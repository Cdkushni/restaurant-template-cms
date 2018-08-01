<?php 
	if ($section==''){
		$result = mysql_query("SELECT * FROM specials WHERE showhide = '0' AND expires >= now()");
		echo "<td><p>Total Active Specials</p></td>";
		echo "<td><p>".number_format(mysql_num_rows($result))."</p></td>";
	}
	if ($section=='Add Special' || $section=='Edit/Delete Specials'){
		
		if ($_POST['submit']=="Delete" && $_POST['confirm']=="yes"){
			
			if (is_file('../images/specials/'.$_POST['old_filename'])){
				unlink('../images/specials/'.$_POST['old_filename']);
				unlink('../images/specials/'.$_POST['old_filename']);
				unlink('../images/specials/full/'.$_POST['old_filename']);
			}

			$query = "DELETE FROM specials WHERE special_id = '".$_POST['id']."'";
			$result = mysql_query($query);
			if (!mysql_error()){
				alert("<p>Special successfully deleted!</p>",true);	
				$_POST['id']="";
				$section='Edit/Delete Garage Doors';
			}else{
				$errors = true;
				alert("<p>There was an error attempting to delete this special. Error: 301</p>",true);
			}	
			
			
		}else if ($_POST['submit']=="Save Special"){
			//validate
			$errors = false;
			foreach($_POST AS $key=>$data){
				$_POST[$key] = str_replace("'","&#39",trim($data));	
			}
			
			//Validation					
			if (!$errors){
		
				$newimage = $_POST['old_filename'];
				if ($_FILES['filename']['name']!=''){
					require_once('../includes/classes/imageman.class.php');
					$img = new Imageman();
					$img->load($_FILES['filename']['tmp_name']);
					$imgerror = $img->valid_image();
						//valid image, show crop tools
						$newimage = time().'.jpg';
						$img->save('../images/specials/full/',$newimage,'jpg');
						//move_uploaded_file($_FILES['filename']['tmp_name'],'../images/slideshow/full/'.$newimage);
						//do a smart crop for the time being, and redirect user to the crop
						require_once('../includes/classes/imageman.class.php');
						$img = new Imageman();
						if (!$img->load('../images/specials/full/'.$newimage)){
							$errors = true;
							alert('<p>Couldnt save image.</p>', false);
						}else{
							//delete old images
							if (is_file('../images/specials/full/'.$_POST['old_filename'])){
								unlink('../images/specials/full/'.$_POST['old_filename']);
								unlink('../images/specials/medium/'.$_POST['old_filename']);
								unlink('../images/specials/'.$_POST['old_filename']);
							}
							$img->smartCrop(294,144);
							$img->save('../images/specials/',$newimage,'jpg');


							$img->load('../images/specials/full/'.$newimage);
							$img->smartCrop(295,194);
							$img->save('../images/specials/medium/',$newimage,'.jpg');
							unset($img);

							$_SESSION['jcrop'][0]['filename'] = $newimage;
							$_SESSION['jcrop'][0]['img_path'] = '../images/specials/full/';
							$_SESSION['jcrop'][0]['target_path'] = '../images/specials/';
							$_SESSION['jcrop'][0]['from_section'] = $_POST['section'];
							$_SESSION['jcrop'][0]['target_width'] = 294;
							$_SESSION['jcrop'][0]['target_height'] = 144;

							$imagecrop = true;
						}					
				}
			

				if (!$errors){	
					$query = "INSERT INTO specials (special_id, name, description, expires, filename, ordering, showhide) VALUES ('".$_POST['id']."','".$_POST['name']."','".$_POST['description']."','".$_POST['expires']."','".$newimage."','".$_POST['ordering']."','".$_POST['showhide']."') ON DUPLICATE KEY UPDATE
								name='".$_POST['name']."',
								description = '".$_POST['description']."',
								expires = '".$_POST['expires']."',
								filename = '".$newimage."',
								ordering = '".$_POST['ordering']."',
								showhide='".$_POST['showhide']."'";

					$result = mysql_query($query);
					
					if (!mysql_error()){
						if($imagecrop){
							$_SESSION['jcrop_success'] = "<p>Specials successfully saved!</p>"; 
							echo "<form action='' method='post' id='frm'>";
							echo "<input type='hidden' name='username' value='".$_POST['username']."'/>";
							echo "<input type='hidden' name='password' value='".$_POST['password']."'/>";
							echo "<input type='hidden' name='section' value='Crop Image'/>";
							echo "<script language='JavaScript'>";
								echo "document.getElementById('frm').submit();";
							echo "</script>";
							echo "</form>";
						}
						alert("<p>Photo sucessfully saved!</p>",true);		
						$_POST['id']="";
					}else{
						alert("<p>There was an error saving this special. ".mysql_error()."</p>",false);
					}
				}
				
			}else{
				foreach($_POST AS $key=>$data){
					$special[$key] = $data;	
				
				}
			 }
				
				
			
		}
				
		if (($_POST['id']=="") && ($section=="Edit/Delete Specials")){
			//display table of articles
			echo "<table cellpadding='3' cellspacing='0' border='0' style='width:100%;' class='removepadding tablesorter stickytableheader'>";		
			echo "<thead>";
			echo "<th style='border-bottom: 1px solid #3C6C46;' width='80px' align='left' class='{sorter: false}'></p><b></b></p></th>";
			echo "<th style='border-bottom: 1px solid #3C6C46;' align='left'><p><b>Name</b></p></th>";		
			echo "<th style='border-bottom: 1px solid #3C6C46;' width='40px' align='right'><p style='text-align: center'><b>Active</b></p></th>";

			echo "<th style='border-bottom: 1px solid #3C6C46;' width='50px' align='right' class='{sorter: false}'>&nbsp;</th>";
			echo "</thead>";
			$result = mysql_query("SELECT * FROM specials ORDER BY ordering ASC, special_id ASC");
			$count = 0;
			while($special = mysql_fetch_array($result)){
				echo "<tr class='row".(($count%2)+1)."'>";
					echo "<td width='80px' align='left'>".renderGravatar((is_file('../images/specials/'.$special['filename']) ? "../images/specials/".$special['filename'] : ""))."</td>";
					echo "<td><p>".$special['name']."</p></td>";
					echo "<td><p  style='text-align: center'>".($special['showhide']=='0' ? "<img src='../cms/images/icon_check.png'/>" : "")."</p></td>";

					echo "<td style='text-align:right'><form action='' method='post' style='margin: 0px;'><input type='hidden' name='id' value='".$special['special_id']."'/><input type='hidden' name='username' value='".$_POST['username']."'/><input type='hidden' name='password' value='".$_POST['password']."'/><input type='hidden' name='section' value='".$_POST['section']."'/><input type='submit' name='submit' value='Edit'/></form></td>";
				echo "</tr>";
				$count++;
			}
		}else if ($_POST['id']!="" || $section=="Add Special"){
			
			if ($_POST['id'] && (!$errors)){
				$result = mysql_query("SELECT * FROM specials WHERE special_id = '".$_POST['id']."'");
				$special = mysql_fetch_array($result);
			}
			echo "<form action='' method='post' enctype='multipart/form-data'>";

			echo "<table cellpadding='3' cellspacing='0' border='0' width='100%' class='removepadding'>";
				echo "<tr>";
					echo "<td width='150px'><p>Name:</p></td>";
					echo "<td><p><input type='text' name='name' value='".@$special['name']."' class='input'/></p></td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td><p>Expires:</p></td>";
					echo "<td><p><input type='text' name='expires' class='input datepicker' value='".$special['expires']."'/></p></td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td><p>Description:<p></td>";
					echo "<td><p><textarea name='description' class='input textarea'>".$special['description']."</textarea></p></td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td><p>".(is_file('../images/specials/'.$special['filename']) ? "<a href='../images/specials/full/".$special['filename']."' rel='prettyPhoto' target='_blank'>Image:</a>" : "Image:")."</p></td>";
					echo "<td><p><input type='file' name='filename' class='input' /><input type='hidden' name='old_filename' value='".$special['filename']."'/></p></td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td><p>Ordering:</p></td>";
					echo "<td><p><select name='ordering' class='select'><option value=''>Default</option>";
						$result = mysql_query("SELECT * FROM garagedoors ORDER BY ordering ASC");
						while($row = mysql_fetch_array($result,1)){
							$ordering[$row['ordering']] = $row['name'];
						}
						for ($i=1; $i<101; $i++){
							echo "<option value='".$i."' ".($i==$special['ordering'] ? "selected" : "").">".($ordering[$row['ordering']]!='' ? $i.' - '.$ordering[$row['ordering']] : $i)."</option>";
						}
					echo "</select></p></td>";
				echo "</tr>";				
				echo "<tr>";
				echo "<td ><p>Show/Hide:</p></td>";
				echo "<td><select name='showhide' class='select'>";
				if($special['showhide'] == 0){
					echo "<option value='0' selected='selected'>Show</option><option value='1'>Hide</option>";
				}else{
					echo "<option value='0'>Show</option><option value='1' selected='selected'>Hide</option>";
				}
				echo "</select></td>";
				echo "</tr>";

				
				
				echo "<tr>";
					if ($_POST['id']!=""){
						echo "<td class='footer' align='left'><p><select name='confirm' class='select' style='width:50px'><option value='no'>No</option><option value='yes'>Yes</option></select><input type='submit' name='submit' value='Delete'/></td>";
					}else{
						echo "<td class='footer'>&nbsp;</td>";	
					}
					echo "<td style='border-top: 1px solid #3C6C46' align='right' class='footer'><p style='text-align:right'><input type='hidden' name='username' value='".$_POST['username']."'/><input type='hidden' name='password' value='".$_POST['password']."'/><input type='hidden' name='section' value='".$_POST['section']."'/><input type='hidden' name='id' value='".$_POST['id']."'/><input type='submit' name='submit' value='Save Special'/></p></td>";
				echo "</tr>";
			echo "</table>";
			

		}
	}
?>