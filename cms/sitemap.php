<?php

if ($section == "Site Map") {
	
	echo "<p>Below is an overview for www.pixelarmy.ca</p>";
	
	// Site Map
	$page_cnt = 0;
	
	for ($i=0; $i<count($pages); $i++) {
		for ($j=0; $j<count($pages[$i]); $j++) {
			$page_cnt++;
		}
	}
	
	echo "<script language='javascript'>data.addRows(" .$page_cnt .");</script>";
	
	$parent_btn = "www.pixelarmy.ca";
	
	echo "<script language='javascript'>data.setCell(0, 0, '" .$parent_btn ."');</script>";
	echo "<script language='javascript'>data.setCell(0, 2, '" .$parent_btn ."');</script>";
	echo "<script language='javascript'>data.setRowProperty(0, 'style', 'border: 1px solid #AAA; background-color:#FFF; width:100px; font-family:Helvetica; font-size: 12px; color:#333; padding: 5px 10px 5px 10px;');</script>";
	echo "<script language='javascript'>data.setRowProperty(0, 'selectedStyle', 'border: 1px solid #AAA; background-color:#FFF; width:100px; font-family:Helvetica; font-size: 12px; color:#333; padding: 5px 10px 5px 10px;');</script>";
	
	$cnt = 0;
	for ($i=0; $i<count($pages); $i++) {
		for ($j=0; $j<count($pages[$i]); $j++) {
			if ($j > 0) {
				$parent_btn = $pages[$i][0][0];
			} else {
				$parent_btn = "www.pixelarmy.ca";
			}
			$cnt++;
			
			echo "<script language='javascript'>data.setCell(" .$cnt .", 0, '" .$pages[$i][$j][0] ."');</script>";
			echo "<script language='javascript'>data.setCell(" .$cnt .", 1, '" .$parent_btn ."');</script>";
			
			echo "<script language='javascript'>data.setRowProperty(" .$cnt .", 'style', 'border: 1px solid #AAA; background-color:#FFF; width:100px; font-family:Helvetica; font-size: 12px; color:#333; padding: 5px 10px 5px 10px;');</script>";
			echo "<script language='javascript'>data.setRowProperty(" .$cnt .", 'selectedStyle', 'border: 1px solid #AAA; background-color:#FFF; width:100px; font-family:Helvetica; font-size: 12px; color:#333; padding: 5px 10px 5px 10px;');</script>";
			
		}
	}
	
	
	echo "<div id='visualization' style='width: 300px; height: 300px;'></div>";
	
	echo "<script language='javascript'>drawVisualization(data);</script>";
	
}
								
?>
