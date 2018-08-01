<?php 
	if ($section==''){
		$result = mysql_query("SELECT * FROM communities");
		echo "<td><p>Total Communities</p></td>";
		echo "<td><p>".number_format(mysql_num_rows($result))."</p></td>";
	}
	if ($section=='Add Community' || $section=='Edit/Delete Communities'){
		
		if ($_POST['submit']=="Delete" && $_POST['confirm']=="yes"){
			$result = mysql_query("SELECT * FROM photos WHERE community_id = '".$_POST['id']."'");
			while($row = mysql_fetch_array($result,1)){
				if (is_file('../images/communities/'.$row['filename']));
				unlink('../images/communities/'.$row['filename']);
			}

			$query = "DELETE FROM communities WHERE community_id = '".$_POST['id']."'";
			$result = mysql_query($query);
			if (!mysql_error()){
				alert("<p>Communitiy successfully deleted!</p>",true);	
				$_POST['id']="";
				$section='Edit/Delete Communities';
			}else{
				$errors = true;
				alert("<p>There was an error attempting to delete this community. Error: 301</p>",true);
			}	
			
			
		}else if ($_POST['submit']=="Save Community"){
			//validate
			$errors = false;
			foreach($_POST AS $key=>$data){
				$_POST[$key] = str_replace("'","&#39",trim($data));	
			}
			
			//Validation

				
					
			if (!$errors){
				//go!

				$content = str_replace("'", "&rsquo;", stripslashes($_POST['FCKeditor1']));
				$content = str_replace("<p>&nbsp;</p>", "", $content);
				
				$content = str_replace('border-top-color: rgb(211, 211, 211);', '', $content);
				$content = str_replace('border-right-color: rgb(211, 211, 211);', '', $content);
				$content = str_replace('border-bottom-color: rgb(211, 211, 211);', '', $content);
				$content = str_replace('border-left-color: rgb(211, 211, 211);', '', $content);
				$content = str_replace('border-top-width: 1px;', '', $content);
				$content = str_replace('border-right-width: 1px;', '', $content);
				$content = str_replace('border-bottom-width: 1px;', '', $content);
				$content = str_replace('border-left-width: 1px;', '', $content);
				$content = str_replace('border-top-style: dotted;', '', $content);
				$content = str_replace('border-right-style: dotted;', '', $content);
				$content = str_replace('border-bottom-style: dotted;', '', $content);
				$content = str_replace('border-left-style: dotted;', '', $content);
				$content = str_replace('color: rgb(82, 119, 128); text-decoration: underline;', '', $content);

				$query = "INSERT INTO communities (community_id, name,content, city, province, ordering) VALUES ('".$_POST['id']."','".$_POST['name']."','".$content."','".$_POST['city']."','".$_POST['province']."','".$_POST['ordering']."') ON DUPLICATE KEY UPDATE name='".$_POST['name']."',content = '".$content."',city='".$_POST['city']."',province='".$_POST['province']."',ordering='".$_POST['ordering']."'";

				$result = mysql_query($query);
				
				
				
				if (!mysql_error()){
					alert("<p>Community sucessfully saved!</p>",true);		
					$_POST['id']="";
				}else{
					alert("<p>There was an error saving this community. ".mysql_error()."</p>",false);
					
				}
				
			}else{
				foreach($_POST AS $key=>$data){
					$community[$key] = $data;	
				
				}
			}
				
				
			
		}
		
		
		if (($_POST['id']=="") && ($section=="Edit/Delete Communities")){
			//display table of articles
			echo "<table cellpadding='3' cellspacing='0' border='0' style='width:100%;' class='removepadding tablesorter stickytableheader'>";		
			echo "<thead>";
			echo "<th style='border-bottom: 1px solid #3C6C46;' width='80px' align='left' class='{sorter: false}'></p><b></b></p></th>";
			echo "<th style='border-bottom: 1px solid #3C6C46;' align='left'><p><b>Name</b></p></th>";		
			echo "<th style='border-bottom: 1px solid #3C6C46;' align='right'><p><b>City</b></p></th>";

			echo "<th style='border-bottom: 1px solid #3C6C46;' width='50px' align='right' class='{sorter: false}'>&nbsp;</th>";
			echo "</thead>";
			
			$result = mysql_query("SELECT communities.*, img.filename FROM communities LEFT JOIN (SELECT filename, community_id FROM photos GROUP BY community_id ORDER BY ordering ASC ) img ON img.community_id = communities.community_id ORDER BY communities.community_id DESC");

			$count = 0;
			while($community = mysql_fetch_array($result)){
				echo "<tr class='row".(($count%2)+1)."'>";
					echo "<td width='80px' align='left'>".renderGravatar((is_file('../images/communities/'.$community['filename']) ? "../images/communities/".$community['filename'] : ""))."</td>";
					echo "<td><p>".$community['name']."</p></td>";
					echo "<td><p>".$community['city']."</p></td>";

					echo "<td style='text-align:right'><form action='' method='post' style='margin: 0px;'><input type='hidden' name='id' value='".$community['community_id']."'/><input type='hidden' name='username' value='".$_POST['username']."'/><input type='hidden' name='password' value='".$_POST['password']."'/><input type='hidden' name='section' value='".$_POST['section']."'/><input type='submit' name='submit' value='Edit'/></form></td>";
				echo "</tr>";
				$count++;
			}
		}else if ($_POST['id']!="" || $section=="Add Community"){
			
			if ($_POST['id'] && (!$errors)){
				$result = mysql_query("SELECT * FROM communities WHERE community_id = '".$_POST['id']."'");
				$community = mysql_fetch_array($result);
			}
			echo "<form action='' method='post' enctype='multipart/form-data'>";

			echo "<table cellpadding='3' cellspacing='0' border='0' width='100%' class='removepadding'>";
				
				echo "<tr>";
					echo "<td width='150px'><p>Name:</p></td>";
					echo "<td><p><input type='text' name='name' value='".@$community['name']."' class='input'/></p></td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td><p>City: </p></td>";
					echo "<td><p><input type='text' name='city' value='".@$community['city']."' class='input'/></p></td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td><p>Province: </p></td>";
					echo "<td><p><input type='text' name='province' value='".$community['province']."' class='input'/></p></td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td><p>Ordering:</p></td>";
					echo "<td><p><select name='ordering' class='select'><option value=''>Default</option>";
						$result = mysql_query("SELECT * FROM communities ORDER BY ordering ASC");
						while($row = mysql_fetch_array($result,1)){
							$ordering[$row['ordering']] = $row['community'];
						}
						for ($i=1; $i<101; $i++){
							echo "<option value='".$i."' ".($i==$community['ordering'] ? "selected" : "").">".($ordering[$row['ordering']]!='' ? $i.' - '.$ordering[$row['ordering']] : $i)."</option>";
						}
					echo "</select></p></td>";
				echo "</tr>";				
				
				echo "<tr>";

				echo "</tr>";
				
				echo "<tr>";
					echo "<td colspan='2'>";
					echo "<h2>Description:</h2>";
						
						$content = str_replace ('src="images', 'src="/images', $content);
						$content = str_replace ("src='images", "src='/images", $content);

						$oFCKeditor = new FCKeditor('FCKeditor1');
						$oFCKeditor->BasePath	= '../fckeditor/';
						$oFCKeditor->Value = $community['content'];
						$oFCKeditor->Config["EditorAreaCSS"] = "../../css/global_stylesheet.css";
						$oFCKeditor->Create();

					echo "</td>";			
				echo "</tr>";
				
				
				echo "<tr>";
					if ($_POST['id']!=""){
						echo "<td class='footer' align='left'><p><select name='confirm' class='select' style='width:50px'><option value='no'>No</option><option value='yes'>Yes</option></select><input type='submit' name='submit' value='Delete'/></td>";
					}else{
						echo "<td class='footer'>&nbsp;</td>";	
					}
					echo "<td style='border-top: 1px solid #3C6C46' align='right' class='footer'><p style='text-align:right'><input type='hidden' name='username' value='".$_POST['username']."'/><input type='hidden' name='password' value='".$_POST['password']."'/><input type='hidden' name='section' value='".$_POST['section']."'/><input type='hidden' name='id' value='".$_POST['id']."'/><input type='submit' name='submit' value='Save Community'/></p></td>";
				echo "</tr>";
			echo "</table>";

		}
	}
?>