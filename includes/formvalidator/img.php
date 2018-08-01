<?php
// include FormValidator class
include 'SPAF_FormValidator.class.php';

// start session
// NOTICE: we have removed session_start() from this script since as of
// version 1.01 FormValidator is able to start the session by itself 
// session_start();

// instantiate the object
$spaf_obj = new SPAF_FormValidator();

// stream image
$spaf_obj->streamImage();
?>
