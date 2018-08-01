<?php

//limit words function
function string_limit_words($string, $word_limit) {
	 $words = explode(' ', $string);
	 return implode(' ', array_slice($words, 0, $word_limit));
}

//encrypt passwords with random salts.
function sha1_salt($string, $salt='') {
    	
	if ($salt!=""){
		//use hash stored in db
		$hash['salt'] = $salt;	
	}else{
		//generate new hash	
		$chars = str_split('~`!@#$%^&*()[]{}-_\/|\'";:,.+=<>?');
	    $keys = array_rand($chars, 10);
		
	    foreach($keys as $key) {
	        $hash['salt'][] = $chars[$key];
	    }
	
	    $hash['salt'] = implode('', $hash['salt']);
	    $hash['salt'] = sha1($hash['salt']);
	}

	//do magic
	$hash['string'] = sha1($hash['salt'].$string.$hash['salt']);
    return $hash;
    
}
function base_url(){

	return Website::$path;
}
function runCurl($url){
	$session = curl_init();

	curl_setopt($session, CURLOPT_URL, $url);
	curl_setopt($session, CURLOPT_HEADER, false);
	curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($session, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($session, CURLOPT_TIMEOUT, 360);
	return curl_exec($session);		
}
function pagination($total, $currentpage, $limit, $url, $sort=""){

	$numlinks  = 9;	//keep it odd
	if ($sort!=""){
		//sort present. 
		$sort = "/".$sort;
	}
	
	$numpages = ceil($total/$limit);
	$content = '<div id="pagination" class="clearFix">';
	
	if ($numpages > 1){
		if ($currentpage!=1){
			//show previous link
			$content .= '<a href="'.$url.($currentpage - 1).$sort.'/">&laquo; Prev</a>';
		}
		if ($numpages>$numlinks){
			$eitherSide = ($numlinks-1)/2;
			$linkStart = (($currentpage-$eitherSide)<1 ? "1" : ($currentpage-$eitherSide));
			$linkEnd = (($currentpage+$eitherSide) >= ($numpages) ? $numpages : ($currentpage+$eitherSide));
			
			if ($linkEnd==$numpages){

				$linkStart -= ($eitherSide-($linkEnd-$currentpage));		
			}
			
			if ($linkStart==1){
				$linkEnd += ($eitherSide-($currentpage-$linkStart));
			}else{
				$content .=  "<span class='pagination_more'>...</span>";	

			}
																  
		}
		for ($i=1; $i<=$numpages; $i++){
			if (($i>=$linkStart && $i<=$linkEnd) || $numpages<=$numlinks){
				if ($i!=$currentpage){
					$content .='<a href="'.$url.$i.$sort.'">'.$i.'</a>';
				}else{
					$content .='<strong>'.$i.'</strong>';
				}
			}
		}
		if ($numpages>$numlinks){
			if ($linkEnd!=$numpages){
				$content .=  "<span class='pagination_more'>...</span>";	
			}
		}
			
		if ($currentpage!=$numpages){
			//show next	link
			$content .='<a href="'.$url.($currentpage + 1).$sort.'">Next &raquo;</a>';
		}
	}
	$content .='</div>';
	return $content;
}

if (!function_exists('alert')){
	function alert($content, $type){
		if ($type=='true'){
			echo "<div class='alert'><h2 class='success'>Success</h2>".$content."</div>";	
		}else if ($type=='false'){
			echo "<div class='alert'><h2 class='error'>Error</h2>".$content."</div>";
		}else if ($type=='info'){
			echo "<div class='alert'><h2 class='info'>Notice</h2>".$content."</div>";
		}
	}
}

function ago($timestamp, $granularity=1, $format='Y-m-d H:i:s'){
	$difference = time() - $timestamp;
	if($difference < 0) return 'just now';
	elseif($difference < 864000){
		$periods = array('week' => 604800,'day' => 86400,'hour' => 3600,'minute' => 60,'second' => 1);
		$output = '';
		foreach($periods as $key => $value){
			if($difference >= $value){
				$time = round($difference / $value);
				$difference %= $value;
				$output .= ($output ? ' ' : '').$time.' ';
				$output .= (($time > 1) ? $key.'s' : $key);
				$granularity--;
			}
			if($granularity == 0) break;
		}
		return ($output ? $output : '0 seconds').' ago';
	}
	else return "on " .date("F j, Y", $timestamp);
}


function generatePasswordToken(){
	$chars = str_split('~`!@#$%^&*()[]{}-_\/|\'";:,.+=<>?');
	$keys = array_rand($chars,10);
	
	foreach($keys as $key){
		$token .=$chars[$key];
	}
	$token = sha1($token);
	return $token;
}

//Searches for variable define URL segments. (eg. https://www.google.com/searchterm/page:1)
//		$var = "page:";
//		$default		//default value if doesn't find anything in array
//		$array = $segments;
function segmentVar($var, $array, $default){
	$size = strlen($var);
	$value = array();
	$value['value'] = $default;
	$value['segment'] = count($array);
	foreach($array AS $key => $data){
		if (substr($data,0,$size) == $var){
			$value['value'] = substr($data,$size,strlen($data));	
			$value['segment'] = $key;
		}
	}
	
	return $value;
}	

function mysql_escape(){
	if (count($_REQUEST)>0){
		
		
		foreach($_REQUEST AS $key => $data){
			if (is_array($_REQUEST[$key])){
				foreach($_REQUEST[$key] AS $iKey => $iData){
					if (get_magic_quotes_gpc()) {
						$_REQUEST[$key][$iKey] = stripslashes($_REQUEST[$key][$iKey]);
					}
					$_REQUEST[$key][$iKey] = $this->db->escape($iData);	
				}
			}else{		
				if (get_magic_quotes_gpc()) {
					$_REQUEST[$key] = stripslashes($_REQUEST[$key]);
				}
				$_REQUEST[$key] = $this->db->escape($data);	
			}
		}
	}
}

function timeDropdown($cur){
	$times=array();

  $iDateFrom=strtotime("6:00 am");
  $iDateTo=strtotime("5:30 + 1day");

  if ($iDateTo>=$iDateFrom) {
    array_push($times,date('g:i a',$iDateFrom)); // first entry

    while ($iDateFrom<$iDateTo) {
      $iDateFrom+=1800; // add 24 hours
      array_push($times,date('g:i a',$iDateFrom));
    }
  }
  
  
	$dropdown ='<option value=""></option>';
	foreach($times AS $time){

		$dropdown .='<option value="'.$time.'" ';
			if ($cur == $time){
				$dropdown .=" selected ='selected'";	
			}
		$dropdown .='>'.$time.'</option>';
	}
	echo $dropdown;
}

function create_pagename($name){
	$pagename = html_entity_decode($name);
	$pagename = str_replace("&rsquo;", "", strtolower($pagename));
	$pagename = str_replace(" ", "-", strtolower($pagename));
	$pagename = str_replace("/", "-", strtolower($pagename));
	$pagename = str_replace("\\", "-", strtolower($pagename));
	$pagename = str_replace("_", "-", strtolower($pagename));
	$pagename = preg_replace('/[^a-zA-Z0-9-]/s', '', $pagename);
	$pagename = str_replace("---", "-", strtolower($pagename));
	$pagename = str_replace("--", "-", strtolower($pagename));
	
	return $pagename;
		
}
function url_friendly_filename($name){
	$pagename = html_entity_decode($name);
	$pagename = str_replace("&rsquo;", "", strtolower($pagename));
	$pagename = str_replace(" ", "-", strtolower($pagename));
	$pagename = str_replace("/", "-", strtolower($pagename));
	$pagename = str_replace("\\", "-", strtolower($pagename));
	$pagename = str_replace("_", "-", strtolower($pagename));
	$pagename = preg_replace('/[^a-zA-Z0-9-.]/s', '', $pagename);
	$pagename = str_replace("---", "-", strtolower($pagename));
	$pagename = str_replace("--", "-", strtolower($pagename));
	
	return $pagename;
		
}

function delete_directory($dirname) {
	if (is_dir($dirname))
		$dir_handle = opendir($dirname);
	if (!$dir_handle)
		return false;
	while($file = readdir($dir_handle)) {
		if ($file != "." && $file != "..") {
			if (!is_dir($dirname."/".$file))
			unlink($dirname."/".$file);
			else
			delete_directory($dirname.'/'.$file);    
		}
	}
	closedir($dir_handle);
	rmdir($dirname);
}
//get users real ip address (with forwarded)
function get_real_ip_address(){
	if (!empty($_SERVER["HTTP_CLIENT_IP"])){
		$ip = $_SERVER["HTTP_CLIENT_IP"];
	}else if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
		$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
	}else{
	 $ip = $_SERVER["REMOTE_ADDR"];
	}
	return $ip;
}

//simple function to take a file, and render it into a string. 
//used be template engine to include files into certain regions of webpages
function dynamicInclude($file){
	if (is_file($file)){
		ob_start();
			require('config.php');		//grab variables
			require($file);

		$return = ob_get_clean();
		ob_end_clean();
		return $return;
	}
}

?>