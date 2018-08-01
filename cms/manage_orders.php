<?php 
	if ($section==''){
		$result = mysql_query("SELECT * FROM orders");
		echo "<td><p>Total Orders</p></td>";
		echo "<td><p>".number_format(mysql_num_rows($result),0)."</p></td>";
	}
	if ($section=='View Orders'){
		if ($_POST['filter_order_id']!=''){
			//check to see if it exists
			$result = mysql_query("SELECT * FROM orders WHERE order_id = '".$_POST['filter_order_id']."'");
			if (mysql_num_rows($result)){
				//good
				$_POST['id'] = $_POST['filter_order_id'];
			}else{
				alert("<p>Could not find the order number you requested. Please try again.</p>",false);	
			}
			
		}else{
			if ($_POST['filter_start_date']!='' && $_POST['filter_end_date']!=''){
				if (strtotime($_POST['filter_start_date']) > strtotime($_POST['filter_end_date'])){
					alert("<p>Please ensure your start date is less than your end date</p>",false);
				}
			}
		}
		if (($_POST['id']=="") && ($section=="View Orders")){
			//display table of certificates
				echo "<div class='filter clearFix'>";
				
					echo "<h2>Filter</h2>";
					echo "<form action='' method='post'style='padding-bottom:10px' class='clearFix'>";
					echo "<p>";
						echo "<label for='filter_start_date' class='f_left'><small>Start Date:</small><br /><input type='text' name='filter_start_date' id='filter_start_date' value='".$_POST['filter_start_date']."' class='datepicker input'/></label>";
						echo "<label for='filter_end_date' class='f_left'><small>End Date:<br /> <input type='text' name='filter_end_date' id='filter_end_date' value='".$_POST['filter_end_date']."' class='datepicker input' /></small></label>";
						echo "<label for='filter_go'><input type='submit' value='Filter &rsaquo;' style='margin-top: 20px'/></label>";
						echo "<input type='hidden' name='username' value='".$_POST['username']."'/>";
						echo "<input type='hidden' name='password' value='".$_POST['password']."'/>";
						echo "<input type='hidden' name='section' value='".$_POST['section']."'/>";
					echo "</p>";
					echo "</form>";				
					echo "<hr />";
					echo "<form action='' method='post'>";
					echo "<p style='clear:left'>";
					echo "<label for='quickjump'><small>Open Order #</small><br /><input type='text' name='filter_order_id' value='".$_POST['filter_order_id']."' class='input' /></label>";
					echo "<label><input type='submit' name='submit' value='Open &rsaquo;' style='margin-top: 20px;'/></label>";
					
						echo "<input type='hidden' name='username' value='".$_POST['username']."'/>";
						echo "<input type='hidden' name='password' value='".$_POST['password']."'/>";
						echo "<input type='hidden' name='section' value='".$_POST['section']."'/>";
					echo "</p>";
					echo "</form>";
					echo "<form action='' method='post'style='padding-bottom:10px; float: right' class='clearFix'>";
					echo "<p>";
						echo "<input type='submit' value='Clear &rsaquo;' style='margin-top: 20px' class='f_right'/>";
						echo "<input type='hidden' name='username' value='".$_POST['username']."'/>";
						echo "<input type='hidden' name='password' value='".$_POST['password']."'/>";
						echo "<input type='hidden' name='section' value='".$_POST['section']."'/>";
					echo "</p>";
					echo "</form>";		
			echo "</div>";
			echo "<div class='tabs'>";
				echo "<ul>";
					echo "<li><a href='#success'>Successful</a></li>";
					echo "<li><a href='#failed'>Failed</a></li>";
				echo "</ul>";
				echo "<div id='success'>";
				
					echo "<table cellpadding='3' cellspacing='0' border='0' style='width:100%;' class='removepadding tablesorter stickyheader'>";		
						echo "<thead>";
							echo "<th style='border-bottom: 1px solid #3C6C46; width:50px;'><p><b>Order</b></p></th >";
							echo "<th  style='border-bottom: 1px solid #3C6C46;'><p><b>Account</b></p></th>";
							echo "<th style='border-bottom: 1px solid #3C6C46; width='250px'><p><b>Date</b></p></th>";
							echo "<th  style='border-bottom: 1px solid #3C6C46;' width='100px' align='right'><p><b>Amount</b></p></th >";
							echo "<th  style='border-bottom: 1px solid #3C6C46;' width='50px' align='right' class='{sorter:false}'>&nbsp;</th >";
						echo "</thead>";
						$where = '';
						if ($_POST['filter_start_date']!=''){
							$where = " AND date_ordered >= '".date('Y-m-d',strtotime($_POST['filter_start_date']))."'";
						}
						if ($_POST['filter_end_date'] !=''){
							
							$where .= " AND date_ordered <= '".date('Y-m-d',strtotime($_POST['filter_end_date']))."'";
						}
						if ($_POST['filter_start_date']=='' && $_POST['filter_end_date']==''){
							$where = '2=3';
						}
						$query = "SELECT orders.* FROM orders WHERE success='1' ".$where." ORDER BY date_ordered DESC";
						$result = mysql_query($query);

						$count = 0;
						while($order = mysql_fetch_array($result)){
							echo "<tr class='row".(($count%2)+1)."'>";
								echo "<td><p>".$order['order_id']."</p></td>";								
								echo "<td><p>".$order['first_name']." ".$order['last_name']."<br /><small>(".($order['street_address2']!='' ? $order['street_address2'].", " : "")." ".$order['street_address']." ".$order['city']." ".$order['province']." ".$order['country']." ".$order['postal_code'].")</small></p></td>";
								echo "<td><p>".date('F d, Y',strtotime($order['date_ordered']))."</p></td>";
								echo "<td><p>$".$order['total']."</p></td>";
								echo "<td><p><form action='' method='post' style='margin: 0px;'><input type='hidden' name='id' value='".$order['order_id']."'/><input type='hidden' name='username' value='".$_POST['username']."'/><input type='hidden' name='password' value='".$_POST['password']."'/><input type='hidden' name='section' value='".$_POST['section']."'/><input type='submit' name='submit' value='View'/></form></p></td>";
							echo "</tr>";
							$count++;
								
						}
						if (!mysql_num_rows($result)){
							echo "<tr><td colspan='5'><p><small>No orders to display</small></p></td></tr>";
						}
					echo "</table>";
				echo "</div>";
				echo "<div id='failed'>";
				
				echo "<table cellpadding='3' cellspacing='0' border='0' style='width:100%;' class='removepadding tablesorter stickyheader'>";		
						echo "<thead>";
							echo "<th  style='border-bottom: 1px solid #3C6C46; width:50px;'><p><b>Order</b></p></th>";
							echo "<th  style='border-bottom: 1px solid #3C6C46;'><p><b>Account</b></p></th >";
							echo "<th style='border-bottom: 1px solid #3C6C46; width='250px'><p><b>Date</b></p></th>";
							echo "<th  style='border-bottom: 1px solid #3C6C46;' width='100px' align='right'><p><b>Amount</b></p></th >";
							echo "<th  style='border-bottom: 1px solid #3C6C46;' width='50px' align='right' class='{sorter:false}'>&nbsp;</th >";
						echo "</thead>";
						$where = '';
							if ($_POST['filter_start_date']!=''){
								$where = " AND date_ordered >= '".date('Y-m-d',strtotime($_POST['filter_start_date']))."'";
							}
							if ($_POST['filter_end_date'] !=''){
								
								$where .= " AND date_ordered <= '".date('Y-m-d',strtotime($_POST['filter_end_date']))."'";
							}
						$query = "SELECT orders.* FROM orders WHERE success='0' ".$where." ORDER BY date_ordered DESC";
						$result = mysql_query($query);
						
						$count = 0;
						while($order = mysql_fetch_array($result)){
							echo "<tr class='row".(($count%2)+1)."'>";
								echo "<td><p>".$order['order_id']."</p></td>";
								echo "<td><p>".$order['first_name']." ".$order['last_name']."<br /><small>(".($order['street_address2']!='' ? $order['street_address2'].", " : "")." ".$order['street_address']." ".$order['city']." ".$order['province']." ".$order['country']." ".$order['postal_code'].")</small></p></td>";
								echo "<td><p>".date('F d, Y',strtotime($order['date_ordered']))."</p></td>";
								echo "<td><p>$".$order['total']."</p></td>";
								echo "<td><p><form action='' method='post' style='margin: 0px;'><input type='hidden' name='id' value='".$order['order_id']."'/><input type='hidden' name='username' value='".$_POST['username']."'/><input type='hidden' name='password' value='".$_POST['password']."'/><input type='hidden' name='section' value='".$_POST['section']."'/><input type='submit' name='submit' value='View'/></form></p></td>";
							echo "</tr>";
							$count++;
								
						}
						if (!mysql_num_rows($result)){
							echo "<tr><td colspan='5'><p><small>No matching orders found</small></p></td></tr>";
						}
					echo "</table>";
				echo "</div>";
				
		}else if ($_POST['id']!=""){
			//ID is set, show form
			if ($_POST['id']!=''){
				$order = mysql_query("SELECT * FROM orders WHERE order_id = '".$_POST['id']."'");	
				$order = mysql_fetch_array($order,1);
			}
			
			echo "<form action='' method='post' enctype='multipart/form-data'>";
			echo "<table cellpadding='3' cellspacing='0' border='0' width='100%' class='removepadding'>";
				echo "<tr>";
					echo "<td width='0px'><p></p></td>";
					echo "<td><h2>Order #FPS-".$order['order_id']."<br /><small>Date: ".date('F d, Y H:i',strtotime($order['date_ordered']))."</small></h2>
							<p>".$order['first_name']." ".$order['last_name']."<br />
								".($order['street_address2']!='' ? $order['street_address2'].", " : "")." ".$order['street_address']."<br />
								".$order['city']." ".$order['province']." ".$order['postal_code']."<br/>".$order['country']."
								".$order['primary_phone']."</p><br />";
								
							$items = mysql_query("SELECT order_items.*, vendors.vendor_name FROM order_items LEFT JOIN vendors ON vendors.vendor_id = order_items.vendor_id WHERE order_id = '".$order['order_id']."'");
							
							echo "<table width='650px;' cellspacing='0' style='border-collapse: collapse;' class='itemtable'>";
								echo "<thead>";
									echo "<th style='border-bottom: 1px solid #ccc; background: #5d8467;'><p style='padding:2px 5px'><b>Item</b></p></th>";
									echo "<th style='border-bottom: 1px solid #ccc; background: #5d8467;width: 80px' align='right'><p style='text-align:right; padding: 2px 5px;'><b>Total</b></p></th>";
								echo "</thead>";
								echo "<tbody>";
								
								
								echo "<tr>";
									echo "<td><p>Year Membership</p></td>";
									echo "<td><p style='text-align:right'>$".number_format(($order['total']-$order['taxes']),2)."</p></td>";
								echo "</tr>";
								
								echo "<tfoot>";
									echo "<tr>";
										echo "<td align='right' style='text-align: right; border-top: 1px solid #ccc;'><p style='text-align:right'>Taxes</p></td>";
										echo "<td style='border-top: 1px solid #ccc;'><p style='text-align: right'>".$order['taxes']."</p></td>";
									echo "</tr>";	
									echo "<tr>";
										echo "<td colspan='1' align='right'style='text-align: right;'><p style='text-align:right; font-size: 16px; padding-top: 5px;'><b>Total</b></p></td>";
										echo "<td style='border-top: 3px double #ccc;'><p style='text-align:right; font-size: 16px; padding-top: 5:30px;'><b>$".$order['total']."</b></p></td>";
									echo "</tr>";
								echo "</tfoot>";
							echo "</table>";
							echo "<h2>Moneris Response:</h2>";
							echo "<p>Reference Num: ".$order['reference_num']."<br />Transaction Num: ".$order['transaction_num']."<br />Response Code: ".$order['transaction_code']."</p>
						</td>";
					
				echo "</tr>";
				echo "<tr>";
					if ($_POST['id']!=""){
						echo "<td class='footer' align='left' colspan='2'><p><input type='button' onclick='javascript:window.history.back(1);' value='&lsaquo; Back'/></p></td>";
					}else{
						echo "<td class='footer' colspan='2'>&nbsp;</td>";	
					}
				echo "</tr>";
			echo "</table>";
			echo "</form>";
		}
	}
?>