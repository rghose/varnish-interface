<?php

include_once( 'config.php' );


function button_text($state, $number, $function_name="doVarnishExecute") {
	$retVal = "";
	$text[0] = "<a href='javascript:$function_name(\"auto\",$number);'>Enable varnish probe</a>";
	$text[1] = "<a href='javascript:$function_name(\"healthy\",$number);'>Force enable</a>";
	$text[2] = "<a href='javascript:$function_name(\"sick\",$number);'>Force disable</a>";
	$dropDown[0] = "<div id='varnishExecButton$number' class='btn-group'><button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>";
	$dropDown[1] = '<span class="caret"></span></button><ul class="dropdown-menu" role="menu"><li>';
	$dropDown[2] = '</li><li>';
	$dropDown[3] = "</li></ul></div>";
	switch( $state ) {
		case "probe":
		case "auto":
			$retVal = $dropDown[0] . "Varnish probe enabled" . $dropDown[1] . $text[2] . $dropDown[2] . $text[1] . $dropDown[3];
			break;
		case "sick":
			$retVal = $dropDown[0] . "Forcibly disabled" . $dropDown[1] . $text[0] . $dropDown[2] . $text[1] . $dropDown[3];
			break;
		case "healthy":
			$retVal = $dropDown[0] . "Forcibly enabled" . $dropDown[1] . $text[0] . $dropDown[2] . $text[2] . $dropDown[3];
			break;
		default:
			// this is for the heading
			$retVal = "Enable / Disable";
	}
	return $retVal;
}

function status_text($data) {
		$stText[0] = '<span class="glyphicon glyphicon-thumbs-';
		$stText[1] = '"> ';
		$stText[2] = '</span>';
		switch( $data ) {
			case "Healthy":
				$statusText = $stText[0] . 'up' . $stText[1] . '<span class="label label-success">Healthy</span>' . $stText[2];
				break;
			case "Sick":
				$statusText = $stText[0] . 'down' . $stText[1] . '<span class="label label-danger">Sick</span>' . $stText[2];
				break;
			default:
				$statusText = "Status";
		}
		return $statusText;
}


function validateIP($ip) 
{ 
    $valid = false; 
     
    $regexp = '/^((1?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(1?\d{1,2}|2[0-4]\d|25[0-5])$/'; 
     
    if (preg_match($regexp, $ip)) 
    { 
        $valid = true; 
    } 
     
    return $valid; 
}  

class MyCallBack {

	public function __construct() {
		$this->i = 0;
  }

	function callback($matches) {
		$i = $this->i;
		$finalText = button_text($matches[3], $i);
    $statusText = status_text($matches[4]);
    $retVal = "<tr><td id='backend$i'>$matches[1]</td><td>$matches[2]</td><td>$matches[5]</td><td>$statusText</td><td><div id='status$i'>$finalText</div></td></tr>";
		$this->i++;
    return $retVal;
	}
}

function print_table($data) {
	echo '<table class="table table-condensed"><tbody>';
	$i=0;
	$cb = MyCallBack();
	$data = preg_replace_callback( '/^(\S+)\s+(\S+)\s+(\S+)\s+(\S+)\s+(\S+)$/m', array($cb, 'callback') ,$data );
	echo $data;
	echo '</tbody></table>';
}

function run_varnishadm( $command, $server, $port, &$output ) {
	global $varnishadm_binary_path;
	global $varnish_secret_path_prefix;
	global $varnishadm_libs_path;

	$varnishadm = $varnishadm_binary_path;
	$varnishsec = $varnish_secret_path_prefix . $server;
	if(!file_exists($varnishadm))
		return -1;
	if(!file_exists($varnishsec))
		return -2;
	$output = shell_exec( "export LD_LIBRARY_PATH=$varnishadm_libs_path && $varnishadm -T $server:$port -S $varnishsec $command 2>&1" );

	return 0;
}

?>
