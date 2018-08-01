<?php

$bad_items = array("-", ",", "&", "/", " ", "'", "&rsquo;", ".");
$good_items = array("", "", "", "", "", "", "", "", "");


$maintext = false;
$saved = false;
$deleted = false;

$page = $_POST['page'];

if ($section == "") {
	$page_cnt = 0;
	
	for ($i=0; $i<count($pages); $i++) {
		for ($j=0; $j<count($pages[$i]); $j++) {
			for ($k=0; $k<count($pages[$i][$j]); $k++) {
				for ($l=0; $l<count($pages[$i][$j][$k]); $l++) {
					for ($m=0; $m<count($pages[$i][$j][$k][$l]); $m++) {
						$page_cnt++;
					}
				}
			}
		}
	}
	
	echo "<td><p>Total Page Count</p></td>";
	echo "<td><p>" .$page_cnt ."</p></td>";
}

if ($section == "Manage Page Content") {
	
	
	$db = $_POST['database'];
	$reference = $_POST['pageref'];
	$ID = $_POST['ID'];
	$old_image = $_POST['old_image'];
	
	//will return nav1, nav2, nav3, nav4, or nav5
	$checkDB = substr("$db", 0, 4);
	
	if ($submit == "Delete") {
		$confirm = $_POST['confirm'];
		
		if ($confirm == "Yes") {
			
				$sql = mysql_query("ALTER TABLE $db ORDER BY id")or die("Could Not Re-order Database");
				
				$result = mysql_query("DELETE FROM $db where id=$ID") or die ("could not delete the record");
					
				$delete = mysql_query("ALTER TABLE $db DROP id") or die ("could not delete the old id field");
				$rebuild = mysql_query("ALTER TABLE $db ADD id INT NOT NULL PRIMARY KEY AUTO_INCREMENT") or die ("did not rebuild id field");
				
				$sql = mysql_query("ALTER TABLE $db ORDER BY id");
				
				//check for subpage tables under top level nav
				if($checkDB == "nav1"){
					
					$tbl2 = @mysql_query("SELECT * FROM `nav2_$reference`");
					if($tbl2){
						
						while($tblrow2 = mysql_fetch_array($tbl2)){
							$reference2 = $tblrow2['reference'];
							
							//check for subpage table under second level nav
							$tbl3 = @mysql_query("SELECT * FROM `nav3_$reference$reference2`");
							if($tbl3){
								
								while($tblrow3 = mysql_fetch_array($tbl3)){
								$reference3 = $tblrow3['reference'];
									
									//check for subpage table under third level nav
									$tbl4 = @mysql_query("SELECT * FROM `nav4_$reference$reference2$reference3`");
									if($tbl4){
										
										while($tblrow4 = mysql_fetch_array($tbl4)){
										$reference4 = $tblrow4['reference'];
										
											//check for subpage table under fourth level nav
											$tbl5 = @mysql_query("SELECT * FROM `nav5_$reference$reference2$reference3$reference4`");
											if($tbl5){
												
												//delete table
												$droptbl5 = mysql_query("DROP TABLE `nav5_$reference$reference2$reference3$reference4`") or die ("Could not delete fourth level database");
											}
										
										}
										
										//delete table
										$droptbl4 = mysql_query("DROP TABLE `nav4_$reference$reference2$reference3`") or die ("Could not delete fourth level database");
									}
									
								}
								
								//delete table
								$droptbl3 = mysql_query("DROP TABLE `nav3_$reference$reference2`") or die ("Could not delete third level database");
							}
							
						}
		
						//delete table
						$droptbl2 = mysql_query("DROP TABLE `nav2_$reference`") or die ("Could not delete second level database");
					}
	
				//check for subpage tables under second level nav
				}else if($checkDB == "nav2"){
					
					//check for subpage table under second level nav
					$tbl3 = @mysql_query("SELECT * FROM `nav3_$reference`");
					if($tbl3){
						
						while($tblrow3 = mysql_fetch_array($tbl3)){
						$reference3 = $tblrow3['reference'];
							
							//check for subpage table under third level nav
							$tbl4 = @mysql_query("SELECT * FROM `nav4_$reference$reference3`");
							if($tbl4){
								
								while($tblrow4 = mysql_fetch_array($tbl4)){
								$reference4 = $tblrow4['reference'];
								
									//check for subpage table under fourth level nav
									$tbl5 = @mysql_query("SELECT * FROM `nav5_$reference$reference3$reference4`");
									if($tbl5){
										
										//delete table
										$droptbl5 = mysql_query("DROP TABLE `nav5_$reference$reference3$reference4`") or die ("Could not delete fourth level database");
									}
								
								}
								
								//delete table
								$droptbl4 = mysql_query("DROP TABLE `nav4_$reference$reference3`") or die ("Could not delete fourth level database");
							}
							
						}
						
						//delete table
						$droptbl3 = mysql_query("DROP TABLE `nav3_$reference`") or die ("Could not delete third level database");
					}

				//check for subpage tables under third level nav
				}else if($checkDB == "nav3"){
		
					//check for subpage table under third level nav
					$tbl4 = @mysql_query("SELECT * FROM `nav4_$reference`");
					if($tbl4){
						
						while($tblrow4 = mysql_fetch_array($tbl4)){
						$reference4 = $tblrow4['reference'];
						
							//check for subpage table under fourth level nav
							$tbl5 = @mysql_query("SELECT * FROM `nav5_$reference$reference4`");
							if($tbl5){
								
								//delete table
								$droptbl5 = mysql_query("DROP TABLE `nav5_$reference$reference4`") or die ("Could not delete fourth level database");
							}
						
						}
						
						//delete table
						$droptbl4 = mysql_query("DROP TABLE `nav4_$reference`") or die ("Could not delete fourth level database");
					}
							
				//check for subpage tables under fourth level nav
				}else if($checkDB == "nav4"){
		
					//check for subpage table under fourth level nav
					$tbl5 = @mysql_query("SELECT * FROM `nav5_$reference`");
					if($tbl5){
						
						//delete table
						$droptbl5 = mysql_query("DROP TABLE `nav5_$reference`") or die ("Could not delete fourth level database");
					}
							
				}
			
			
			
			alert ("<p><b>Success!</b> Page and all subpages were successfully deleted.</p>", true);
			$ID = "";
			
			$saved = true;
			echo "<form action='' method='post'>";
			echo "<input type='submit' name='submit' value='Continue' class='submit' />";
			
			echo "<input type='hidden' name='username' value='" .$username ."' />";
			echo "<input type='hidden' name='password' value='" .$password ."' />";
			echo "<input type='hidden' name='section' value='Edit Page' />";
			echo "</form>";
			
		} else {
			alert ("<p><b>Error!</b> You must confirm deletion by selecting Yes from the pulldown menu.</p>", false);
		}
		
		
	} else if ($submit == "Save Changes") {
		
		
		$name = str_replace("'", "&rsquo;", stripslashes($_POST['name']));
		$url = str_replace("'", "&rsquo;", stripslashes($_POST['url']));
		$urltarget = $_POST['urltarget'];
		$showhide = $_POST['showhide'];
		$type = $_POST['type'];
		$ordering = $_POST['ordering'];
		$deleteable = $_POST['deleteable'];
		$pagename = $_POST['pagename'];
		
		$page_title = str_replace("'", "&rsquo;", stripslashes($_POST['page_title']));
		$meta_title = str_replace("'", "&rsquo;", stripslashes($_POST['meta_title']));
		$meta_description = str_replace("'", "&rsquo;", stripslashes($_POST['meta_description']));
		$meta_keywords = str_replace("'", "&rsquo;", stripslashes($_POST['meta_keywords']));
		
		if(trim($page_title) == ""){ $page_title = $name; }
		if(trim($meta_title) == ""){ $meta_title = $name; }
		
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
		
		/*$sidebar = str_replace("'", "&rsquo;", stripslashes($_POST['FCKeditor2']));
		$sidebar = str_replace("<p>&nbsp;</p>", "", $sidebar);
		
		$sidebar = str_replace('border-top-color: rgb(211, 211, 211);', '', $sidebar);
		$sidebar = str_replace('border-right-color: rgb(211, 211, 211);', '', $sidebar);
		$sidebar = str_replace('border-bottom-color: rgb(211, 211, 211);', '', $sidebar);
		$sidebar = str_replace('border-left-color: rgb(211, 211, 211);', '', $sidebar);
		$sidebar = str_replace('border-top-width: 1px;', '', $sidebar);
		$sidebar = str_replace('border-right-width: 1px;', '', $sidebar);
		$sidebar = str_replace('border-bottom-width: 1px;', '', $sidebar);
		$sidebar = str_replace('border-left-width: 1px;', '', $sidebar);
		$sidebar = str_replace('border-top-style: dotted;', '', $sidebar);
		$sidebar = str_replace('border-right-style: dotted;', '', $sidebar);
		$sidebar = str_replace('border-bottom-style: dotted;', '', $sidebar);
		$sidebar = str_replace('border-left-style: dotted;', '', $sidebar);
		$sidebar = str_replace('color: rgb(82, 119, 128); text-decoration: underline;', '', $sidebar);
		
		$image = $_FILES['image'];
		$deleteimage = $_POST['deleteimage'];
		if($image['name'] != "") {
			uploadImage2("../photos/", $image, "jpg", 509, 298);
			
			$image = $image['name'];
			
		} else {
			if($deleteimage == true){
				$image = "";
			}else{
				$image = $_POST['old_image'];	
			}
		}*/	
		$newimage = $_POST['old_filename'];

		if ($_FILES['filename']['name']!=''){
			require_once('../includes/classes/imageman.class.php');
			$img = new Imageman();
			$img->load($_FILES['filename']['tmp_name']);
			$imgerror = $img->valid_image();
				//valid image, show crop tools
				$newimage = time().'.jpg';
				$img->save('../images/banners/full/',$newimage,'jpg');
				//move_uploaded_file($_FILES['filename']['tmp_name'],'../images/slideshow/full/'.$newimage);
				//do a smart crop for the time being, and redirect user to the crop
				unset($img);
				$img = new Imageman();
				if (!$img->load('../images/banners/full/'.$newimage)){
					$errors = true;
					alert('<p>Couldnt save banner image.</p>', false);

				}else{
					if (is_file('../images/banners/full/'.$_POST['old_filename'])){
						unlink('../images/banners/full/'.$_POST['old_filename']);
						unlink('../images/banners/'.$_POST['old_filename']);
					}
					$img->smartCrop(1280,330);
					$img->save('../images/banners/',$newimage,'jpg');

					unset($img);
					$_SESSION['jcrop'][0]['filename'] = $newimage;
					$_SESSION['jcrop'][0]['img_path'] = '../images/banners/full/';
					$_SESSION['jcrop'][0]['target_path'] = '../images/banners/';
					$_SESSION['jcrop'][0]['from_section'] = $_POST['section'];
					$_SESSION['jcrop'][0]['target_width'] = 1280;
					$_SESSION['jcrop'][0]['target_height'] = 330;

					$imagecrop = true;
				}					
		}
			
		//create safe page name
		if($deleteable == true){
			$pagename = create_pagename($name);
		}
		
		$sql = mysql_query("UPDATE $db SET
			name = '$name',
			page = '$pagename',
			type = '$type',
			page_title = '$page_title',
			sub_page_title = '$sub_page_title',
			meta_title = '$meta_title',
			meta_description = '$meta_description',
			meta_keywords = '$meta_keywords',
			content = '$content',
			sidebar = '$sidebar',
			image = '$newimage',
			url = '$url',
			urltarget = '$urltarget',
			showhide = '$showhide',
			ordering = '$ordering'
			WHERE id = $ID")or die("content error");
		if (!mysql_error()){
			if($imagecrop){
				$_SESSION['jcrop_success'] = "<p>Photo successfully saved!</p>"; 
				echo "<form action='' method='post' id='frm'>";
				echo "<input type='hidden' name='username' value='".$_POST['username']."'/>";
				echo "<input type='hidden' name='password' value='".$_POST['password']."'/>";
				echo "<input type='hidden' name='section' value='Crop Image'/>";
				echo "<script language='JavaScript'>";
					echo "document.getElementById('frm').submit();";
				echo "</script>";
				echo "</form>";
			}
			alert("<p><b>Success!</b> Content has been saved.</p>", true);
			sitemapXML();

			$_POST['id']="";
			$saved = true;
		
			echo "<form action='' method='post'>";
			echo "<input type='submit' name='submit' value='Continue' class='submit' />";
			
			echo "<input type='hidden' name='username' value='" .$username ."' />";
			echo "<input type='hidden' name='password' value='" .$password ."' />";
			echo "<input type='hidden' name='section' value='' />";
			echo "<input type='hidden' name='ID' value='" .$id ."' />";
			echo "</form>";
		}else{
			alert("<p>There was an error saving this page. ".mysql_error()."</p>",false);
		}
	
		
		
	}
	
		
	
	if (!$saved) {
		
		
		// "PARENT" pagenames coming from the maintextnav.php for us to build the full url for statistics
		$p1	 = $_POST['p1'];
		$p2	 = $_POST['p2'];
		$p3	 = $_POST['p3'];
		$p4	 = $_POST['p4'];
		// -->
		
		$query = "SELECT * FROM $db WHERE page='$page'";
		$result = mysql_query($query);
		$num_results = mysql_num_rows($result);
		
		for ($i = 0; $i < $num_results; $i++) {
			$row = mysql_fetch_array($result);
			
			$id = $row['id'];
			$content = $row['content'];
			$tagline = $row['tagline'];
			$pagename = $row['page'];
			$sidebar = $row['sidebar'];
			$image = $row['image'];
			$name = $row['name'];
			$type = $row['type'];
			$url = $row['url'];
			$urltarget = $row['urltarget'];
			$deleteable = $row['deleteable'];
			$showhide = $row['showhide'];
			$ref = $row['reference'];
			$ordering = $row['ordering'];
			
			$page_title = $row['page_title'];
			$sub_page_title = $row['sub_page_title'];
			$meta_title = $row['meta_title'];
			$meta_description = $row['meta_description'];
			$meta_keywords = $row['meta_keywords'];
			
			
			$p = "/";
			if ($p1 != "") { $p .= $p1 ."/"; }
			if ($p2 != "") { $p .= $p2 ."/"; }
			if ($p3 != "") { $p .= $p3 ."/"; }
			if ($p4 != "") { $p .= $p4 ."/"; }
			$p .= $row['page'] ."/";
			
				
			if($checkDB == "nav1"){
				$fullref = (substr($db, 4, 20)) .$ref;
			}else{
				$fullref = (substr($db, 5, 20)) .$ref;
			}
			
		}
		
		if ($type == 1) {
			$find = "/" .$row['page'];
			$replace = $row['url'];
			$p = str_replace($find, $replace, $p);
			$p = str_replace("//", "/", $p);
		}

		show_statistics($p);
		
		// IMPORTANT -->
		important("<p>Page Deletion:</strong> If you delete a page, all subpages under that section will also be deleted. <strong>This action is not undoable.</strong></p>");
		// -->
			
		echo "<form action='' method='post' name='managecontent' enctype='multipart/form-data'>";
		
		echo "<table cellpadding='3' cellspacing='0' border='0' style='width:100%;' class='removepadding'>";
	
		if($deleteable == true){
		echo "<tr>";
		echo "<td style='width:150px;'><p>Button Text:</p></td>";
		echo "<td><input type='text' name='name' value='" .$name ."' class='input'>";
		echo " <sup title='<span>Button Text</span><p>The navigation will automatically be created with this name.</p>'>?</sup></td>";
		echo "</tr>";
		}else{
		echo "<tr>";
		echo "<td style='width:150px;'><p>Button Text:</p></td>";
		echo "<td><input type='text' name='blank' value='" .$name ."' class='input' disabled><input type='hidden' name='name' value='" .$name ."'>";
		echo " <sup title='<span>Button Text</span><p>The navigation will automatically be created with this name.</p>'>?</sup></td>";
		echo "</tr>";	
		}
		
		echo "<tr>";
		echo "<td style='width:150px;'><p>Show/Hide Page:</p></td>";
		echo "<td><select name='showhide' class='select'>";
		echo "<option value='0' ".($showhide==0 ? "selected" : "").">Show</option>";
		echo "<option value='1' ".($showhide==1 ? "selected" : "").">Hide</option>";
		echo "<option value='2' ".($showhide==2 ? "selected" : "").">Disabled</option>";
		echo "</select> <sup title='<span>Show/Hide Page</span><p>Hiding a page will hide it from the navigation menu structure.</p>'>?</sup></td>";
		echo "</tr>";
		
		echo "<tr>";
		echo "<td><p>Numerical Order:</p></td>";
		echo "<td><select name='ordering' class='select'>";
		echo "<option value='101'>--Default--</option>";
		for($n=1; $n<=100; $n++){
			
			if($n == $ordering){
				echo "<option selected >";
			}else{
				echo "<option>";
			}
			echo $n. "</option>";	
		}
		echo "</select> <sup title='<span>Numerical Order</span><p>All pages will be displayed in alphabetical order unless designated here. Pages labeled &lsquo;Default&rsquo; will display AFTER pages with numerical ordering.</p>'>?</sup></td>";
		echo "</tr>";
		
		if ($_POST['p1']==''){
			echo "<tr>";
			if ($image != "") {
				echo "<td><p><a href='../images/banners/" .$image. "' target='_blank'>Banner Image:</a></p></td>";
			} else {
				echo "<td><p>Banner Image:</p></td>";
			}
			echo "<td><input type='file' name='filename' class='input' /><input type='hidden' name='old_filename' value='".$image."'/></td>";
			echo "</tr>";
		}
		
		
		echo "<tr>";
		echo "<td style='width:150px;'><br /><p><b>I Would Like To:</b></p></td>";
		echo "<td><br /><p>";
		if($type == 0){
			echo "<input type='radio' name='type' value='0' checked onclick=\"addContent();\" /> Add content to this page &nbsp;&nbsp;&nbsp;";
			echo "<input type='radio' name='type' value='1' onclick=\"addLink();\" /> Link this page to another page";
		}else{
			echo "<input type='radio' name='type' value='0' onclick=\"addContent();\" /> Add content to this page &nbsp;&nbsp;&nbsp;";
			echo "<input type='radio' name='type' value='1' checked onclick=\"addLink();\" /> Link this page to another page";
		}
		echo "</p><br /></td>";
		echo "</tr>";
		
	
		echo "<tr>";
		echo "<td colspan='2'>";
		
		if($type == 0){
			echo "<div id='addContent'>";
		}else{
			echo "<div id='addContent' style='display:none;'>";
		}
		
			echo "<div class='tabs tab-ui'>";
			echo "<ul>";
				echo "<li><a href='#content'>Page Content</a></li>";
				echo "<li><a href='#seo'>SEO Content</a></li>";
			echo "</ul>";
			
			echo "<div id='seo'>";
			echo "<table cellpadding='3' cellspacing='0' border='0' style='width:100%;' class='removepadding'>";
			
			echo "<tr>";
			echo "<td style='width:150px;'><p>Meta Title:</p></td>";
			echo "<td><input type='text' name='meta_title' value='" .$meta_title ."' class='input'>";
			echo " <sup title='<span>Meta Title</span><p>A keyword-rich page title that will appear at the very top of your browser window.</p>'>?</sup></td>";
			echo "</tr>";
			
			echo "<tr>";
			echo "<td style='width:150px;'><p>Meta Description:</p></td>";
			echo "<td><textarea name='meta_description' class='textarea'>" .$meta_description ."</textarea>";
			echo " <sup title='<span>Meta Description</span><p>A keyword-rich description of the page used for search engine optimization. Will default to global website settings description if left blank.</p>'>?</sup></td>";
			echo "</tr>";
			
			echo "<tr>";
			echo "<td style='width:150px;'><p>Meta Keywords:<br /><span style='font-size:11px; color:#999;'>(separate with a comma)</span></p></td>";
			echo "<td><textarea name='meta_keywords' class='textarea'>" .$meta_keywords ."</textarea>";
			echo " <sup title='<span>Meta Keywords</span><p>Important keywords relevant to the page which will aid in search engine optimization. Will default to global website settings keywords if left blank.</p>'>?</sup></td>";
			echo "</tr>";
			
			echo "</table>";
			echo "</div>";
			
			echo "<div id='content'>";
			echo "<table cellpadding='3' cellspacing='0' border='0' style='width:100%;' class='removepadding'>";
		
			echo "<tr>";
			echo "<td style='width:135px; padding:8px 0 0 15px;'><p>Main Page Title:</p></td>";
			echo "<td><input type='text' name='page_title' value='" .$page_title ."' class='input'>";
			echo " <sup title='<span>Page Title</span><p>The title that will appear as the main heading (h1) of the page.</p>'>?</sup></td>";
			echo "</tr>";

			echo "<tr>";
			echo "<td style='width:135px; padding:8px 0 0 15px;'><p>Sub Page Title:</p></td>";
			echo "<td><input type='text' name='sub_page_title' value='" .$sub_page_title ."' class='input'></td>";
			echo "</tr>";
			
			echo "<tr>";
			echo "<td colspan='2'>";
		
				//echo "<div style='display:block; float:left; width:70%'>";
				if ($content == "") {
					$content = "";	
				}
				
				$content = str_replace ('src="images', 'src="/images', $content);
				$content = str_replace ("src='images", "src='/images", $content);
				
				$oFCKeditor = new FCKeditor('FCKeditor1') ;
				$oFCKeditor->BasePath	= '../fckeditor/';
				$oFCKeditor->Value = $content;
				$oFCKeditor->Config["EditorAreaCSS"] = "../../css/global_stylesheet.css";
				$oFCKeditor->Create();
				
				
				if ($sidebar == "") {
					$sidebar = "";	
				}
				/*echo "</div>";
				echo "<div style='display:block; float:right; width:30%'>";
				$sidebar = str_replace ('src="images', 'src="/images', $sidebar);
				$sidebar = str_replace ("src='images", "src='/images", $sidebar);
				
				$oFCKeditor = new FCKeditor('FCKeditor2') ;
				$oFCKeditor->BasePath	= '../fckeditor/';
				$oFCKeditor->Value = $sidebar;
				$oFCKeditor->Config["EditorAreaCSS"] = "../../css/global_stylesheet.css";
				$oFCKeditor->Create();
				echo "</div>";*/
				
			echo "</td>";
			echo "</tr>";
			echo "</table>";
			echo "</div>";
			
			
			echo "</div>";
		
			echo "</div>";
		
			
	
		if($type == 1){
			echo "<div id='addLink'>";
		}else{
			echo "<div id='addLink' style='display:none;'>";
		}
			echo "<table cellpadding='3' cellspacing='0' border='0' style='width:100%;' class='removepadding'>";
			echo "<tr><td style='width:150px;'><p>Full Page/Site URL:</p></td>";
			echo "<td><input type='text' name='url' value='" .$url ."' class='input'> <span style='font-size:11px; color:#999;'>(ex. http://www.pixelarmy.ca)</span></td></tr>";
			echo "<tr><td style='width:150px;'><p>Open Link In:</p></td>";
			echo "<td><select name='urltarget' class='select'>";
			if($urltarget == 0){echo "<option value='0' selected='selected'>Same Window</option><option value='1'>New Window</option>";}
			else{echo "<option value='0'>Same Window</option><option value='1' selected='selected'>New Window</option>";}
			echo "</select></td></tr>";
			echo "</table>";
		echo "</div>";
		
		
		echo "</td>";
		echo "</tr>";
		
		
		echo "<tr>";
		echo "<td class='footer' style='text-align:left;'>";
		if ($deleteable == "1") {
			echo "<select name='confirm' class='select' style='width: 50px;'><option>No</option><option>Yes</option></select> <input type='submit' name='submit' class='submit' value='Delete' style='padding-left: 5px; padding-right: 5px; width:75px;' />";
		} else {
			echo "<p>&nbsp;</p>";	
		}
		echo "</td>";
		
		echo "<td class='footer'><input type='submit' name='submit' value='Save Changes' class='submit' />";
		
		
		echo "<input type='hidden' name='username' value='" .$username ."' />";
		echo "<input type='hidden' name='password' value='" .$password ."' />";
		echo "<input type='hidden' name='section' value='" .$section ."' />";
		echo "<input type='hidden' name='page' value='" .$page ."' />";
		echo "<input type='hidden' name='pagename' value='" .$pagename ."' />";
		echo "<input type='hidden' name='deleteable' value='" .$deleteable ."' />";
		echo "<input type='hidden' name='old_image' value='" .$image ."' />";
		echo "<input type='hidden' name='database' value='" .$db ."' />";
		echo "<input type='hidden' name='pageref' value='" .$fullref ."' />";
		echo "<input type='hidden' name='ID' value='" .$id ."' />";
		echo "</form>";
		
		echo "</td>";
		echo "</tr>";
		
		echo "<tr><td colspan='2' align='right'>";
		
		echo "<form action='' method='post'>";
		echo "<input type='submit' name='submit' value='Cancel' class='submit' />";
		echo "<input type='hidden' name='username' value='" .$username ."' />";
		echo "<input type='hidden' name='password' value='" .$password ."' />";
		echo "<input type='hidden' name='section' value='Edit Page' />";
		echo "</form>";
		
		echo "</td></tr>";
		
		echo "</table>";
	}
								
}
?>