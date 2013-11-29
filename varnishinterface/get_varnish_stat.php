<?php

session_start();

include_once ( 'config.php' );
include_once ( 'common.php' );

error_reporting(E_ALL);

function get_varnish_server_info( $address, $service_port ) {

	global $varnish_secret_file_prefix;
	
	// Check if the secret file for this ip exists.
	$varnishsec = $varnish_secret_file_prefix . $address;
	if(!file_exists($varnishsec)) {
		return false;
	}

	$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
	if ($socket === false) {
		echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
		return 0;
	}

	#echo "== Attempting to connect to '$address' on port '$service_port'...";
	$result = socket_connect($socket, $address, $service_port);
	if ($result === false) {
		echo "socket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
		return 0;
	} 

	#echo "== Reading response:\n";
	socket_read($socket, 2048,PHP_NORMAL_READ);
	$challenge=socket_read($socket, 2048,PHP_NORMAL_READ);
	socket_read($socket, 2048,PHP_NORMAL_READ);
	socket_read($socket, 2048,PHP_NORMAL_READ);

	exec ( "/bin/varnish_auth.bin $varnishsec  $challenge", $output );

	#echo "== Sending auth data\n";
	$data = "auth $output[0]\n";
	#echo $data;
	socket_write( $socket, $data, strlen($data) );
	#echo "OK\n";

	#echo "== Reading response:\n";
	$resp="";
	for( $i=0; $i<10; $i++ ) {
		$resp .= socket_read($socket, 2048,PHP_NORMAL_READ);
	}

	#echo $resp;
	#echo "== Sending command";
	$data="backend.list\n";
	socket_write( $socket, $data, strlen($data) );
	#echo "OK\n";

	#echo "== Reading response:\n";
	socket_read($socket, 2048,PHP_NORMAL_READ);
	#socket_read($socket, 2048,PHP_NORMAL_READ);
 	socket_set_nonblock($socket);
	
	$received='';
	while(socket_recv($socket, $buf, 1024, 0) >= 1) {
		$received .= $buf;
	}
	socket_close($socket);

	print_table($received);
}

$ip 	= $varnishadm_default_ip;
$port = $varnishadm_default_port;
$noTable = false;

if(isset($_GET['notable'])) {
	$noTable=true;
}

if (!isset( $_GET['ip'])  ) {
	echo 'No server specified.';
	exit(0);
}
else {
	$ip = $_GET['ip'];
	if(!validateIP($ip)) {
		echo "Invalid IP address";
		exit(0);
	}
	$_SESSION['ip'] = $ip;
}

if( !isset( $_GET['port'] ) || strlen($_GET['port'])==0 ) {
	echo 'No port specified, using default<br>';
} 
else {
	$port = $_GET['port'];
	$_SESSION['port']=$port;
}

$output="";
$retVal=run_varnishadm( "backend.list", "$ip", "$port", $output );

switch($retVal) {
	case -1:
		echo "Please install varnishadm (or provide a path in the config)";
		break;
	case -2:
		echo "The secret for this server is missing.<br/>";
		break;
	case 0:
		if($noTable) echo $output;
		else print_table($output);
		break;
	default:
		echo "Looks like something broke. :-/<br/>$output";
}
	// Use this as a failsave.
	//get_varnish_server_info("$ip", "$port" );
?>
