<?php
//Global Website Settings
$path = '/';										//default '/'

//Database connectivity
$db_host 		= 'mysql51-012.wc1.ord1.stabletransit.com'; 	//default: localhost
$db_username 	= '814205_z4zzl3us3';
$db_password 	= 'iiFb0ACvOIzLO77G6YyvJg';
$db_schema		= '814205_z4zzl3db';
$db_debug 		= true;											//whether to profile database queries

//Timezone Settings
$timezone = 'America/Edmonton';


//Template Settings
//Templates that will be created globally are main and print. Pass in new ones to overwrite
$templates = array(

	'page' => array('title','maincontent','subcontent','includes', 'content','sidebar'),
	'main' => array('title','maincontent','subcontent','includes', 'content'),
	'2_col' => array('includes', 'sidebar','content')

);


?>