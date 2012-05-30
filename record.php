<?php

require_once dirname(__FILE__) . '/videorecorder.php';

if(!empty($_POST["ACTION"])) {	
	$client 	= new videorecorder();
	
	$ACTION 	= $_POST["ACTION"];
	$USERDIR 	= $_POST["USERDIR"];
	$filename 	= $_FILES["USERFILE"]["name"];
	$filedata 	= $_FILES['USERFILE']['tmp_name'];
	
	$client->save($ACTION, $USERDIR, $filename, $filedata);
} else {
	exit(NotReg);
}