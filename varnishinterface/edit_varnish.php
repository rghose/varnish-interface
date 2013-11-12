<?php
session_start();

include_once('config.php');

if(!isset($_SESSION['user'])){
	die('Please login to continue...');
}

if(!isset($_GET['key']) || !isset($_GET['v']) || !isset($_GET['old']) )
	die("Invalid call.");

$key=addslashes($_GET['key']);
$val=addslashes($_GET['v']);
$old=addslashes($_GET['old']);

// no changes, no need for database work
if( $old == $key ) {
	die($old);
}

try{
	$dir = "sqlite:$sqlite_database_path";
	$dbh  = new PDO($dir) or die("cannot open the database, inform your nearest sysad asap!\n");
	$query = "create table if not exists main (ip text not null, port integer not null default 2000, hostname text not null, cluster text not null)";
	$dbh->exec($query);
	$query="update main set $key='$val' where $key='$old'";
	$out=$dbh->exec($query);
	$dbh=null;
}
catch (PDOException $e){
	echo $e->getMessage();	// to be removed in prod
}

echo stripslashes($val);
?>
