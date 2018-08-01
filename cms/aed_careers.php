<?php 
	if ($section==''){
		$result = mysql_query("SELECT * FROM careers");
		echo "<td><p>Total Careers</p></td>";
		echo "<td><p>".number_format(mysql_num_rows($result))."</p></td>";
	}
	
	if ($section=='Add Career' || $section=='Edit/Delete Careers'){

		if ($_POST['submit']=="Delete" && $_POST['confirm']=="yes"){
			
			$query = "DELETE FROM careers WHERE career_id = '".$_POST['id']."'";
			$result = mysql_query($query);
			if (!mysql_error()){
				alert("<p>Career successfully deleted!</p>",true);	
				$_POST['id']="";
				$section='Edit/Delete Career';
			}else{
				$errors = true;
				alert("<p>There was an error attempting to delete this career. Error: 301</p>",true);
			}	
			
			
		}else if ($_POST['submit']=="Save Career"){
			//validate
			$errors = false;
			foreach($_POST AS $key=>$data){
				$_POST[$key] = trim($data);	
			}
			
			if ($_POST['title']==""){
				$errors = true;
				alert("<p>Job Title is required.</p>",false);
			}
			if ($_POST['competition']==""){
				$errors = true;
				alert("<p>Competition number is required.</p>",false);	
			}
			
			if (!$errors){
				//images
				
				
				if (!$errors){
					
					$content = str_replace("'", "&rsquo;", stripslashes($_POST['content']));
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
		
					
					
					$query = "INSERT INTO careers (career_id, title, content, competition) VALUES ('".$_POST['id']."','".$_POST['title']."','".$content."','".$_POST['competition']."') ON DUPLICATE KEY UPDATE title='".$_POST['title']."',  content='".$content."', competition='".$_POST['competition']."'";
					
					$result = mysql_query($query);
					echo mysql_error();
					
					if (!mysql_error()){
						alert("<p>Career sucessfully saved!</p>",true);		
						$_POST['id']="";
					}
				}else{
					foreach($_POST AS $key=>$data){
						$career[$key] = $data;	
					
					}
				}	
			}else{
				foreach($_POST AS $key=>$data){
					$career[$key] = $data;	
				}
			}
		}
		
		if (($_POST['id']=="") && ($section=="Edit/Delete Careers")){
			//display table of articles
			echo "<table cellpadding='3' cellspacing='0' border='0' style='width:100%;' class='removepadding tablesorter stickyheader'>";		
			echo "<thead>";
			echo "<th style=' border-bottom: 1px solid #3C6C46;'><p><b>Title</b></p></th>";
			echo "<th style='border-bottom: 1px solid #3C6C46;'><p><b>Competition</b></p></th>";		
			echo "<th style='border-bottom: 1px solid #3C6C46;' width='50px' align='right'>&nbsp;</th>";
			echo "</thead>";
			
			$result = mysql_query("SELECT * FROM careers ORDER BY career_id DESC");
			$count = 0;
			while($career = mysql_fetch_array($result)){
				echo "<tr class='row".(($count%2)+1)."'>";
					echo "<td><p>".$career['title']."</p></td>";
					echo "<td><p>".$career['competition']."</p></td>";
					echo "<td><form action='' method='post' style='margin: 0px;'><input type='hidden' name='id' value='".$career['career_id']."'/><input type='hidden' name='username' value='".$_POST['username']."'/><input type='hidden' name='password' value='".$_POST['password']."'/><input type='hidden' name='section' value='".$_POST['section']."'/><input type='submit' name='submit' value='Edit'/></form></td>";
				echo "</tr>";
				$count++;
					
			}
		}else if ($_POST['id']!="" || $section=="Add Career"){
			//ID is set, show form
			
			if ($_POST['id'] && (!$errors)){
				$result = mysql_query("SELECT * FROM careers WHERE career_id = '".$_POST['id']."'");
				$career = mysql_fetch_array($result);
			}
						echo "<form action='' method='post' enctype='multipart/form-data'>";

			echo "<table cellpadding='3' cellspacing='0' border='0' width='100%' class='removepadding'>";
				echo "<tr>";
					echo "<td width='135px'><p>Career Title:</p></td>";
					echo "<td><input type='text' name='title' value='".@$career['title']."' class='input'/></td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td><p>Competition Number:</p></td>";
					echo "<td><input type='text' name='competition' value='".$career['competition']."' class='input' /></td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td colspan='2'>";
		
					$oFCKeditor = new FCKeditor('content') ;
					$oFCKeditor->BasePath	= '../fckeditor/';
					$oFCKeditor->Value = $career['content'];
					$oFCKeditor->Config["EditorAreaCSS"] = "../../css/global_stylesheet.css";
					$oFCKeditor->Create();
		
		
					echo "</td>";
				echo "</tr>";
				echo "<tr>";
					if ($_POST['id']!=""){
						echo "<td  class='footer' align='left'><p><select name='confirm' class='select' style='width: 50px;'><option value='no'>No</option><option value='yes'>Yes</option></select><input type='submit' name='submit' value='Delete'/></p></td>";
					}else{
						echo "<td class='footer'>&nbsp;</td>";	
					}
					echo "<td class='footer' align='right'><input type='hidden' name='username' value='".$_POST['username']."'/><input type='hidden' name='password' value='".$_POST['password']."'/><input type='hidden' name='section' value='".$_POST['section']."'/><input type='hidden' name='id' value='".$_POST['id']."'/><input type='submit' name='submit' value='Save Career'/></td>";
				echo "</tr>";
			echo "</table>";
			echo "</form>";
		}
	}
?>