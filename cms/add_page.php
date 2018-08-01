<?php

if ($section == "Add Main Page") {
	
	$db = "nav1";
	$saved = false;
	
	if ($submit == "Save") {
		
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
		$reference = create_reference($db,1001,1999);
		
		$page_title = str_replace("'", "&rsquo;", stripslashes($_POST['page_title']));
		$sub_page_title = str_replace("'","&rsquo;",stripslashes($_POST['sub_page_title']));
		$meta_title = str_replace("'", "&rsquo;", stripslashes($_POST['meta_title']));
		$meta_description = str_replace("'", "&rsquo;", stripslashes($_POST['meta_description']));
		$meta_keywords = str_replace("'", "&rsquo;", stripslashes($_POST['meta_keywords']));
		
		if(trim($page_title) == ""){ $page_title = $name; }
		if(trim($meta_title) == ""){ $meta_title = $name; }
		
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
				require_once('../includes/classes/imageman.class.php');
				$img = new Imageman();
				if (!$img->load('../images/banners/full/'.$newimage)){
					$errors = true;
					alert('<p>Couldnt save banner image.</p>', false);
				}else{

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
		$pagename = create_pagename($name);
		
		
		$query = "SELECT * FROM $db";
		$result = mysql_query($query);
		$num_results = mysql_num_rows($result);
		$num_insert = $num_results+1;
		
		$sql = "INSERT INTO $db (id, name, page, controller, type, page_title, sub_page_title, meta_title, meta_description, meta_keywords, content, sidebar, image, url, urltarget, showhide, deleteable, reference) VALUES ('$num_insert', '$name', '$pagename', '$pagename', '$type', '$page_title','$sub_page_title', '$meta_title', '$meta_description', '$meta_keywords', '$content', '$sidebar', '$newimage', '$url', '$urltarget', '$showhide', '$deleteable', '$reference');";
		$result = mysql_query($sql) or die ("Database Error.");
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
			alert("<p><b>Success!</b> New Page was successfully created.</p>", true);
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
		echo "<td style='width:150px;'><p>Show/Hide Page:</p></td>";
		echo "<td><select name='showhide' class='select'>";
		echo "<option value='0' selected='selected'>Show</option><option value='1'>Hide</option>";
		echo "</select>  <sup title='<span>Show/Hide Page</span><p>Hiding a page will hide it from the navigation menu structure.</p>'>?</sup></td>";
		echo "</tr>";
		
		echo "<tr>";
		echo "<td><p>Banner Image:</p></td>";
		echo "<td><input type='file' name='filename' class='input' /></td>";
		echo "</tr>";
		
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