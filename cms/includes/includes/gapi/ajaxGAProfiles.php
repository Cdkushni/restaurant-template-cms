<?php 

	require 'gapi.class.php';
	
	$ga_email = $_POST['email'];
	$ga_password = $_POST['pass'];

	try{
		$ga = new gapi($ga_email,$ga_password);
			/* Get Account Information */
		$ga->requestAccountData();
		$newarray = array();
		foreach($ga->getResults() as $result) {

		 $id = $result->getProfileId();
		 $webPropertyId = $result->getWebPropertyId();
		  array_push($newarray, array('id'=>$id,'name'=> $result->getProfileName(),'propertyId'=>$webPropertyId));
		}
		
		echo json_encode($newarray);
	}catch (Exception $e){
		echo json_encode('fail');
	}
	


?>