<?php 
	
	if ($section=='Manage Forums'){
		if (count($_POST) && $_POST['submit']=='Save Forum'){
			$_POST['name'] = str_replace("'","&#39;",$_POST['name']);
			$result = mysql_query("UPDATE forums SET name = '".$_POST['name']."' WHERE forum_id = '".$_POST['id']."'");

			if (!mysql_error()){
				alert('<p>Forum has been updated</p>',true);
				$_POST['id']='';
			}else{
				alert('<p>There was a problem updating this forum. Please try again later.',false);
			}
		}
		if ($_POST['id']!=''){
			$result = mysql_query("SELECT * FROM forums WHERE forum_id = '".$_POST['id']."'");
			$forum = mysql_fetch_array($result,1);
			echo "<form action='' method='post'>";

			echo "<table style='border-collapse: collapse;'>";
				echo "<tr>";
					echo "<td width='135px'><p>Forum Name: </p></td>";
					echo "<td><p><input type='text' name='name' class='input' value='".$forum['name']."'/></p></td>";
				echo "</tr>";
				

			echo "</table>";
			

				echo "<input type='hidden' name='section' value='".$section."'/>";
				echo "<input type='hidden' name='username' value='".$username."'/>";
				echo "<input type='hidden' name='password' value='".$password."'/>";
				echo "<input type='hidden' name='id' value='".$_POST['id']."'/>";
				echo "<table width='100%' style='border-collapse:collapse'>";
					echo "</tr>";
						echo "<td align='right' class='footer'><p><input type='submit' name='submit' value='Save Forum' class='f_right button'/></p></td>";
					echo "</tr>";
				echo "</table>";
			echo "</form>";
		}else{
				
			echo "<table width='100%' style='border-collapse: collapse;' class='tablesorter stickytableheader'>";
				echo "<thead>";
					echo "<th style='border-bottom: 1px solid #ccc;'><p><b>Forum</b></p></th>";
					echo "<th style='border-bottom: 1px solid #ccc;'><p><b>Threads</b></p></th>";
					echo "<th style='border-bottom: 1px solid #ccc; vertical-align: top;' class='{sorter:false}' width='30px' align='right'><p></p></th>";
				echo "</thead>";
				$query = "SELECT * FROM forums LEFT JOIN (SELECT count(thread_id) AS num_threads, forum_id FROM threads WHERE deleted <> '1' GROUP BY forum_id) nt ON nt.forum_id = forums.forum_id ORDER BY name ASC";
				$result = mysql_query($query);

				echo "<tbody>";
				$count = 0;
				while($row = mysql_fetch_array($result)){
					echo "<tr class='row".(($count%2)+1)."'>";
						echo "<td><p>".$row['name']."</p></td>";
						echo "<td><p>".number_format($row['num_threads'],0)."</p></td>";						
						echo "<td style='vertical-align: top' width='30px'><p>
								<form action='' method='post'>
									<input type='hidden' name='section' value='".$section."'/>
									<input type='hidden' name='username' value='".$username."'/>
									<input type='hidden' name='password' value='".$password."'/>
									<input type='hidden' name='id' value='".$row['forum_id']."'/>
									<input type='submit' name='submit' value='Edit'/>
								</form></p>
								</td>";
					echo "</tr>";
					$count++;
				}
				if (!mysql_num_rows($result)){
					echo "<tr>";
					echo "<td colspan='5'><p><small>No forums found.</small></p></td>";
					echo "</tr>";
				}
				echo "</tbody>";
			echo "</table>";
		}
	}
?>