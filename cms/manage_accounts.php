<?php 
	if ($section==''){
		$result = mysql_query("SELECT * FROM accounts WHERE account_id IN (SELECT account_id FROM registrations WHERE expires >=now())");
		$total_active = mysql_num_rows($result);
		$result = mysql_query("SELECT * FROM accounts");
		$total = mysql_num_rows($result);
		echo "<td><p>Active Accounts</p></td>";
		echo "<td><p>".number_format($total_active,0)." <small>(".$total." total)</small></p></td>";

	}

	if ($section=='User Accounts'){
		if ($_POST['id']!=''){
			if ($_POST['submit']=='Update Status'){
				str_replace("'","\'",$_POST['ban_reason']);
				$result = mysql_query("UPDATE accounts SET ban_reason = '".$_POST['ban_reason']."', banned='".($_POST['confirm']=='banned' ? 1 : 0)."' WHERE account_id = '".$_POST['id']."'");
				if (!mysql_error()){
					if ($_POST['confirm']=='banned'){
						alert("<p>You have succesfully updated this account. (Status: BANNED)",true);
					}else{
						alert("<p>You have successfully updated this account. (Status: ACTIVE)",true);
					}
				}else{
					echo mysql_error();
				}
			}
			$result = mysql_query("SELECT * FROM accounts LEFT JOIN profiles ON profiles.account_id = accounts.account_id WHERE accounts.account_id = '".$_POST['id']."'");
			$account = mysql_fetch_array($result,1);
			echo '<h2>'.$account['first_name'].' '.$account['last_name'].'<br /><small><b>Member Till: '.date('F d, Y',strtotime($row['expires'])).'</b></small></h2>';

			echo "<form action='' method='post' id='backform'><input type='hidden' name='section' value='".$section."'/><input type='hidden' name='username' value='".$username."'/><input type='hidden' name='password' value='".$password."'/><a href='javascript:' onclick='javascript:document.getElementById(\"backform\").submit()'>&lsaquo; Back To All Accounts</a></form>";
				echo "<div class='tabs'>";
					echo "<ul>";
						echo "<li><a href='#personal'>Account Information</a></li>";
						echo "<li><a href='#feedback'>Feedback</a></li>";
						echo "<li><a href='#references'>References</a></li>";
						echo "<li><a href='#orders'>Order History</a></li>";
					echo "</ul>";
					echo "<div id='personal'>";
						echo '<p>'.$account['email'].'<br />';
						echo ($account['street_address2']!='' ? $account['street_address2'].', ' : "").$account['street_address'].'<br />';
						echo $account['postal_code'].' '.$account['province'].'<br />';
						echo $account['phone_num'].'</p>';
						echo "<p><b>Pets I can Watch: </b><br />".$account['pets_watch'].'</p>';
						echo "<p><b>Features Of My Home</b><br />".$account['home_features'].'</p>';
						echo "<p><b>Fun Facts</b><br />".$account['fun_facts']."</p>";
						echo "<p><b>Will Travel</b><br />".$account['will_travel'].'</p>';
						echo "<p><b>How did you hear of us? </b><br />".$account['heardus']."</p>";
					echo "</div>";
					echo "<div id='feedback'>";
						$result = mysql_query("SELECT * FROM feedback LEFT JOIN profiles ON profiles.account_id = feedback.account_id WHERE feedback.account_id <> '".$_POST['id']."' AND request_id IN (SELECT request_id FROM sitter_requests WHERE account_id = '".$_POST['account_id']."' OR (volunteer_id ='".$_POST['account_id']."' AND volunteer_confirmed='1'))");
						echo mysql_error();
						while($feedback = mysql_fetch_array($result,1)){
							if ($feedback['volunteer_id']==$_POST['id']){
								$feedbacker = 'sitter';
							}else{
								$feedbacker = 'requester';
							}
							echo "<div>
								<h3>".$first_name.' '.$last_name.'<small ';

									if ($feedbacker=='sitter'){
										echo " class='blue'> (Pet Sitter)";
									}else{
										echo " class='green'> (Requested Pet Sitting)";
									}
								echo '</small><br /><small>Submitted On: '.date('F d, Y',strtotime($date_added)).'</small></h3>';

									for($i=0; $i<$stars; $i++){
										echo "<span class='singlestar active'></span>";
									}
									for ($i=$stars; $i<5; $i++){
										echo "<span class='singlestar'></span>";
									}
								echo "<div class='clearFix' ></div><p style='padding-top: 10px;'>".$feedback."</p>
								
							</div>";
						}
						if (!mysql_num_rows($result)){
							echo "<p><small>No feedback has been received for this user</small></p>";
						}
					echo "</div>";
					echo "<div id='references' class='clearFix'>";
						$result = mysql_query("SELECT * FROM account_references WHERE account_id = '".$_POST['id']."'");
						while($row = mysql_fetch_array($result)){
							echo "<div style='width:300px; padding: 15px; float: left; border: 1px solid #ccc;'>";
								echo "<table width='100%'>";
									echo "<tr>";
										echo "<td width='160px;'><p>Name:</p></td>";
										echo "<td><p>".$row['supplied_name'].'</p></td>';
									echo "</tr>";
									echo "<tr>";
										echo "<td><p>Email:</p></td>";
										echo "<td><p>".$row['email']."</p></td>";
									echo "</tr>";
									echo "<tr>";
										echo "<td><p>City:</p></td>";
										echo "<td><p>".$row['city']."</p></td>";
									echo "</tr>";
									echo "<tr>";	
										echo "<td><p>Pet Owner: </p></td>";
										echo "<td><p>".($row['pet_owner'] ? "<img src='../cms/images/icon_check.png'/>" : "<img src='../cms/images/icon_delete.png'/>")."</p></td>";
									echo "</tr>";
									echo "<tr>";
										echo "<td><p>Relationship To Member:</p></td>";
										echo "<td><p>".$row['your_relationship']."</p></td>";
									echo "</tr>";
									echo "<tr>";
										echo "<td><p>Known Since:</p></td>";
										echo "<td><p>".$row['known_since']."</p></td>";
									echo "</tr>";
									echo "<tr>";
										echo "<td><p style='padding: 0px; margin: 0px;'>Sitter Suitability:</p></td>";
										echo "<td><p style='padding: 0px; margin: 0px;'>";
											for($i=0; $i<$row['sitter_suitability']; $i++){
												echo "<span class='singlestar active'></span>";
											}
											for($i=$i; $i<5; $i++){
												echo "<span class='singlestar'></span>";
											}
										echo "</p></td>";
										echo "</tr>";
									echo "<tr>";
										echo "<td><p style='padding: 0px; margin: 0px;'>Sitter Home:</p></td>";
										echo "<td><p style='padding: 0px; margin: 0px;'>";
											for($i=0; $i<$row['sitter_home']; $i++){
												echo "<span class='singlestar active'></span>";
											}
											for($i=$i; $i<5; $i++){
												echo "<span class='singlestar'></span>";
											}
										echo "</p></td>";
									echo "</tr>";	
									echo "<tr>";
										echo "<td><p style='padding: 0px; margin: 0px;'>Sitter Reliability:</p></td>";
										echo "<td><p style='padding: 0px; margin: 0px;'>";
											for($i=0; $i<$row['sitter_reliability']; $i++){
												echo "<span class='singlestar active'></span>";
											}
											for($i=$i; $i<5; $i++){
												echo "<span class='singlestar'></span>";
											}
										echo "</p></td>";
									echo "</tr>";	
									echo "<tr>";
										echo "<td colspan='2'><p>Comments</p></td>";
										
									echo "</tr>";	
									echo "<tr>";
										echo "<td colspan='2'><p>".$row['comments']."</p></td>";
									echo "</tr>";
								echo "</table>";
								echo "<div class='clearFix' style='clear:both'></div>";
							echo "</div>";
						}
					echo "</div>";
					echo "<div id='orders'>";
						$result = mysql_query("SELECT * FROM orders WHERE account_id = '".$_POST['id']."'");
						
						echo "<table width='100%' class='tablesorter stickyheader' style='border-collapse: collapse;'>";
							echo "<thead>";
								echo "<th style='border-bottom: 1px solid #ccc;'><p><b>Order #</b></p></th>";
								echo "<th style='border-bottom: 1px solid #ccc;'><p><b>Date Ordered</b></p></th>";
								echo "<th style='border-bottom: 1px solid #ccc;'><p><b>Transaction Num</b></p></th>";
								echo "<th style='border-bottom: 1px solid #ccc;'><p><b>Response</b></p></th>";
								echo "<th style='border-bottom: 1px solid #ccc;'><p><b>Amount</b></p></th>";
								echo "<th style='border-bottom: 1px solid #ccc;' class='{sorter:false}' align='right' width='40px'><p></p></th>";
							echo "</thead>";
							$count = 0;
							while($row = mysql_fetch_array($result,1)){
								echo "<tr class='row".(($count%2)+1)."'>";
									echo "<td><p>FPS-".$row['order_id']."</p></td>";
									echo "<td><p>".date('F d, Y H:i',strtotime($row['date_ordered']))."</p></td>";
									echo "<td><p>".$row['transaction_num']."</p></td>";
									echo "<td><p>".$row['transaction_message']."</p></td>";
									echo "<td><p>$".$row['total']."</p></td>";
									echo "<td align='right'><p style='text-align: right'><form action='' method='post'><input type='hidden' name='section' value='View Orders'/><input type='hidden' name='id' value='".$row['order_id']."'/><input type='hidden' name='username' value='".$username."'/><input type='hidden' name='password' value='".$password."'/><input type='submit' name='submit' value='View'/></form></p></td>";
								echo "</tr>";
								$count++;
							}
							
							if (!mysql_num_rows($result)){
								echo "<tr>";
								echo "<td colspan='6'><p><small>No orders found</small></p></td>";
								echo "</tr>";
							}

						echo "</table>";
					echo "</div>";

				echo "</div>";
				echo "<hr />";
				echo "<div><h2>Ban Account</h2><form action='' method='post'><input type='hidden' name='id' value='".$_POST['id']."'/><input type='hidden' name='username' value='".$username."'/><input type='hidden' name='password' value='".$password."'/><input type='hidden' name='section' value='".$section."'/><p>Ban Reason:<br /><textarea name='ban_reason' class='textarea' style='height: 70px'>".$account['ban_reason']."</textarea><br /><select name='confirm' class='select' style='width: 150px'><option value=''>Active</option><option value='banned' ".($account['banned']=='1' ? "selected" : "").">Banned</option></select><input type='submit' name='submit' value='Update Status'/></p></form></div>";

				
		}else{
			echo "<table cellpadding='3' cellspacing='0' border='0' style='width:100%;' class='removepadding tablesorter stickyheader'>";		
			echo "<thead>";
				echo "<th style='border-bottom: 1px solid #3C6C46; width: 50px;'><p><b>Active</b></p></th>";
				echo "<th style='border-bottom: 1px solid #3C6C46; width: 30px;'><p><b></b></p></th>";

				echo "<th  style='border-bottom: 1px solid #3C6C46;'><p><b>Account</b></p></th>";
				echo "<th style='border-bottom: 1px solid #3C6C46;'><p><b>Date Joined</b></p></th>";
				echo "<th style='border-bottom: 1px solid #3C6C46;'><p><b>Last Login</b></p></th>";
				echo "<th style='border-bottom: 1px solid #3C6C46; width='250px'><p><b>Member Till</b></p></th>";
				echo "<th  style='border-bottom: 1px solid #3C6C46;' width='50px' align='right' class='{sorter:false}'>&nbsp;</th >";
			echo "</thead>";
			
			$result = mysql_query("SELECT accounts.*, profiles.first_name, profiles.last_name, accounts.last_login, accounts.date_joined, r.expires, ref.refcount, ref.refscore FROM accounts LEFT JOIN profiles ON profiles.account_id = accounts.account_id LEFT JOIN (SELECT registrations.expires, account_id FROM registrations GROUP BY account_id ORDER BY expires DESC ) r ON r.account_id = accounts.account_id LEFT JOIN (SELECT COUNT(reference_id) AS refcount, (sitter_home+sitter_reliability+sitter_suitability) AS refscore, account_id FROM account_references WHERE date_completed IS NOT NULL GROUP BY account_id) ref ON ref.account_id = accounts.account_id ORDER BY email ASC");
			
			$count = 0;
			while($row = mysql_fetch_array($result)){
				echo "<tr class='row".(($count%2)+1)."'>";
					echo "<td><p>".(strtotime($row['expires'])>time() ? ($row['banned']=='0' ? "<img src='../cms/images/icon_check.png'/>" : "") : "")."</p></td>";
					echo "<td><p style='".($row['banned']=='1' ? "text-decoration: line-through; color:#ccc;" : "")."'>".(($row['refscore']<=4 && $row['refcount']>0) ? "<img src='../cms/images/icon_flag.png' class='help' title='<span>Referral Score</span><p>This user has a combined referral score of less than 5. You may want to review their suitability to be a pet sitter.<br /><br />Number of referrals: ".$row['refcount']."<br />Referral Score: ".$row['refscore']."</p>' />" : "")."</p></td>";
					echo "<td><p style='".($row['banned']=='1' ? "text-decoration: line-through; color:#ccc;" : "")."'>".$row['first_name'].' '.$row['last_name'].'<br /><small>'.$row['email'].'</small></p></td>';
					echo "<td><p style='".($row['banned']=='1' ? "text-decoration: line-through; color:#ccc;" : "")."'>".date('F d, Y',strtotime($row['date_joined']))."</p></td>";
					echo "<td><p style='".($row['banned']=='1' ? "text-decoration: line-through; color:#ccc;" : "")."'>".date('F d, Y',strtotime($row['last_login']))."</p></td>";
					echo "<td><p style='".($row['banned']=='1' ? "text-decoration: line-through; color:#ccc;" : "")."'>".date('F d, Y',strtotime($row['expires']))."</p></td>";
					echo "<td><p><form action='' method='post'><input type='hidden' name='id' value='".$row['account_id']."'/><input type='hidden' name='section' value='".$section."'/><input type='hidden' name='username' value='".$username."'/><input type='hidden' name='password' value='".$password."'/><input type='submit' name='submit' value='View' class='button f_right'/></form></p></td>";
				echo "</tr>";
				$count++;
			}
		
		}
	}

?>