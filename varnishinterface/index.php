<?php
	session_start();

	if( !isset($_SESSION['user'] )){
		
		if(isset($_POST['username']) && isset($_POST['password'])){
			$ldap = ldap_connect("172.16.140.34");

			$username=$_POST['username'];
			$password=$_POST['password'];

			if($bind = ldap_bind($ldap, $username, $password)) {
				$_SESSION['user']=$username;
				header( 'Location: ./home.php' );
				die('kasjkajskjaksjkas');
			} else {
				$_SESSION['login_error']="Invalid AD credentials";
			}

			ldap_close($ldap);
		}

?>
<html>
<head>
<title>VAT Login</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<?php
	include_once( 'header.php' );
?>
<div class="jumbotron">
<div class="container">
<h1>VAT<small>Varnish administrative tool</small></h1>
<p>Please login with your AD username and password.</p>
</div>
</div>
<div class="container">
<form class="form-horizontal" role="form" action="index.php" method="POST">
	<div class="form-group">
		<label for="inputID" class="col-sm-2 control-label">AD Username:</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" name="username" id="inputID" placeholder="Enter AD username">
		</div>
	</div>
	<div class="form-group">
		<label for="inputPassword" class="col-sm-2 control-label">Password</label>
		<div class="col-sm-10">
			<input type="password" class="form-control" name="password" id="inputPass" placeholder="Password">
		</div>
	</div>
<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" class="btn btn-default">Sign in</button>
		</div>
	</div>
</form>
</div>
<script src="//code.jquery.com/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
<?php
	}
	else {
		header('Location: ./home.php');	
		die('deadbeef');
	}
?>
