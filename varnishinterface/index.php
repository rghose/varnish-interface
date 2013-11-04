<?php
	session_start();	
?>
<html>
<head>
<title>Varnish interface</title>
</head>
<body>
<?php
	include_once( 'header.php' );
?>
<div class="jumbotron">
<div class="container">
<h1>Varnish Interface</h1>
<p>Get access to varnish administration for the servers added in varnish interface manager. Use with care. Enable or disable servers on the varnish server.</p>
</div>
</div>

<div class="container">

<form role="form" class="form-horizontal" action='javascript:void(0);'>
<input class="form-control" id='ip' placeholder='Enter the IP of varnish server' /><br/>
<input class="form-control" id='port' placeholder='Enter port (default 2000)' /><br />
<input class="btn btn-default" type="button" value="Get data" onclick="loadAjax('get_varnish_stat.php?ip='+getElementById('ip').value+'&port='+getElementById('port').value,'vdata');" />
</form>

</div>

<div class="container" id="vdata">
</div>

<script src="//code.jquery.com/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
