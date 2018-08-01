<?php 
	if ($section==''){
		$result = mysql_query("SELECT * FROM threads WHERE deleted <> '1'");
		echo "<td><p>Total Forum Threads</p></td>";
		echo "<td><p>".number_format(mysql_num_rows($result))."</p></td>";
	}
	if ($section=='Manage Threads'){
		if (count($_POST)){
			if ($_POST['submit']=='Delete Thread'){
				if ($_POST['confirm']=='yes'){
					//deleted.
					$result = mysql_query("UPDATE threads SET deleted = '1' WHERE thread_id = '".$_POST['id']."'");
					$result = mysql_query("UPDATE posts SET deleted = '1' WHERE thread_id = '".$_POST['id']."'");

					if (!mysql_error()){
						alert('<p>Thread and all of its posts have been successfully deleted.</p>',true);
						$_POST['id']='';
					}else{
						alert('<p>There was a problem deleting this thread. Please try again later.',false);
					}
				}else{
					alert('<P>Please confirm the delete request using the dropdown provided.',false);
				}
			}else if ($_POST['submit']=='Save Thread'){
				$result = mysql_query("UPDATE threads SET name='".$_POST['name']."', url_name = '".create_pagename($_POST['name'])."', sticky='".$_POST['sticky']."', locked='".$_POST['locked']."' WHERE thread_id = '".$_POST['id']."'");
				if (!mysql_error()){
					alert('<p>Thread has been successfully updated.</p>',true);
					$_POST['id']='';
				}else{
					alert('<p>There was a problem saving this thread.. Please try again later',false);
				}
			}
		}
		if ($_POST['id']!=''){
			$result = mysql_query("SELECT * FROM threads WHERE thread_id = '".$_POST['id']."'");
			$thread = mysql_fetch_array($result,1);
			echo "<form action='' method='post'>";

			echo "<table style='border-collapse: collapse;'>";
				echo "<tr>";
					echo "<td width='135px'><p>Thread Name: </p></td>";
					echo "<td><p><input type='text' name='name' class='input' value='".$thread['name']."'/></p></td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td><p>Sticky Thread: </p></td>";
					echo "<td><p><input type='checkbox' name='sticky' value='1' ".($thread['sticky']=='1' ? "checked" : "")."/> <sup class='help' title='<span>Sticky Thread</span><p>These are threads that will always be listed at the top of the forum with special formatting. These are normally used for special posting instructions related to the specific forum'>?</sup></p></td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td><p>Locked Thread: </p></td>";
					echo "<td><p><input type='checkbox' name='locked' value='1' ".($thread['locked']=='1' ? "checked" : "")."/> <sup class='help' title='<span>Locked Thread</span><p>These threads will still be visible on the site, however no new posts will be allowed to be posted to them.'>?</sup></p></td>";
				echo "</tr>";

			echo "</table>";
			

				echo "<input type='hidden' name='section' value='".$section."'/>";
				echo "<input type='hidden' name='username' value='".$username."'/>";
				echo "<input type='hidden' name='password' value='".$password."'/>";
				echo "<input type='hidden' name='id' value='".$_POST['id']."'/>";
				echo "<table width='100%' style='border-collapse:collapse'>";
					echo "</tr>";
						echo "<td align='left' class='footer'><p><select name='confirm' class='select' style='width: 55px;'><option value='no'>No</option><option value='yes'>Yes</option></select><input type='submit' name='submit' value='Delete Thread' /></td>";
						echo "<td align='right' class='footer'><p><input type='submit' name='submit' value='Save Thread' class='f_right button'/></p></td>";
					echo "</tr>";
				echo "</table>";
			echo "</form>";
		}else{
				
			echo "<table width='100%' style='border-collapse: collapse;' class='tablesorter stickytableheader'>";
				echo "<thead>";
					echo "<th style='border-bottom: 1px solid #ccc;' class='{sorter: false}' width='45px'><p><b></b></p></th>";
					echo "<th style='border-bottom: 1px solid #ccc;'><p><b>Thread</b></p></th>";
					echo "<th style='border-bottom: 1px solid #ccc;'><p><b>Forum</b></p></th>";
					echo "<th style='border-bottom: 1px solid #ccc;'><p><b>Posts</b></p></th>";
					echo "<th style='border-bottom: 1px solid #ccc;'><p><b>Last Action</b></p></th>";
					echo "<th style='border-bottom: 1px solid #ccc; vertical-align: top;' class='{sorter:false}' width='30px' align='right'><p></p></th>";
				echo "</thead>";

				$query = "SELECT threads.*, forums.name AS forum_name, p.last_action, p.num_posts FROM threads LEFT JOIN forums ON forums.forum_id = threads.forum_id LEFT JOIN (SELECT date_posted AS last_action, thread_id, count(post_id) AS num_posts FROM posts GROUP BY thread_id ORDER BY date_posted DESC) p ON p.thread_id = threads.thread_id WHERE deleted <> '1' ORDER BY name ASC";
				$result = mysql_query($query);

				echo "<tbody>";
				$count = 0;
				while($row = mysql_fetch_array($result)){
					echo "<tr class='row".(($count%2)+1)."'>";
						echo "<td><p>".($row['locked']=='1' ? "<img src='../cms/images/icon_locked.png' style='padding: 3px'/> " : "").($row['sticky']=='1' ? "<img  style='padding: 3px' src='../cms/images/icon_sticky.png' class='help' title='<span>Sticky Thread</span><p>This thread is set to sticky. It will remain at the top of the of the forum.</p>'/> " : "")."</p></td>";
						echo "<td><p>".$row['name']."</p></td>";
						echo "<td><p>".$row['forum_name']."</p></td>";
						echo "<td><p>".number_format($row['num_posts'],0)."</p></td>";
						echo "<td><p>".($row['num_posts']>0 ? date('F d, Y H:i',strtotime($row['last_action'])) : '')."</p></td>";
						echo "<td style='vertical-align: top' width='30px'><p>
								<form action='' method='post'>
									<input type='hidden' name='section' value='".$section."'/>
									<input type='hidden' name='username' value='".$username."'/>
									<input type='hidden' name='password' value='".$password."'/>
									<input type='hidden' name='id' value='".$row['thread_id']."'/>
									<input type='submit' name='submit' value='Edit'/>
								</form></p>
								</td>";
					echo "</tr>";
					$count++;
				}
				if (!mysql_num_rows($result)){
					echo "<tr>";
					echo "<td colspan='5'><p><small>No threads found.</small></p></td>";
					echo "</tr>";
				}
				echo "</tbody>";
			echo "</table>";
		}
	}
?>