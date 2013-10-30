<?php
	session_start();

	include_once ( 'config.php');
	include_once ( 'common.php');

	$action=$_GET['action'];
	$server=$_GET['server'];

	$ip = $varnishadm_default_ip;
	$port = $varnishadm_default_port;

	if (isset( $_GET['ip'])  ) {
		$ip = $_GET['ip'];
		$_SESSION['ip'] = $ip;
	} else if( isset( $_SESSION['ip'] ))
		$ip = $_SESSION['ip'];

	if( isset( $_GET['port'] )) {
		$port = $_GET['port'];
		$_SESSION['port']=$port;
	} else if( isset( $_SESSION['port'] ))
		$port = $_SESSION['port'];

	// sanitize the server string for varnishadm and bash.
	$runString = "backend.set_health $server $action";
	// TODO: Can a hostname have 2 commas?
	$runString = str_replace( array(",," , "("  , ")"  ), array(":"  , "\(" , "\)" ), $runString );	
	if(false==($retVal=run_varnishadm( "$runString", "$ip", "$port" ))) {
		echo "<div class='glyphicon glyphicon-exclamation-sign'>$retVal</div>";
	}
	else echo "<div class='glyphicon glyphicon-ok>Done!</div>'";
?>
