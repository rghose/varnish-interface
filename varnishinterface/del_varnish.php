<?php
	session_start();

	include_once('config.php');

	if( !isset($_GET['ip'] )){
		echo "need server ip";
		exit(0);
	}

	$ip = $_GET['ip'];

	$ip=filter_var($ip, FILTER_VALIDATE_IP);
	
	if(!$ip) {
?>
<div class="alert alert-error alert-dismissable">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
Invalid IP address.
</div>
<?php
		exit(0);
	}
	
	exec( "rm $varnish_secret_path_prefix$ip" );
?>
<div class="alert alert-success alert-dismissable">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
File was successfully deleted.
</div>

