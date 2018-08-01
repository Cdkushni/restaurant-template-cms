<?php 

	$name = trim( explode( '?', $_SERVER['REQUEST_URI'], 2 )[0], '/' );
	$name = str_replace( '.', '', $name );
	$file = '404';

	if ( $name !== 'index' && is_file( __dir__ . "/$name.php" ) ) {
		$file = $name;
	} elseif (!$name) {
		$file = 'home';
	}

	$dir = dirname(__FILE__);
	require $dir . "/$file.php";

	session_start();
	require_once ('includes/config.php');
	require_once ('includes/classes/website.class.php');
	//create a website object with all the config variables. 
	//$controller = new Website();
	
	$controllers = Website::fetchControllers($path);
	//navigation routing
	if ($controllers[1]==''){
		$controllers[1]='home';
	}
		//check to see if controller exists in controllers folder.
		if (file_exists('controllers/'.$controllers[1].'.php')){
			//include controller file

			require_once('controllers/'.$controllers[1].'.php');
			$name = Website::createController($controllers[1]);

			$controller = new $name;
			//checks to see if secondary segment is present
			if (@$controllers[2]!=''){				
				//check to see if method exists in controller - basically determines if the segment is a dynamic page or not
				$func = str_replace('-','_',$controllers[2]);
				if (is_callable(array($controller,$func))){				
					//function exists, call method

					call_user_func(array($controller,$func));

				}else{

					//second segment couldn't be found, check to see if page exists in db
					if (isset($controller->pages[2])){
						
						//page exists in DB, show basic page. 
						$controller->basic_page();	
						
					}else{
						//page wasn't found in the DB, redirect to controllers index class
						$controller->index();						
					}
				}
			}else{
				
				//call index of controller
				$controller->index();	
			}		
		}else if (is_dir('controllers/'.$controllers[1])){
				//directory exists
			if (file_exists('controllers/'.$controllers[1].'/'.$controllers[2].'.php')){
				//file exists, use it
				require_once('controllers/'.$controllers[1].'/'.$controllers[2].'.php');
				
				$name = Website::createController($controllers[2]);

				$controller = new $name;

				if ($controllers[3]!=''){
					$fun = str_replace('-','_',$controllers[3]);
					if (is_callable(array($controller,$func))){
						call_user_func(array($controller,$func));
					}else{
						if (isset($controller->pages[3])){
							$controller->basic_page();
						}else{
							$controller->_404();
						}
					}
				}else{
					$controller->index();
				}
			}else{
				//folder existed, yet the controller didn't, try to load initial page
				$controller = new Website();
				if (isset($controller->pages[2])){
					$controller->basic_page();
				}else if (isset($controller->pages[1])){
					$controller->basic_page();
				}else{
					$controller->_404();
				}
			}
		}else{
			//create basic webpage - no controller found
			$controller = new Website();
			$controller->index();
		}
	if ($_SERVER['REMOTE_ADDR']=='' && @$controller->segments[1]!='ajax' && $controller->debug){	
		include ('includes/debug.php');	
	}
?>