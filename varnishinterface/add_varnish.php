<?php
	session_start();

	include_once('common.php');

	$ip = $_POST['idNewServer'] ;
	echo $ip . "\nFilename: ";

	if(!move_uploaded_file($_FILES["fileuploadNew"]["tmp_name"], "$varnish_secret_path_prefix" . "_temp_" . $ip )) {
		echo "Error in moving to destination\n";
	}
?>
