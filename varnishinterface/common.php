<?php

include_once( 'config.php' );

function print_table($data) {
	echo '<table class="table table-condensed"><tbody>';
	$i=0;
	$data = preg_replace_callback( '/^(\S+)\s+(\S+)\s+(\S+)\s+(\S+)\s+(\S+)$/m', function($matches) use (&$i) {
			$text[0] = "<a href='javascript:doVarnishExecute(\"auto\",$i);'>Enable varnish probe</a>";
			$text[1] = "<a href='javascript:doVarnishExecute(\"enable\",$i);'>Force enable</a>";
			$text[2] = "<a href='javascript:doVarnishExecute(\"disable\",$i);'>Force disable</a>";
			$dropDown[0] = '<div class="btn-group"><button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">';
			$dropDown[1] = '<span class="caret"></span></button><ul class="dropdown-menu" role="menu"><li>';
			$dropDown[2] = '</li><li>';
			$dropDown[3] = "</li></ul><div id='status$i'></div></div>";
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
			$retVal = "<tr><td id='backend$i'>$matches[1]</td><td>$matches[2]</td><td>$matches[5]</td><td>$statusText</td><td>$finalText</td></tr>";
			$i++;
			return $retVal;
			}, $data );
	echo $data;
	echo '</tbody></table>';
}

function run_varnishadm( $command, $server, $port ) {
	global $varnishadm_binary_path;
	global $varnish_secret_path_prefix;
	global $varnishadm_libs_path;

	$varnishadm = $varnishadm_binary_path;
	$varnishsec = $varnish_secret_path_prefix . $server;
	if(!file_exists($varnishadm) || !file_exists($varnishsec))
		return false;
	$output = shell_exec( "LD_LIBRARY_PATH=$varnishadm_libs_path && $varnishadm -T $server:$port -S $varnishsec $command" );

	return $output;
}

?>
