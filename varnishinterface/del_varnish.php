<?php
	session_start();

	include_once('config.php');

	if( !isset($_SESSION['user']) ) {
		header( 'Location: ./index.php' );
		die();
	}

	if( !isset($_GET['ip'] )){
		echo "need server ip";
		exit(0);
	}

	$ip = $_GET['ip'];

	$ip=filter_var($ip, FILTER_VALIDATE_IP);
	
	if(!$ip) {
?>
<div class="alert alert-danger alert-dismissable">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
Invalid IP address.
</div>
<?php
		exit(0);
	}
	
	exec( "rm $varnish_secret_path_prefix$ip" );
	try{
		$dir = "sqlite:$sqlite_database_path";
		$dbh  = new PDO($dir) or die("cannot open the database, inform your nearest sysad asap!\n");
		$query = "create table if not exists main (ip text not null, port integer not null default 2000, hostname text not null, cluster text not null)";
		$dbh->exec($query);
		$query="delete from main where ip='$ip'";
		$dbh->exec($query);
		$dbh=null;
	}
	catch (PDOException $e){
		echo $e->getMessage();
	}

?>
<div class="alert alert-success alert-dismissable">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
Server was successfully deleted.
</div>

