<?php
	session_start();

	include_once ( 'config.php');
	include_once ( 'common.php');

	if( !isset( $_GET['action']) || !isset($_GET['server']) ) {
		echo "So server / action specified, cannot continue.";
		exit(0);
	}
	
	$c = $_GET['c'];
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

	$output="";

	if( isset($_GET['cluster']) && isset($_SESSION['CLUSTERS']) ) {
		$n = $_SESSION["CLUSTERS"];
		$dirty=0;
		$bad_servers=array();
		for($i=0;$i<$n;$i++) {
			if(!isset($_SESSION["CLUSTER_$i"]))
				die("Invalid data");
			
			$ip=$_SESSION["CLUSTER_$i"];
			//unset($_SESSION["CLUSTER_$i"]);

			// sanitize the server string for varnishadm and bash.
			$runString = "backend.set_health $server $action";
			// TODO: Can a hostname have 2 commas?
			$runString = str_replace( array(",," , "("  , ")"  ), array(":"  , "\(" , "\)" ), $runString );	
			
			if( 0!=run_varnishadm("$runString","$ip","$port", $output )) {
				$bad_servers[$dirty++]=$ip;
			}
		}
		if($dirty>0) {
			echo "<div class='glyphicon glyphicon-exclamation-sign'>Failed for servers " . print_r($bad_servers) . "</div>";
			unset($_SESSION["CLUSTERS"]);
		}

		if(0!=strlen($c)) {
			$buttonText = button_text($action, $c, 'vSyncAll' );
			echo "$buttonText";
		}
		else echo "<div class='glyphicon glyphicon-ok'> Set as $action</div>"; 
		exit(0);
	}

	// sanitize the server string for varnishadm and bash.
	$runString = "backend.set_health $server $action";
	// TODO: Can a hostname have 2 commas?
	$runString = str_replace( array(",," , "("  , ")"  ), array(":"  , "\(" , "\)" ), $runString );	
	if(0!=run_varnishadm( "$runString", "$ip", "$port" ,$output)) {
		echo "<div class='glyphicon glyphicon-exclamation-sign'>$output</div>";
	}
	else {
		// worked fine, so display the relevant button.
		if( 0 != strlen($c) ) {
			$buttonText = button_text($action, $c);
			echo "$buttonText";
		}
		else echo "<div class='glyphicon glyphicon-ok'> Set as $action</div>";
	}
?>
