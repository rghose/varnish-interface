<?php
	session_start();

	include_once('common.php');

	if(!isset($_SESSION['user'])){
		header( 'Location: ./index.php' );
		die();
	}

	if( !isset($_POST['idNewHostname']) ||  !isset($_POST['idNewServer']) || !isset($_FILES['fileuploadNew']) ){
?>
<div class="alert alert-danger alert-dismissable">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
You missed a value.
</div>
<?php
		exit(0);
	}

	$ip = $_POST['idNewServer'] ;
	$port = $varnishadm_default_port;
	if( isset($_POST['idPort']) ) {
		$options = array( 
			'options' => array ( 'default' => 2000 ), 
			'flags' => array(FILTER_FLAG_ALLOW_OCTAL,FILTER_FLAG_ALLOW_HEX) );
		$port = filter_var( $_POST['idPort'], FILTER_VALIDATE_INT, $options);
	}
	$hostname = $_POST['idNewHostname'];
	$cluster = $_POST['idClusterName'];

	// if no cluster specified move to own cluster
	if( strlen($cluster) == 0 ) {
		$cluster = $hostname;
	}

	// TODO: Add check for:  valid ip address

	if( ! ($ip=filter_var($ip, FILTER_VALIDATE_IP)) ) {
?>
<div class="alert alert-danger alert-dismissable">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
Invalid IP address.
</div>
<?php
		exit(0);
	}

	// Secret files are typically 15-20 bytes
	if( $_FILES["fileuploadNew"]["size"] > 128 ) {
?>
<div class="alert alert-danger alert-dismissable">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
File size too large!
</div>
<?php
		exit(0);
	}

	if(file_exists("$varnish_secret_path_prefix" . $ip )) {
?>
<div class="alert alert-danger alert-dismissable">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
This configuration already exists. Please delete existing file.
</div>
<?php
		exit(0);
	}

	if(!move_uploaded_file($_FILES["fileuploadNew"]["tmp_name"], "$varnish_secret_path_prefix" . $ip )) {
?>
<div class="alert alert-warning alert-dismissable">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
Error in moving to destination. <?php echo $_FILES['file_upload']['error']; ?>.
</div>
<?php
	}
	else {
		try{
			$dir = "sqlite:$sqlite_database_path";
			$dbh  = new PDO($dir) or die("cannot open the database, inform your nearest sysad asap!\n");
			$query = "create table if not exists main (ip text not null, port integer not null default 2000, hostname text not null, cluster text not null)";
			$dbh->exec($query);
			$query="insert into main (ip,port,hostname,cluster) values ('$ip','$port','$hostname','$cluster')";
			$out=$dbh->exec($query);
			$dbh=null;
		}
		catch (PDOException $e){
			echo $e->getMessage();	// to be removed in prod
		}

?>
<div class="alert alert-success alert-dismissable">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
File uploaded!
</div>
<?php
	}
?>
