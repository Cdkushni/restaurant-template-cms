<?php

if ($section == "Add Sub Page") {
	
	$saved = false;
	
	
	if ($submit == "Save") {
		
		$reference = $_POST['reference'];
		$db = "nav2_" .$reference;
		
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
		$sidebar = str_replace('color: rgb(82, 119, 128); text-decoration: underline;', '', $sidebar);*/
		
		$name = str_replace("'", "&rsquo;", stripslashes($_POST['name']));
		$type = $_POST['type'];
		$url = str_replace("'", "&rsquo;", stripslashes($_POST['url']));
		$urltarget = $_POST['urltarget'];
		$showhide = $_POST['showhide'];
		$deleteable = 1;
		
		$page_title = str_replace("'", "&rsquo;", stripslashes($_POST['page_title']));
		$meta_title = str_replace("'", "&rsquo;", stripslashes($_POST['meta_title']));
		$meta_description = str_replace("'", "&rsquo;", stripslashes($_POST['meta_description']));
		$meta_keywords = str_replace("'", "&rsquo;", stripslashes($_POST['meta_keywords']));
		
		if(trim($page_title) == ""){ $page_title = $name; }
		if(trim($meta_title) == ""){ $meta_title = $name; }
		
		/*$image = $_FILES['image'];
		if ($image['name'] != "") {
			uploadImage("../path/", $image, "jpg", 300, 300);
			uploadImage("../path/thumbs/", $image, "jpg", 150, 150);
			$image = $image['name'];
		}else{
			$image = "";	
		}*/
		
		//create safe page name
		$pagename = create_pagename($name);
		
		
		//if db doesn't exist, create it first
		$queryTEST = mysql_query("SELECT * FROM $db");
		if(!$queryTEST){
		
			$tbl = 'CREATE TABLE nav2_'.$reference.'( '.
			 'id INT NOT NULL AUTO_INCREMENT, '.
			 'name VARCHAR(200) NOT NULL, '.
			 'page VARCHAR(200) NOT NULL, '.
			 'type INT NOT NULL, '.
			 'page_title VARCHAR(200) NOT NULL, '.
			 'sub_page_title VARCHAR(200) NOT NULL, '.
			 'meta_title VARCHAR(200) NOT NULL, '.
			 'meta_description TEXT NOT NULL, '.
			 'meta_keywords TEXT NOT NULL, '.
			 'content MEDIUMTEXT NOT NULL, '.
			 'sidebar MEDIUMTEXT NOT NULL, '.
			 'image VARCHAR(200) NOT NULL, '.
			 'url VARCHAR(200) NOT NULL, '.
			 'urltarget INT NOT NULL, '.
			 'showhide INT NOT NULL, '.
			 'deleteable INT NOT NULL, '.
			 'reference INT NOT NULL, '.
			 'ordering INT NOT NULL DEFAULT 101, '.
			 'last_modified TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, '.
			 'PRIMARY KEY(id))';
			$createtbl = mysql_query($tbl)or die("table error");
		
		}
		
		//now insert into db
		$query = "SELECT * FROM $db";
		$result = mysql_query($query);
		$num_results = mysql_num_rows($result);
		$num_insert = $num_results+1;
		
		$refid = create_reference($db,2001,2999);
		
		$sql = "INSERT INTO $db (id, name, page, type, page_title, sub_page_title, meta_title, meta_description, meta_keywords, content, sidebar, image, url, urltarget, showhide, deleteable, reference) VALUES ('$num_insert', '$name', '$pagename', '$type', '$page_title', '$sub_page_title', '$meta_title', '$meta_description', '$meta_keywords', '$content', '$sidebar', '$image', '$url', '$urltarget', '$showhide', '$deleteable', '$refid');";
		$result = mysql_query($sql) or die ("Database Error.");
		
		sitemapXML();
		
		alert("<p><b>Success!</b> New Page was successfully created.</p>", true);
		$saved = true;
		
		echo "<form action='' method='post'>";
		echo "<input type='submit' name='submit' value='Continue' class='submit' />";
		
		echo "<input type='hidden' name='username' value='" .$username ."' />";
		echo "<input type='hidden' name='password' value='" .$password ."' />";
		echo "<input type='hidden' name='section' value='' />";
		echo "<input type='hidden' name='ID' value='" .$id ."' />";
		echo "</form>";
		
	}
			
	
	
	
	if (!$saved) {
		
		// IMPORTANT -->
		//important("<p><b>Button Text</b>: The navigation will automatically be created with this name.</p>");
		// -->
	
	
		echo "<form action='' method='post' name='addpage' enctype='multipart/form-data'>";
		
		echo "<table cellpadding='3' cellspacing='0' border='0' style='width:100%;' class='removepadding'>";
		
		echo "<tr>";
		echo "<td style='width:150px;'><p>Button Text:</p></td>";
		echo "<td><input type='text' name='name' class='input' value='" .$name ."' /> <sup title='<span>Button Text</span><p>The navigation will automatically be created with this name.</p>'>?</sup></td>";
		echo "</tr>";
		
		echo "<tr>";
		echo "<td style='width:150px;'><p>Parent Page:</p></td>";
		echo "<td><select name='reference' class='select'>";
		
		$lvlquery = mysql_query("SELECT * FROM nav1 WHERE reference > 1001 && reference <> '1007' && reference <> '1008'");
		while($lvl = mysql_fetch_array($lvlquery)){
			$lvlname = $lvl['name'];
			$lvlref = $lvl['reference'];
			
			echo "<option value='" .$lvlref. "'>" .$lvlname. "</option>";
		}
		
		echo "</select> <sup title='<span>Parent Page</span><p>The page you are creating will appear within the Parent Page in the navigation structure (submenu).</p>'>?</sup></td>";
		echo "</tr>";
		
		echo "<tr>";
		echo "<td style='width:150px;'><p>Show/Hide Page:</p></td>";
		echo "<td><select name='showhide' class='select'>";
		echo "<option value='0' selected='selected'>Show</option><option value='1'>Hide</option>";
		echo "</select> <sup title='<span>Show/Hide Page</span><p>Hiding a page will hide it from the navigation menu structure.</p>'>?</sup></td>";
		echo "</tr>";
		
		/*echo "<tr>";
		echo "<td><p>Banner Image:</p></td>";
		echo "<td><input type='file' name='image' class='input' /></td>";
		echo "</tr>";*/
		
			
		echo "<tr>";
		echo "<td style='width:150px;'><br /><p><b>I Would Like To:</b></p></td>";
		echo "<td><br /><p>";
	
		echo "<input type='radio' name='type' value='0' checked onclick=\"addContent();\" /> Add content to this page &nbsp;&nbsp;&nbsp;";
		echo "<input type='radio' name='type' value='1' onclick=\"addLink();\" /> Link this page to another page";
	
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
			echo " <sup title='<span>Page Title</span><p>The title that will appear as the main heading (h1) of the page.</p>'>?</sup><br /><br /></td>";
			echo "</tr>";

			echo "<tr>";
			echo "<td style='width:135px; padding:8px 0 0 15px;'><p>Sub Page Title:</p></td>";
			echo "<td><input type='text' name='sub_page_title' value='" .$sub_page_title ."' class='input'><br /><br /></td>";
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
		echo "<td colspan='2' class='footer'><input type='submit' name='submit' value='Save' class='submit' /></td>";
		echo "</tr>";
		
		echo "</table>";
		
		
		echo "<input type='hidden' name='username' value='" .$username ."' />";
		echo "<input type='hidden' name='password' value='" .$password ."' />";
		echo "<input type='hidden' name='section' value='" .$section ."' />";
		echo "<input type='hidden' name='logged_in' value='true' />";
		echo "</form>";
	}
}

?>