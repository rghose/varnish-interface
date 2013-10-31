<?php
	session_start();
?>
<html>
<head>
<title>Varnish Interface manager</title>
</head>
<script src="js/common.js"></script>
<script>
function loadServers() {
	loadAjax('get_varnish_servers.php','vmgmtdata');
}
</script>
<body onload="loadServers();">
<div class="jumbotron">
<div class="container">
<h1>Varnish interface manager</h1>
<p>Add or remove varnish servers in this page. Upload the secret files for servers with authentication in varnishadm</p>
</div>
</div>
<link href="css/bootstrap.min.css" rel="stylesheet" media="screen"/>
<link href="css/fupload.css" rel="stylesheet" media="screen"/>
<?php
	include_once('config.php');

?>
<div class="container" id="vmgmtdata">
</div>

<script src="//code.jquery.com/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
