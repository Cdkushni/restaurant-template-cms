<?php 
	
	if ($section=='Add Community Photo' || $section=='Edit/Delete Community Photos'){
		
		if ($_POST['submit']=="Delete" && $_POST['confirm']=="yes"){
			$result = mysql_query("SELECT * FROM photos WHERE photo_id = '".$_POST['id']."'");
			while($row = mysql_fetch_array($result,1)){
				if (is_file('../images/communities/'.$row['filename'])){
					unlink('../images/communities/'.$row['filename']);
					unlink('../images/communities/full/'.$row['filename']);
				}
			}
			$result = mysql_query("DELETE FROM photos WHERE photo_id = '".$_POST['id']."'");
			if (!mysql_error()){
				alert("<p>Photo has been deleted.</p>",true);
				$_POST['id']='';
			}
		}else if ($_POST['submit']=="Save Photo"){
			//validate
				$newimage = $_POST['old_filename'];
				if ($_FILES['filename']['name']!=''){
					require_once('../includes/classes/imageman.class.php');
					$img = new Imageman();
					$img->load($_FILES['filename']['tmp_name']);
					$imgerror = $img->valid_image();
						//valid image, show crop tools
						$newimage = time().'.jpg';
						$img->save('../images/communities/full/',$newimage,'jpg');
						//move_uploaded_file($_FILES['filename']['tmp_name'],'../images/slideshow/full/'.$newimage);
						//do a smart crop for the time being, and redirect user to the crop
						require_once('../includes/classes/imageman.class.php');
						$img = new Imageman();
						if (!$img->load('../images/communities/full/'.$newimage)){
							$errors = true;
							alert('<p>Couldnt save image.</p>', false);
						}else{

							$img->smartCrop(600,400);
							$img->save('../images/communities/',$newimage,'jpg');

							unset($img);
							$_SESSION['jcrop'][0]['filename'] = $newimage;
							$_SESSION['jcrop'][0]['img_path'] = '../images/communities/full/';
							$_SESSION['jcrop'][0]['target_path'] = '../images/communities/';
							$_SESSION['jcrop'][0]['from_section'] = $_POST['section'];
							$_SESSION['jcrop'][0]['target_width'] = 600;
							$_SESSION['jcrop'][0]['target_height'] = 400;

							$imagecrop = true;
						}					
				}
				
			if (!$errors){	
				$query = "INSERT INTO photos (photo_id, community_id, filename, caption, ordering) VALUES ('".$_POST['id']."','".$_POST['community_id']."','".$newimage."','".$_POST['caption']."','".$_POST['ordering']."') ON DUPLICATE KEY UPDATE community_id = '".$_POST['community_id']."', filename='".$newimage."', caption = '".$_POST['caption']."', ordering='".$_POST['ordering']."'";

				$result = mysql_query($query);
				
				if (!mysql_error()){
					if($imagecrop){
						$_SESSION['jcrop_success'] = "<p>Photo successfully saved!</p>"; 
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
					alert("<p>There was an error saving this photo. ".mysql_error()."</p>",false);
				}
			}else{
				foreach($_POST AS $key=>$data){
					$photo[$key] = $data;					
				}
			}
		}		
		
		if (($_POST['id']=="") && ($section=="Edit/Delete Community Photos")){
			//display table of articles
			echo "<table cellpadding='3' cellspacing='0' border='0' style='width:100%;' class='removepadding tablesorter stickytableheader'>";		
			echo "<thead>";
			echo "<th style='border-bottom: 1px solid #3C6C46;' align='left' width='40px' align='left'><p><b></b></p></th>";
			echo "<th style='border-bottom: 1px solid #3C6C46;' align='left'><p><b>Caption</b></p></th>";
			echo "<th style='border-bottom: 1px solid #3C6C46;' align='left'><p><b>Community</b></p></th>";
			echo "<th style='border-bottom: 1px solid #3C6C46;' width='50px' align='right' class='{sorter: false}'>&nbsp;</th>";
			echo "</thead>";
			
			$result = mysql_query("SELECT photos.*, communities.name, communities.city FROM photos LEFT JOIN communities ON communities.community_id = photos.community_id WHERE photos.property_id IS NULL ORDER BY photos.community_id ASC, photos.ordering ASC");
			$count = 0;
			while($photo = mysql_fetch_array($result,1)){
				echo "<tr class='row".(($count%2)+1)."'>";
					echo "<td><p><a href='../images/communities/".$photo['filename']."' target='_blank' rel='prettyPhoto'>".renderGravatar('../images/communities/'.$photo['filename'])."</a></p></td>";
					echo "<td><p>".$photo['caption']."</p></td>";
					echo "<td><p>".$photo['name']."<br /><small>".$photo['city']."</small></p></td>";
					echo "<td style='text-align:right'><form action='' method='post' style='margin: 0px;'><input type='hidden' name='id' value='".$photo['photo_id']."'/><input type='hidden' name='username' value='".$_POST['username']."'/><input type='hidden' name='password' value='".$_POST['password']."'/><input type='hidden' name='section' value='".$_POST['section']."'/><input type='submit' name='submit' value='Edit'/></form></td>";
				echo "</tr>";
				$count++;
					
			}
		}else if ($_POST['id']!="" || $section=="Add Community Photo"){
			if ($_POST['id'] && (!$errors)){
				$result = mysql_query("SELECT * FROM photos WHERE photo_id = '".$_POST['id']."'");
				$photo = mysql_fetch_array($result);
			}

			echo "<form action='' method='post' enctype='multipart/form-data'>";
			
			echo "<table cellpadding='3' cellspacing='0' border='0' width='100%' class='removepadding'>";
				echo "<tr>";
					echo "<td width='150px'><p>Community:</p></td>";
					echo "<td><p><select name='community_id' class='select'>";
						$result = mysql_query("SELECT * FROM communities ORDER BY ordering ASC");
						while($row = mysql_fetch_array($result,1)){
							echo "<option value='".$row['community_id']."' ".($row['community_id'] == $photo['community_id'] ? "selected" : "").">".$row['name']."</option>";
						}
					echo "</select></p></td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td><p>Caption:</p></td>";
					echo "<td><p><input type='text' class='input' name='caption' value='".$photo['caption']."'/></p></td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td><p>".(is_file('../images/communities/'.$photo['filename']) ? "<a href='../images/communities/".$photo['filename']."' rel='prettyPhoto'>Image:</a>" : "Image:")."</p></td>";
					echo "<td><p><input type='file' name='filename' class='input'/><input type='hidden' name='old_filename' value='".$photo['filename']."'/></p></td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td><p>Ordering:</p></td>";
					echo "<td><p><select name='ordering' class='select'><option value='101'>Default</option>";
						$result = mysql_query("SELECT * FROM photos ORDER BY ordering ASC");
						while($photoorder = mysql_fetch_array($result,1)){
							$order[$photoorder['ordering']] = $photoorder['title'];
						}
						for ($i=1; $i<101; $i++){
							echo "<option value='".$i."' ".($i==$photo['ordering'] ? "selected" : "").">".$i." ".(isset($order[$i]) ? " - (".$order[$i].")" : "")."</option>";
						}
					echo "</select></p></td>";
				echo "</tr>";
				
			
				echo "<tr>";
					if ($_POST['id']!=""){
						echo "<td class='footer' align='left'><p><select name='confirm' class='select' style='width:50px'><option value='no'>No</option><option value='yes'>Yes</option></select><input type='submit' name='submit' value='Delete'/></td>";
					}else{
						echo "<td class='footer'>&nbsp;</td>";	
					}
					echo "<td style='border-top: 1px solid #3C6C46' align='right' class='footer'><p style='text-align:right'><input type='hidden' name='username' value='".$_POST['username']."'/><input type='hidden' name='password' value='".$_POST['password']."'/><input type='hidden' name='section' value='".$_POST['section']."'/><input type='hidden' name='id' value='".$_POST['id']."'/><input type='submit' name='submit' value='Save Photo'/></p></td>";
				echo "</tr>";
			echo "</table>";
			echo "</form>";

		}
	}
?>