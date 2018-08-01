<?php 
	if ($section==''){
		$result = mysql_query("SELECT * FROM contests WHERE showhide = '0'");
		echo "<td><p>Total Active Contests</p></td>";
		echo "<td><p>".number_format(mysql_num_rows($result))."</p></td>";
	}
	if ($section=='Add Contest' || $section=='Edit/Delete Contests'){
		
		if ($_POST['submit']=="Delete" && $_POST['confirm']=="yes"){
			$result = mysql_query("SELECT * FROM contest_entries WHERE contest_id = '".$_POST['id']."'");
			while($row = mysql_fetch_array($result,1)){
				if (is_file('../images/contests/'.$row['filename'])){
					unlink('../images/contests/'.$row['filename']);
				}
			}
			$result = mysql_query("DELETE FROM contest_entries WHERE contest_id = '".$_POST['id']."'");
			$result = mysql_query("DELETE FROM contests WHERE contest_id = '".$_POST['id']."'");
			if (!mysql_error()){
				alert("<p>Contest has been deleted.</p>",true);
				$_POST['id']='';
			}
		}else if ($_POST['submit']=="Save Contest"){
			//validate
					
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

				$query = "INSERT INTO contests (contest_id, contest_name, deadline, description, showhide) VALUES ('".$_POST['id']."','".$_POST['contest_name']."','".date('Y-m-d',strtotime($_POST['deadline']))."','".$content."','".$_POST['showhide']."') ON DUPLICATE KEY UPDATE contest_name = '".$_POST['contest_name']."', deadline = '".date('Y-m-d',strtotime($_POST['deadline']))."',description = '".$content."', showhide='".$_POST['showhide']."', winner_id = '".$_POST['winner']."'";

							$result = mysql_query($query);
				
				
				if (!mysql_error()){
					alert("<p>Contest sucessfully saved!</p>",true);		
					$_POST['id']="";
				}else{
					alert("<p>There was an error saving this contest. ".mysql_error()."</p>",false);
				}
			}else{
				foreach($_POST AS $key=>$data){
					$contest[$key] = $data;					
				}
			}
		}		
		
		if (($_POST['id']=="") && ($section=="Edit/Delete Contests")){
			//display table of articles
			echo "<table cellpadding='3' cellspacing='0' border='0' style='width:100%;' class='removepadding tablesorter stickytableheader'>";		
			echo "<thead>";
			echo "<th style='border-bottom: 1px solid #3C6C46;' width='40px' align='left'><p><b>Active</b></p></th>";
			echo "<th style='border-bottom: 1px solid #3C6C46;' align='left'><p><b>Contest</b></p></th>";		
			echo "<th style='border-bottom: 1px solid #3C6C46;' align='right'><p><b>Deadline</b></p></th>";
			echo "<th style='border-bottom: 1px solid #3C6C46;  align='right'><p><b>Entries</b></p></th>";
			echo "<th style='border-bottom: 1px solid #3C6C46;' width='50px' align='right' class='{sorter: false}'>&nbsp;</th>";
			echo "</thead>";
			
			$result = mysql_query("SELECT contests.*, ne.num_entries FROM contests LEFT JOIN (SELECT count(contest_id) AS num_entries, contest_id FROM contest_entries GROUP BY contest_id) ne ON ne.contest_id = contests.contest_id ORDER BY contest_name ASC");
			echo mysql_error();
			$count = 0;
			while($contest = mysql_fetch_array($result)){
				echo "<tr class='row".(($count%2)+1)."'>";
					echo "<td><p>".($contest['showhide']=='0' ? "<img src='../cms/images/icon_check.png'/>" : "")."</p></td>";
					echo "<td><p>".$contest['contest_name']."</p></td>";
					echo "<td><p>".date('F d, Y',strtotime($contest['deadline']))."</p></td>";
					echo "<td><p>".number_format($contest['num_entries'],0)."</p></td>";
					echo "<td style='text-align:right'><form action='' method='post' style='margin: 0px;'><input type='hidden' name='id' value='".$contest['contest_id']."'/><input type='hidden' name='username' value='".$_POST['username']."'/><input type='hidden' name='password' value='".$_POST['password']."'/><input type='hidden' name='section' value='".$_POST['section']."'/><input type='submit' name='submit' value='Edit'/></form></td>";
				echo "</tr>";
				$count++;
					
			}
		}else if ($_POST['id']!="" || $section=="Add Contest"){
			important('<p><b>Deleting Contests: </b> Deleting a contest will delete all photos associated with that contest. Proceed with caution.');
			if ($_POST['id'] && (!$errors)){
				$result = mysql_query("SELECT * FROM contests WHERE contest_id = '".$_POST['id']."'");
				$contest = mysql_fetch_array($result);
			}
			echo "<form action='' method='post' enctype='multipart/form-data'>";
			if ($_POST['id']!=''){
				echo "<div class='tabs'>";
					echo "<ul>";
						echo "<li><a href='#info'>Contest Information</a></li>";
						echo "<li><a href='#entries'>Entries</a></li>";
					echo "</ul>";
					echo "<div id='info'>";

			}
			echo "<table cellpadding='3' cellspacing='0' border='0' width='100%' class='removepadding'>";
				echo "<tr>";
					echo "<td width='150px'><p>Contest Name: *</p></td>";
					echo "<td><p><input type='text' name='contest_name' value='".@$contest['contest_name']."' class='input'/></p></td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td><p>Deadline: *</p></td>";
					echo "<td><p><input type='text' name='deadline' value='".$contest['deadline']."' class='input datepicker' /></p></td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td ><p>Show/Hide:</p></td>";
				echo "<td><select name='showhide' class='select'>";
				if($contest['showhide'] == 0){
					echo "<option value='0' selected='selected'>Show</option><option value='1'>Hide</option>";
				}else{
					echo "<option value='0'>Show</option><option value='1' selected='selected'>Hide</option>";
				}
				echo "</select></td>";
				echo "</tr>";
				
				echo "<tr>";
					echo "<td colspan='2'>";
					echo "<h2>Contest Description</h2>";
						
						$content = str_replace ('src="images', 'src="/images', $content);
						$content = str_replace ("src='images", "src='/images", $content);
						
						$oFCKeditor = new FCKeditor('FCKeditor1');
						$oFCKeditor->BasePath	= '../fckeditor/';
						$oFCKeditor->Value = $contest['description'];
						$oFCKeditor->Config["EditorAreaCSS"] = "../../css/global_stylesheet.css";
						$oFCKeditor->Create();

					echo "</td>";			
				echo "</tr>";
				echo "</table>";
				if ($_POST['id']!=''){
					echo "</div>";
					echo "<div id='entries'>";
					echo "<h2>Contest Entries<br /><small>You will be able to choose a winner once the contest closes. Winners will be displayed on the contest page.</small></h2>";
					$result = mysql_query("SELECT * FROM contest_entries LEFT JOIN profiles ON profiles.account_id = contest_entries.account_id WHERE contest_id = '".$_POST['id']."'");
					if (mysql_num_rows($result)){
						echo "<ul style='list-style: none; margin-bottom: 20px;' class='clearFix contest_entries'>";
						while($entry = mysql_fetch_array($result)){
							require_once('../includes/classes/imageman.class.php');
							$imageman = new imageman();
							$resized = $imageman->dynamicScaleToFill(75,75,'../images/contests/'.$entry['filename']);

							if (is_file('../images/contests/'.$entry['filename'])){
								echo "<li style='float: left; margin: 10px;'><span><a href='../images/contests/".$entry['filename']."' target='_blank' rel='prettyPhoto[]' title='Submitted By: ".$entry['first_name']." ".$entry['last_name']."</br />".$entry['caption']."'><img src='../images/contests/".$entry['filename']."' width='".$resized['w']."' height='".$resized['h']."' style='left: -".($resized['offset_w']/2)."px; top: -".($resized['offset_h']/2)."px'/></a></span>".(strtotime($contest['deadline'])<=time() ? "<label>Winner <input type='radio' name='winner' value='".$entry['account_id']."' ".($entry['account_id']==$contest['winner_id'] ? "checked" : "")." /></label>" : "")."<br /><small style='font-size: 10px'><a href='javascript:' onclick='gotoProfile(".$entry['account_id'].")' style='font-size: 10px'>(View Account)</a></small></li>";
							}

						}
						echo "</ul>";
					}else{
						echo "<small>No entries have been received at this time.</small>";
					}
					echo "</div>";
				}
				echo "<table width='100%' style='border-collapse: collapse;'>";
				echo "<tr>";
					if ($_POST['id']!=""){
						echo "<td class='footer' align='left'><p><select name='confirm' class='select' style='width:50px'><option value='no'>No</option><option value='yes'>Yes</option></select><input type='submit' name='submit' value='Delete'/></td>";
					}else{
						echo "<td class='footer'>&nbsp;</td>";	
					}
					echo "<td style='border-top: 1px solid #3C6C46' align='right' class='footer'><p style='text-align:right'><input type='hidden' name='username' value='".$_POST['username']."'/><input type='hidden' name='password' value='".$_POST['password']."'/><input type='hidden' name='section' value='".$_POST['section']."'/><input type='hidden' name='id' value='".$_POST['id']."'/><input type='submit' name='submit' value='Save Contest'/></p></td>";
				echo "</tr>";
			echo "</table>";
			echo "</form>";

			echo "<script type='text/javascript'>";
			?>
				function gotoProfile(id){
					$("#accountid").val(id);
					$("#accountform").submit();

				}
			<?php
			echo "</script>";
			echo "<form id='accountform' method='post' action=''>";

			echo "<input type='hidden' name='username' value='".$_POST['username']."'/><input type='hidden' name='password' value='".$_POST['password']."'/><input type='hidden' name='section' value='User Accounts'/><input type='hidden' name='id' id='accountid' value=''/>";
			echo "</form>";
		}
	}
?>