<?php 
	if ($section==''){
		$result = mysql_query("SELECT * FROM faqs");
		echo "<td><p>Total FAQs</p></td>";
		echo "<td><p>".number_format(mysql_num_rows($result))."</p></td>";
	}
	if ($section=='Add FAQ' || $section=='Edit/Delete FAQs'){
		
		if ($_POST['submit']=="Delete" && $_POST['confirm']=="yes"){
			
			$result = mysql_query("DELETE FROM faqs WHERE faq_id = '".$_POST['id']."'");
			if (!mysql_error()){
				alert("<p>FAQ has been deleted.</p>",true);
				$_POST['id']='';
			}

		}else if ($_POST['submit']=="Save FAQ"){
			//validate
				foreach($_POST AS $key=>$data){
					$_POST[$key] = str_replace("'","&#39;",$data);
				}	
				if ($_POST['question']==''){
					$errors = true;
					alert('<p>Please enter a question</p>',false);
				}
				if ($_POST['answer']==''){
					$errors =true;
					alert('<p>Please enter an answer</p>',false);
				}


				$content = str_replace("'", "&rsquo;", stripslashes($_POST['answer']));
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

				
			if (!$errors){	
				$query = "INSERT INTO faqs (faq_id, question, answer, ordering,showhide) VALUES ('".$_POST['id']."','".$_POST['question']."','".$content."','".$_POST['ordering']."','".$_POST['showhide']."') ON DUPLICATE KEY UPDATE question='".$_POST['question']."', answer='".$content."', ordering='".$_POST['ordering']."', showhide='".$_POST['showhide']."'";

				$result = mysql_query($query);
				
				if (!mysql_error()){
					if($imagecrop){
						$_SESSION['jcrop_success'] = "<p>FAQ successfully saved!</p>"; 
						echo "<form action='' method='post' id='frm'>";
						echo "<input type='hidden' name='username' value='".$_POST['username']."'/>";
						echo "<input type='hidden' name='password' value='".$_POST['password']."'/>";
						echo "<input type='hidden' name='section' value='Crop Image'/>";
						echo "<script language='JavaScript'>";
							echo "document.getElementById('frm').submit();";
						echo "</script>";
						echo "</form>";
					}
					alert("<p>FAQ sucessfully saved!</p>",true);		
					$_POST['id']="";
				}else{
					alert("<p>There was an error saving this FAQ. ".mysql_error()."</p>",false);
				}
			}else{
				foreach($_POST AS $key=>$data){
					$depot[$key] = $data;					
				}
			}
		}		
		
		if (($_POST['id']=="") && ($section=="Edit/Delete FAQs")){
			//display table of articles
			echo "<table cellpadding='3' cellspacing='0' border='0' style='width:100%;' class='removepadding tablesorter stickytableheader'>";		
			echo "<thead>";
			echo "<th style='border-bottom: 1px solid #3C6C46;' align='left'><p><b>Question</b></p></th>";		
			echo "<th style='border-bottom: 1px solid #3C6C46;' width='50px' align='right' class='{sorter: false}'>&nbsp;</th>";
			echo "</thead>";
			
			$result = mysql_query("SELECT * FROM faqs ORDER BY ordering ASC");
			$count = 0;
			while($faq = mysql_fetch_array($result,1)){
				echo "<tr class='row".(($count%2)+1)."'>";
					echo "<td><p>".$faq['question']."</p></td>";
					echo "<td style='text-align:right'><form action='' method='post' style='margin: 0px;'><input type='hidden' name='id' value='".$faq['faq_id']."'/><input type='hidden' name='username' value='".$_POST['username']."'/><input type='hidden' name='password' value='".$_POST['password']."'/><input type='hidden' name='section' value='".$_POST['section']."'/><input type='submit' name='submit' value='Edit'/></form></td>";
				echo "</tr>";
				$count++;
					
			}
		}else if ($_POST['id']!="" || $section=="Add FAQ"){
			if ($_POST['id'] && (!$errors)){
				$result = mysql_query("SELECT * FROM faqs WHERE faq_id = '".$_POST['id']."'");
				$faq = mysql_fetch_array($result);
			}

			echo "<form action='' method='post' enctype='multipart/form-data'>";
			
			echo "<table cellpadding='3' cellspacing='0' border='0' width='100%' class='removepadding'>";
				echo "<tr>";
					echo "<td width='150px'><p>Question:</p></td>";
					echo "<td><p><input type='text' name='question' value='".@$faq['question']."' class='input'/></p></td>";
				echo "</tr>";
			
				echo "<tr>";
					echo "<td><p>Ordering:</p></td>";
					echo "<td><p><select name='ordering' class='select'><option value='101'>Default</option>";
						
						for($i = 1; $i<101; $i++){
							echo "<option value='".$i."' ".($i==$faq['ordering'] ? "selected" : "").">".$i."</option>";
						}
					
					echo "</select></p></td>";
				echo "</tr>";
				echo "<tr>";	
					echo "<td><p>Show/Hide:</p></td>";
					echo "<td><p><select name='showhide' class='select'><option value='0'>Show</option><option value='1' ".($faq['showhide']=='1' ? "selected" : "").">Hide</option></select></p></td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td colspan='2'>";
		
					$oFCKeditor = new FCKeditor('answer') ;
					$oFCKeditor->BasePath	= '../fckeditor/';
					$oFCKeditor->Value = $faq['answer'];
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
					echo "<td style='border-top: 1px solid #3C6C46' align='right' class='footer'><p style='text-align:right'><input type='hidden' name='username' value='".$_POST['username']."'/><input type='hidden' name='password' value='".$_POST['password']."'/><input type='hidden' name='section' value='".$_POST['section']."'/><input type='hidden' name='id' value='".$_POST['id']."'/><input type='submit' name='submit' value='Save FAQ'/></p></td>";
				echo "</tr>";
			echo "</table>";
			echo "</form>";

		}
	}
?>