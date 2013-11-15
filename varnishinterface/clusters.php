<?php
	session_start();

	include_once( 'config.php' );
	include_once( 'common.php' );

	if( !isset($_SESSION['user']) ) {
		header( 'Location: ./index.php' );
		die();
	}

	$mode=1;
	$cneed = false;
	$cgot=false;
	$cluster;
	if( isset($_GET['cluster']) ) {
		$mode=2;
		$cneed=true;
		$cluster=$_GET['cluster'];
	}
		

	try {
		$dir = "sqlite:$sqlite_database_path";
		$dbh  = new PDO($dir) or die("cannot open the database, inform your nearest sysad asap!\n");
		$query = "create table if not exists main (ip text not null, port integer not null default 2000, hostname text not null, cluster text not null)";
		$dbh->exec($query);
		$query="select distinct cluster from main";
		$i=0;
		foreach ( $dbh->query($query) as $row ) {
			if( $mode == 1 ) {
?>
<a class="button" onclick="loadAjax('clusters.php?cluster=<?php echo $row['cluster'];?>','cvDetails');"><span id="cluster<?php echo $i;?>">
<?php print $row['cluster']; ?>
</span>
</a>
<br/>
<?php
			$i++;
			}
			else if( $mode==2 && $cneed  && $cluster==$row['cluster']) {
				$cneed=false;
				$cgot=true;
			}
		}

		if( $mode==1 ) {
			$dbh=null;
			exit(0);	// done for getting clusters
		}

		// Now print out the details of the cluster recieved...
		if( $cgot == false ) {
			$dbh=null;
			die ("Not a valid cluster name.<br/>");
		}

		$query = "select ip, port, hostname from main where cluster='$cluster'";
		$c=0;
		$handle=array();
		$servers=array();
		foreach( $dbh->query($query) as $row ) {
			$servers[$c]=$row['ip'];
			$handle[$c] = popen("php-cgi  -q get_varnish_stat.php ip=$row[ip] port=$row[port] notable",'r');
			if(!$handle[$c])
				die("Failed for $row[ip]");
			// This is needed by set_server.php when it will scan for cluster ips in cluster mode
			$_SESSION["CLUSTER_$c"]=$row['ip'];
			$c++;
		}

		$_SESSION["CLUSTERS"] = $c;

		$data=array();
		$i=0;
		$total_lines=0;
		while($i<$c) {
			if($handle[$i]) {
				$total_lines=0;
				while (!feof($handle[$i])) {
					$buff = fscanf($handle[$i], "%s %s %s %s %[^\n]s");
					//list($name, $refs, $action, $status, $probes) = $buff;
					$data[$servers[$i]][$total_lines++]=$buff;
				}
			}
			pclose($handle[$i++]);
		} 
		
		echo '<table class="table table-condensed"><tbody>';
		for($i=1;$i<$total_lines-1;$i++) {
			$finalText="";
			$statusText="";
			$acts=array();
			$health=array();
			for($j=0;$j<$c;$j++) {
				$acts[$j]  =$data[$servers[$j]][$i][2];
				$health[$j]=$data[$servers[$j]][$i][3];
				$finalText  .= " $acts[$j]";
				$statusText .= " $health[$j]";
			}

			$finalText = button_text($acts[0],$i,'vSyncAll');
			for($j=1;$j<$c;$j++) {
				if($acts[$j]!=$acts[$j-1]) {
					$finalText = "Not in sync";
					break;
				}
			}
			
			$statusText = status_text($health[0]);
			for($j=1;$j<$c;$j++) {
				if($health[$j]!=$health[$j-1]) {
					$statusText = "Not in sync (or network issue)";
					break;
				}
			}
//			echo "<tr><td id='backend$i'>$name</td><td>$refs<td><td>$probes</td><td>$statusText</td><td><div id='status$i'>$finalText</div></td></tr>";
				echo "<tr><td id='backend$i'>" . $data[$servers[0]][$i][0] . "</td><td>$statusText</td><td><div id='status$i'>$finalText</div></td></tr>";
		}
		echo '</tbody></table>';
	
		$dbh=null;
	}
	catch( PDOException $e ){
		echo $e->getMessage();	// to be removed in prod
	}
?>
