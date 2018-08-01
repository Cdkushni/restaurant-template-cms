<?php 

class Templateman {

	var $dir = 'views/templates/'; 					//Director where the template files are stored. Default: pages/templates/ 
	var $templates = array();	//holds array of template files. 
	var $activeTemplate; 		//holds the active template file set by setTemplate method.
	var $content = array();		//holds all the content that will be 
	//******************************************************
 	//-  __construct  --------------------------------------
	//------------------------------------------------------
	//-  Allows you to set a default template, as well
	//-		as the path to your template directory
	//-
	//-  variables
	//-		default	- name of the default template
	//-		path	- path to template directory
	//-
	//-	 return: Will echo the page out directly. 
	//******************************************************
	function __construct($default='',$path='views/templates/') {
		if ($default!=''){
			$this->activeTemplate = $default;		
		}
		
		if (is_dir($path)){
			$this->dir = $path;
		}else{
			return array('errors'=>true,'message'=>'Directory does not exist');
		}
	}
	
	//******************************************************
 	//-  createTemplate  -----------------------------------
	//------------------------------------------------------
	//-  creates a new template with the desired regions
	//-
	//-  variables
	//-		name	- name of template
	//-		regions	- list of available regions
	//-
	//-	 return: Will echo the page out directly. 
	//******************************************************
	function createTemplate($name,$regions = array()){
	
		$this->templates[$name] = $regions;
		
	}
	
	//******************************************************
 	//-  setTemplate  --------------------------------------
	//------------------------------------------------------
	//-  overrides default template, and sets the active
	//-  template to that specified
	//-
	//-	 variables
	//-		template	- the desired template
	//-
	//-	 return: true or error array
	//******************************************************
	function setTemplate($template){
		if (isset($this->templates[$template])){
			//good
			$this->activeTemplate = $template;	
			return true;			
		}else{
			return array('errors' =>true,'message' =>'Template does not exist. Please create template first.');
		}
	}
	
	//******************************************************
 	//-  write  --------------------------------------------
	//------------------------------------------------------
	//-  writes content to the region specified.
	//-
	//-  variables
	//- 	region	- the region to write content to
	//-		content	- desired content
	//-
	//-	 return: true or error array
	//******************************************************
	function write($region,$content){
		if (in_array($region,$this->templates[$this->activeTemplate])){
			//valid template region
			@$this->content[$region] .= $content;
			return true;
		}else{
			return array('errors' =>true, 'message' => 'Region does not exist in template: '.$this->activeTemplate);	
		}
	}
	
	//******************************************************
 	//-  write_file  ---------------------------------------
	//------------------------------------------------------
	//-  writes a file to the region specified.
	//-
	//-  variables
	//- 	region	- the region to write content to
	//-		file	- desired file content
	//-		vars	- any variables that page is going to
	//-					need
	//-
	//-	 return: true or error array
	//******************************************************
	function write_file($region, $file, &$vars=''){

		if (is_file($file)){
			
			ob_start();
			
			if ($vars!='')
				extract((array) $vars, EXTR_REFS);		//extract variables ($array['varname'] becomes->  $varname);

			require($file);
			$this->content[$region] .= ob_get_clean();

			return true;
			
		}else{
			return array('errors' => true, 'message' => 'File does not exist: '.$this->activeTemplate);	
		}
	}
	
	//******************************************************
 	//-  render  -------------------------------------------
	//------------------------------------------------------
	//-  renders the active template using all supplied
	//-	 content
	//-
	//-	 return: Will echo the page out directly. 
	//-
	//-  Note: use in conjuction with locally declared
	//-        function 'dynamicInclude()' to include
	//-        files that require processing beforehand
	//******************************************************
	function render($vars=''){
		//render the template
		ob_start();
		if ($vars!='')
			extract($vars);
		
		if (!is_null($this->content))
			extract($this->content, EXTR_PREFIX_ALL,'template');
		
		require($this->dir.$this->activeTemplate.'.tpl.php');

		ob_end_flush();
			
			// if ($_SERVER['REMOTE_ADDR']=='70.74.186.197'){
			// 	include ('includes/debug.php');
			// }
		return true;
	}
}
?>