<?php 
	if ($section==''){
		$result = mysql_query("SELECT * FROM post_reports WHERE ignored <> '1' AND post_id IN (SELECT post_id FROM posts)");
		echo "<td><p>Total Post Reports</p></td>";
		echo "<td><p>".(number_format(mysql_num_rows($result),0)>0 ? "<span style='color:#ea2020;'><b>".number_format(mysql_num_rows($result))."</span>" : 0)."</p></td>";
	}
	
	if ($section=='View Reports'){
		if (count($_POST)){
			if ($_POST['submit']=='Delete Post'){
				if ($_POST['confirm']=='yes'){
					//deleted.
					$result = mysql_query("UPDATE posts SET deleted = '1' WHERE post_id = '".$_POST['id']."'");
					if (!mysql_error()){
						alert('<p>Post has been successfully deleted.</p>',true);
						$_POST['id']='';
					}else{
						alert('<p>There was a problem deleting this post. Please try again later',false);
					}
				}else{
					alert('<P>Please confirm the delete request using the dropdown provided.',false);
				}
			}else if ($_POST['submit']=='Ignore Reports'){
				$result = mysql_query("UPDATE post_reports SET ignored = '1' WHERE post_id = '".$_POST['id']."'");
				if (!mysql_error()){
					alert('<p>Post reports have been successfully ignored.</p>',true);
					$_POST['id']='';
				}else{
					alert('<p>There was a problem ignoring these post reports. Please try again later',false);
				}
			}
		}
		if ($_POST['id']!=''){

			$result = mysql_query("SELECT * FROM posts LEFT JOIN profiles ON profiles.account_id = posts.account_id WHERE post_id = '".$_POST['id']."'");
			$post = mysql_fetch_array($result,1);

			echo "<h2>".$post['first_name']." ".$post['last_name']."<br /><small>Posted On: ".date('F d, Y H:i',strtotime($post['date_posted']))."</small></h2>";
			echo "<p><i>".$post['content']."</i></p>";
			echo "<hr />";
			echo "<h2>Reports</h2>";
			
			$query = "SELECT * FROM post_reports LEFT JOIN profiles ON post_reports.account_id = profiles.account_id WHERE post_id = '".$_POST['id']."' AND ignored <> '1'";
			
			$report_result = mysql_query($query);
			echo mysql_error();
			while($report = mysql_fetch_array($report_result)){
				echo "<p><b>".$report['first_name'].' '.$report['last_name'].'</b><br /><small>Reported On: '.date('F d, Y H:i',strtotime($report['date_reported'])).'</small><br />'.urldecode($report['reason']).'</p>';
			}

			echo "<form action='' method='post'>";
				echo "<input type='hidden' name='section' value='".$section."'/>";
				echo "<input type='hidden' name='username' value='".$username."'/>";
				echo "<input type='hidden' name='password' value='".$password."'/>";
				echo "<input type='hidden' name='id' value='".$_POST['id']."'/>";
				echo "<table width='100%' style='border-collapse:collapse'>";
					echo "</tr>";
						echo "<td align='left' class='footer'><p><select name='confirm' class='select' style='width: 55px;'><option value='no'>No</option><option value='yes'>Yes</option></select><input type='submit' name='submit' value='Delete Post' /></td>";
						echo "<td align='right' class='footer'><p><input type='submit' name='submit' value='Ignore Reports' class='f_right button'/></p></td>";
					echo "</tr>";
				echo "</table>";
			echo "</form>";
		}else{
			echo "<div class='tabs'>";

				echo "<ul>";
					echo "<li><a href='#active'>Active Reports</a></li>";
					echo "<li><a href='#ignored'>Ignored Reports</a></li>";
				echo "</ul>";
				echo "<div id='active'>";
				echo "<table width='100%' style='border-collapse: collapse;'>";
					echo "<thead>";
						echo "<th style='border-bottom: 1px solid #ccc;'><p><b>Posted By</b></p></th>";
						echo "<th style='border-bottom: 1px solid #ccc;'><p><b>Thread</b></p></th>";
						echo "<th style='border-bottom: 1px solid #ccc;'><p><b>Forum</b></p></th>";
						echo "<th style='border-bottom: 1px solid #ccc;'><p><b>Reports</b></p></th>";
						echo "<th style='border-bottom: 1px solid #ccc; vertical-align: top;' class='{sorter:false}' width='30px' align='right'><p></p></th>";
					echo "</thead>";

					$result = mysql_query("SELECT *, threads.name AS thread_name, forums.name AS forum_name FROM posts LEFT JOIN threads ON threads.thread_id = posts.thread_id LEFT JOIN forums ON forums.forum_id = threads.forum_id LEFT JOIN profiles ON profiles.account_id = posts.account_id LEFT JOIN (SELECT COUNT(post_id) AS num_reports, post_id FROM post_reports GROUP BY post_id) nr ON nr.post_id = posts.post_id WHERE posts.post_id IN (SELECT post_id FROM post_reports WHERE ignored <> '1') AND posts.deleted <> '1'");
					echo "<tbody>";
					$count = 0;
					while($row = mysql_fetch_array($result)){
						echo "<tr class='row".(($count%2)+1)."'>";
							echo "<td><p>".$row['first_name']." ".$row['last_name']."</p></td>";
							echo "<td><p>".$row['thread_name']."</p></td>";
							echo "<td><p>".$row['forum_name']."</p></td>";
							echo "<td><p>".$row['num_reports']."</p></td>";
							echo "<td style='vertical-align: middle' width='30px'>
									<form action='' method='post'>
										<input type='hidden' name='section' value='".$section."'/>
										<input type='hidden' name='username' value='".$username."'/>
										<input type='hidden' name='password' value='".$password."'/>
										<input type='hidden' name='id' value='".$row['post_id']."'/>
										<input type='submit' name='submit' value='View Post' />
									</form>
									</td>";
						echo "</tr>";
						$count++;
					}
					if (!mysql_num_rows($result)){
						echo "<tr>";
						echo "<td colspan='5'><p><small>No reports to deal with!</small></p></td>";
						echo "</tr>";
					}
					echo "</tbody>";
				echo "</table>";
				echo "</div>";
				echo "<div id='ignored'>";
					echo "<table width='100%' style='border-collapse: collapse;'>";
					echo "<thead>";
						echo "<th style='border-bottom: 1px solid #ccc;'><p><b>Posted By</b></p></th>";
						echo "<th style='border-bottom: 1px solid #ccc;'><p><b>Thread</b></p></th>";
						echo "<th style='border-bottom: 1px solid #ccc;'><p><b>Forum</b></p></th>";
						echo "<th style='border-bottom: 1px solid #ccc;'><p><b>Reports</b></p></th>";
						echo "<th style='border-bottom: 1px solid #ccc; vertical-align: top;' class='{sorter:false}' width='30px' align='right'><p></p></th>";
					echo "</thead>";

					$result = mysql_query("SELECT *, threads.name AS thread_name, forums.name AS forum_name FROM posts LEFT JOIN threads ON threads.thread_id = posts.thread_id LEFT JOIN forums ON forums.forum_id = threads.forum_id LEFT JOIN profiles ON profiles.account_id = posts.account_id LEFT JOIN (SELECT COUNT(post_id) AS num_reports, post_id FROM post_reports GROUP BY post_id) nr ON nr.post_id = posts.post_id WHERE posts.post_id IN (SELECT post_id FROM post_reports WHERE ignored = '1') AND posts.deleted <> '1'");
					echo "<tbody>";
					$count = 0;
					while($row = mysql_fetch_array($result)){
						echo "<tr class='row".(($count%2)+1)."'>";
							echo "<td><p>".$row['first_name']." ".$row['last_name']."</p></td>";
							echo "<td><p>".$row['thread_name']."</p></td>";
							echo "<td><p>".$row['forum_name']."</p></td>";
							echo "<td><p>".$row['num_reports']."</p></td>";
							echo "<td style='vertical-align: middle' width='30px'>
									<form action='' method='post'>
										<input type='hidden' name='section' value='".$section."'/>
										<input type='hidden' name='username' value='".$username."'/>
										<input type='hidden' name='password' value='".$password."'/>
										<input type='hidden' name='id' value='".$row['post_id']."'/>
										<input type='submit' name='submit' value='View Post' />
									</form>
									</td>";
						echo "</tr>";
						$count++;
					}
					if (!mysql_num_rows($result)){
						echo "<tr>";
						echo "<td colspan='5'><p><small>No reports to deal with!</small></p></td>";
						echo "</tr>";
					}
					echo "</tbody>";
				echo "</table>";
				echo "</div>";
				echo "</div>";
		}
	}
?>