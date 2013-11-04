<?php
	session_start();

	include_once('common.php');

	if( !isset($_POST['idNewServer']) || !isset($_FILES['fileuploadNew']) ){
		echo "something worng";
		exit(0);
	}

	$ip = $_POST['idNewServer'] ;

	// TODO: Add check for:  valid ip address

	if( ! ($ip=filter_var($ip, FILTER_VALIDATE_IP)) ) {
?>
<div class="alert alert-danger alert-dismissable">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
Invalid IP address.
</div>
<?php
		exit(0);
	}

	// Secret files are typically 15-20 bytes
	if( $_FILES["fileuploadNew"]["size"] > 128 ) {
?>
<div class="alert alert-danger alert-dismissable">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
File size too large!
</div>
<?php
		exit(0);
	}

	if(file_exists("$varnish_secret_path_prefix" . $ip )) {
?>
<div class="alert alert-danger alert-dismissable">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
This configuration already exists. Please delete existing file.
</div>
<?php
		exit(0);
	}

	if(!move_uploaded_file($_FILES["fileuploadNew"]["tmp_name"], "$varnish_secret_path_prefix" . $ip )) {
?>
<div class="alert alert-warning alert-dismissable">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
Error in moving to destination. <?php echo $_FILES['file_upload']['error']; ?>.
</div>
<?php
	}
	else {
?>
<div class="alert alert-success alert-dismissable">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
File uploaded!
</div>
<?php
	}
?>
