<?php 
// function __autoload($class_name) {
// 	include 'includes/classes/'.strtolower($class_name) . '.class.php';

// }
class Website{
	public static $instance;
	public static $path;
	var $globals;
	var $pages;
	var $page;
	var $segments;
	var $authman;
	var $imageman;
	var $geoman;
	var $templateman;
	var $emailman;
	var $meta;
	var $location;
	var $states;
	var $countries;
	var $url = 'http://';							//base url of the site. eg. http://www.google.com
	var $sitename ='';		
	function __construct(){
		self::$instance = &$this;

		require ('includes/config.php');
		self::$path = $path;
		$this->path = $path;
		$this->debug = $db_debug;
		
		date_default_timezone_set($timezone);

		require_once('includes/functions.php');

		//clean input 
		if (isset($_POST)){
			$_POST = $this->cleanArray($_POST);
		}
		//database class	
		require_once('includes/classes/databaseman.class.php'); 		
		$this->db = new Databaseman($db_host,$db_username,$db_password,$db_schema,$db_debug);
		$this->segments = $this->prepareSegments($this->path,$this->db->mysqli);		//scrubs segments, removes dev segments.
		
		//authorization class		
		require_once('includes/classes/authman.class.php');		
		$this->authman = new Authman($this->db);
		
		//template definition
		require_once ('includes/classes/imageman.class.php');
		$this->imageman = new Imageman();
		
		//create template engine		
		require_once ('includes/classes/templateman.class.php');
		$this->templateman = new Templateman('main');

		//create the template files
		$this->templateman->createTemplate('main',array('content','sidebar'));
		$this->templateman->createTemplate('print',array('content'));
		foreach($templates as $key=>$data){
			$this->templateman->createTemplate($key,$data);
		}
		if (isset($_GET['print'])){
			$this->templateman->setTemplate('print');
		}

		$this->prepare_globals();

		$this->prepare_pages();	

		//generate fkey for post security
		if (@$_COOKIE['fkey']==''){
			$this->fkey = $this->authman->_generate_remeid('32');
			setcookie('fkey',$this->fkey,time()+60*60*24,'/');
		}else{
			$this->fkey = $_COOKIE['fkey'];	
		}

		//***************************
		//	Various Global Variables
		//***************************
		$this->states['CA'] = array(
			"AB"=>"Alberta", 
			"BC"=>"British Columbia",
			"MB"=>"Manitoba", 
			"NB"=>"New Brunswick",
			"NL"=>"Newfoundland and Labrador", 
			"NT"=>"Northwest Territories", 
			"NS"=>"Nova Scotia", 
			"NU"=>"Nunavut",
			"ON"=>"Ontario",   
			"PE"=>"Prince Edward Island", 
			"QC"=>"Quebec", 
			"SK"=>"Saskatchewan",    
			"YT"=>"Yukon Territory");
		
		$this->states['US'] = array(
			'AL'=>"Alabama",  
			'AK'=>"Alaska",  
			'AZ'=>"Arizona",  
			'AR'=>"Arkansas",  
			'CA'=>"California",  
			'CO'=>"Colorado",  
			'CT'=>"Connecticut",  
			'DE'=>"Delaware",  
			'DC'=>"District Of Columbia",  
			'FL'=>"Florida",  
			'GA'=>"Georgia",  
			'HI'=>"Hawaii",  
			'ID'=>"Idaho",  
			'IL'=>"Illinois",  
			'IN'=>"Indiana",  
			'IA'=>"Iowa",  
			'KS'=>"Kansas",  
			'KY'=>"Kentucky",  
			'LA'=>"Louisiana",  
			'ME'=>"Maine",  
			'MD'=>"Maryland",  
			'MA'=>"Massachusetts",  
			'MI'=>"Michigan",  
			'MN'=>"Minnesota",  
			'MS'=>"Mississippi",  
			'MO'=>"Missouri",  
			'MT'=>"Montana",
			'NE'=>"Nebraska",
			'NV'=>"Nevada",
			'NH'=>"New Hampshire",
			'NJ'=>"New Jersey",
			'NM'=>"New Mexico",
			'NY'=>"New York",
			'NC'=>"North Carolina",
			'ND'=>"North Dakota",
			'OH'=>"Ohio",  
			'OK'=>"Oklahoma",  
			'OR'=>"Oregon",  
			'PA'=>"Pennsylvania",  
			'RI'=>"Rhode Island",  
			'SC'=>"South Carolina",  
			'SD'=>"South Dakota",
			'TN'=>"Tennessee",  
			'TX'=>"Texas",  
			'UT'=>"Utah",  
			'VT'=>"Vermont",  
			'VA'=>"Virginia",  
			'WA'=>"Washington",  
			'WV'=>"West Virginia",  
			'WI'=>"Wisconsin",  
			'WY'=>"Wyoming");
		$this->countries['CA'] = "Canada";
		$this->countries['US'] = "United States";
	}

 	function cleanArray($arr){
		$allowed_tags = '<b><a><i><u><strike><img><hr><a><br>';
		foreach($arr AS $key=>$data){

			//strip line breaks - May need to take out depending on desired result
			if (!is_array($arr[$key])){
				if ($key=='thread_message'){
					if (isset($this->db)){
						$arr[$key] = $this->db->escape(strip_tags(trim($arr[$key]),$allowed_tags));
					}else{
						$arr[$key] = strip_tags(trim($arr[$key]),$allowed_tags);
					}
				}else{
					if (isset($this->db)){
						$arr[$key] = $this->db->escape(strip_tags(trim($arr[$key]),'<br>'));
					}else{
						$arr[$key] = strip_tags(trim($arr[$key]),'<br>');
					}
				}

			}else{

	 			$arr[$key] = $this->cleanArray($arr[$key]);

	 		}

	 	}

	 	return $arr;
 	}

	public function get_instance(){
		return self::$instance;		
	}
	function fetchControllers($path){
		$segments = Website::prepareSegments($path);
		require('includes/config.php');
		require('includes/database.php');
		$controllers = array();
		foreach($segments AS $key => $data){
			$result = mysql_query("SELECT controller FROM nav1 WHERE page = '".$data."'");
			if (mysql_num_rows($result)){
				$controllers[$key] = mysql_result($result,0,'controller');
			}else{
				$controllers[$key] = $data;
			}
		}
		return $controllers;

	}
	function prepareSegments($path = NULL, $mysqli=''){
		$segments = $_SERVER['REQUEST_URI'];
		//$this->segments = split('/',$this->segments);
		$segments = explode('/',$segments);

		//scrub segments clean
		if ($mysqli!=''){
			foreach($segments as $key=>$data){
				if (mysqli_real_escape_string($mysqli,$data)!=null){
					$segments[$key] = mysqli_real_escape_string($mysqli,$data);
				}
			}
		}else{
			foreach($segments AS $key=>$data){
				if (mysql_escape_string($data)!=null){
					$segments[$key] = mysql_escape_string($data);
				}
			}
		}
		if ($segments[count($segments)-1]==""){
			unset($segments[count($segments)-1]);	
		}

		if (isset($this) && is_null($path)){
			$path = $this->path;
		}

		$devSegments = explode('/',$path);
		$queryprofile = array();
		foreach($devSegments AS $devseg){

			if (in_array($devseg,$segments) && $devseg!=""){
		
				$i = array_search($devseg, $segments);
				unset($segments[$i]);
			}
		}
		
		//reorder segments (ignore the segments included in the path ones)
		$segments = array_values($segments);
		$segments[count($segments)] = '';
		return $segments;
		
	}
	
	private function prepare_globals(){
		
		//populate global settings			
		$this->globals = $this->db->query("SELECT * FROM global_settings",MYSQL_ASSOC);
		
	}
	public static function createController($name){
		return ucfirst(str_replace('-','_',$name));
	}
	private function prepare_pages(){
		
		//get page content information
		foreach($this->segments AS $key=>$data){
			if ($key>0){

				$query = "SELECT * FROM nav".$key;
				for ($i=1; $i<$key; $i++){
					if ($i==1){
						$query .='_';	
					}
					$query .= @$this->pages[$i]['reference'];
				}
				if ($key==1 && $data==''){
					$data = 'home';
				}
				$query .=" WHERE page = '".$data."' AND showhide<>2";
				
				if ($result = $this->db->query($query)){
				
					if ($result->num_rows){
						$this->pages[$key] = $result->fetch_assoc();
					}else{
						//invalid segment detected, may not fall into normal page paradigm
						$detected_404 = $key;
						break;	
					}			
				}
			}
		}		
				
		if (!isset($this->segments[1])){
			$result = $this->db->query("SELECT * FROM nav1 WHERE page = 'home'");
			$this->pages[1] = $result->fetch_assoc();
		}
		$this->setTitle($this->globals['meta_title']);

		if (count($this->pages)){
			foreach($this->pages AS $key =>  $data){
				$this->setTitle($data['meta_title']);
			}
		}
		$this->page = @end($this->pages);
		@$this->setDescription($this->page['description']);
		@$this->setKeywords($this->page['keywords']);
	}
	
	public function index(){
		//shows home page
		if($this->segments[1]!=''){
			if (isset($this->pages[count($this->segments)-2])){
				$this->basic_page();
			}else{
				$this->_404();
			}
		}else{
			$this->templateman->setTemplate('main');
			$this->templateman->write('title',$this->page['page_title']);
			$this->templateman->write('content',$this->page['content']);
			$this->templateman->render($this);
		}
		
	}

	function get_flash_message($type=''){
		
		$message = '';
		if ($type=='success' || $type==''){
			if (isset($_SESSION['flash']['success'])){
				$message = "<div class='success'>Success!</div><div class='alert'><p>".$_SESSION['flash']['success']."</p></div>";
			}
		}

		if ($type=='error' || $type==''){
			if (isset($_SESSION['flash']['error'])){
				$message .= "<div class='error'>Error</div><div class='alert'><p>".$_SESSION['flash']['error']."</p></div>";
			}
		}

		if ($type=='info' || $type==''){
			if (isset($_SESSION['flash']['info'])){
				$message .= "<div class='info'>Info</div><div class='alert'><p>".$_SESSION['flash']['info']."</p></div>";
			}
		}

		unset($_SESSION['flash']);
		return $message;

	}

	//sets flash message. These will be unset as soon as they are displayed initially, or on next page load. (there is a bit of a grace period incase there is a header redirect before showing.)
	function set_flash_message($message,$type){

		switch($type){
			case 'success':
			case 'error':
			case 'info':
				$_SESSION['flash'][$type] = $message;
			break;
			default:
				return false;
			break;	
		}
		unset($_SESSION['flash_count']);

	}
	public function basic_page(){
		if (isset($_GET['prettyPhoto'])){
			//prettyphoto iframe, just show content
			$this->templateman->createTemplate('prettyphoto',array('content'));
			$this->templateman->setTemplate('prettyphoto');
		}else{
			$this->templateman->setTemplate('page');
			$this->templateman->write('content', $this->get_flash_message());
		}

		if (@$this->segments[1]==''){
			$this->templateman->setTemplate('main');

		}
		$this->templateman->write('title',$this->page['page_title']);
		$this->templateman->write('content',$this->page['content']);	

		if ($result = $this->db->query("SELECT * FROM nav2_".$this->page['reference']." WHERE showhide='0' ORDER BY ordering")){
			if ($result->num_rows){
				while($row = $result->fetch_assoc()){
					$subpages[] = $row;
				}

				$this->templateman->write_file('subcontent','views/sub_pages.php',$subpages);
			}
		}
		$this->templateman->render();
	}	
	
	//404 error
	public function _404(){
		header("HTTP/1.0 404 Not Found");
		$this->templateman->setTemplate('page');
		$this->templateman->write('content','<h1>Oh no! Page Not Found...</h1>');
		$this->templateman->write('content','<p>We couldn\'t find the page you requested. Feel free to visit <a href="/about-us/">About Us</a> to learn more about who we are.</p>');
		$this->templateman->render();
	}

	public static function site_url($url){
		return $url;
	}
	public function setTitle($title,$full = false){
			if ($full){
				$this->meta['title'] = $title;
			}else{
				if ($this->meta['title']!=''){
					$this->meta['title'] = $title .' - '. $this->meta['title'];
				}else{
					$this->meta['title'] = $title;
				}
			}
		
	}
	public function setDescription($description){
		$this->meta['description'] = $description;
	}
	public function setSiteDescription($d){
		$this->meta['site_description']= $d;
	}
	public function setKeywords($keys){
		$this->meta['keywords'] = $keys;
	}
	public function getTitle(){
		if ($this->meta['title']==''){
			return $this->globals['meta_title'];
		}else{
			return $this->meta['title'];
		}
	}
	public function getDescription(){
		if ($this->meta['description']==''){
			return $this->globals['meta_description'];
		}else{
			return $this->meta['description'];
			
		}	
	}
	public function getKeywords(){
		if ($this->meta['keywords'] ==''){
			return $this->globals['meta_keywords'];
		}else{
			return $this->meta['keywords'];
		}
	}
	public function getSiteDescription(){
		return $this->globals['meta_description'];
	}
	public function __destruct(){
		if (isset($_SESSION['flash'])){
			foreach($_SESSION['flash'] AS $key=>$data){
				if (intval($_SESSION['flash_count'][$key])>2){
					unset($_SESSION['flash'][$key]);
					unset($_SESSION['flash_count'][$key]);
				}else{
					$_SESSION['flash_count'][$key]++;
				}
			}
		}
	}
}

?>