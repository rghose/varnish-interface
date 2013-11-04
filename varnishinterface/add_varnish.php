<?php
	session_start();

	include_once('common.php');

	if( !isset($_POST['idNewServer']) || !isset($_FILES['fileuploadNew']) ){
		echo "something worng";
		exit(0);
	}

	$ip = $_POST['idNewServer'] ;

	if(!move_uploaded_file($_FILES["fileuploadNew"]["tmp_name"], "$varnish_secret_path_prefix" . "_temp_" . $ip )) {
?>
<div class="alert alert-warning alert-dismissable">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
Error in moving to destination from <?php echo $_FILES['fileuploadNew']['tmp_name']; ?>.
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
