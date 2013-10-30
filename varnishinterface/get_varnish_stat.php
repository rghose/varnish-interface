<?php

session_start();

include_once ( 'config.php' );

error_reporting(E_ALL);

function print_table($data) {
	echo '<table class="table table-condensed"><tbody>';
	$i=1;
	$data = preg_replace_callback( '/^(\S+)\s+(\S+)\s+(\S+)\s+(\S+)\s+(\S+)$/m', function($matches) use (&$i) {
			$text[0] = "Enable varnish probe";
			$text[1] = "Force enable";
			$text[2] = "Force disable";
			$dropDown[0] = '<div class="btn-group"><button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">';
			$dropDown[1] = '<span class="caret"></span></button><ul class="dropdown-menu" role="menu"><li><a href="#">';
			$dropDown[2] = '</a></li><li><a href="#">';
			$dropDown[3] = '</a></li></ul></div>';
			$finalText = "";
			switch( $matches[3] ) {
				case "probe":
					$finalText = $dropDown[0] . "Varnish probe enabled" . $dropDown[1] . $text[2] . $dropDown[2] . $text[1] . $dropDown[3];
					break;
				case "sick":
					$finalText = $dropDown[0] . "Forcibly disabled" . $dropDown[1] . $text[0] . $dropDown[2] . $text[1] . $dropDown[3];
                                        break;
				case "healthy":
					$finalText = $dropDown[0] . "Forcibly enabled" . $dropDown[1] . $text[0] . $dropDown[2] . $text[2] . $dropDown[3];
                                        break;
				default:
					// this is for the heading
					$finalText = "Enable / Disable";
			}

			$statusText = "";
			$stText[0] = '<span class="glyphicon glyphicon-thumbs-';
			$stText[1] = '"> ';
			$stText[2] = '</span>';
			switch( $matches[4] ) {
				case "Healthy":
					$statusText = $stText[0] . 'up' . $stText[1] . 'Healthy' . $stText[2];
					break;
				case "Sick":
					$statusText = $stText[0] . 'down' . $stText[1] . 'Sick' . $stText[2];
                                        break;
				default:
					$statusText = "Status";
			}
			return "<tr><td>$matches[1]</td><td>$matches[2]</td><td>$matches[5]</td><td>$statusText</td><td>$finalText</td></tr>";
			}, $data );
	echo $data;
	echo '</tbody></table>';
}

function get_varnish_server_info( $address, $service_port ) {

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

function run_varnishadm( $command, $server, $port ) {
	$varnishadm = $varnishadm_binary_path;
	$varnishsec = $varnishadm_secret_path_prefix . $server;
	if(!file_exists($varnishadm_binary_path) || !file_exists($varnishsec))
		return false;
	$output = shell_exec( "LD_LIBRARY_PATH=$varnishadm_libs_path && $varnishadm_binary_path -T $server:$port -S $varnishsec $command" );

	print_table($output);
	return true;
}


$ip = $varnishadm_default_ip;
$port = $varnishadm_default_port;

if (!isset( $_GET['ip'])  ) {
	echo 'No server specified.';
	exit(0);
}
else $ip = $_GET['ip'];

if( !isset( $_GET['port'] ) || strlen($_GET['port'])==0 ) {
	echo 'No port specified, using default';
} else $port = $_GET['port'];

if( !run_varnishadm( "backend.list", "$ip", "$port" )) {
	// Use this as a failsave.
	get_varnish_server_info("$ip", "$port" );
}

?>
