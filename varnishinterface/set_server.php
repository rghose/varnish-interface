<?php
	session_start();

	include_once ( 'config.php');
	include_once ( 'common.php');

	$action=$_GET['action'];
	$server=$_GET['server'];

	if (isset( $_GET['ip'])  ) {
		$ip = $_GET['ip'];
		$_SESSION['ip'] = $ip;
	}

	if( isset( $_GET['port'] )) {
		$port = $_GET['port'];
		$_SESSION['port']=$port;
	}

	$ip = $_SESSION['ip'];
	$port = $_SESSION['port'];

	// sanitize the server string for varnishadm and bash.
	$runString = "'backend.set_health $server $action'";
	// TODO: Can a hostname have 2 commas?
	$runString = str_replace( ",,", ":", $runString );	
	
	if(false==($retVal=run_varnishadm( "$runString", "$ip", "$port" ))) {
		echo '<div class="glyphicon glyphicon-exclamation-sign"></div>';
	}

	echo "<div class='glyphicon glyphicon-exclamation-ok'>$retVal</div>";
?>
