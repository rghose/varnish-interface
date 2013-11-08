<?php
	session_start();

	include_once( 'config.php' );

	if( !isset($_SESSION['user']) ) {
		exit(0);
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
		foreach( $dbh->query($query) as $row ) {
			echo "$row[ip]:$row[port] is $row[hostname]<br/>";
		}
	
		$dbh=null;
	}
	catch( PDOException $e ){
		echo $e->getMessage();	// to be removed in prod
	}
?>
