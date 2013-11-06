<?php
	session_start();	

	if( !isset($_SESSION['user'] ) ) {
		header( 'Location: ./index.php' );
		die();
	}
?>
<html>
<head>
<title>Varnish interface</title>
</head>
<body onload="loadVarnishServers('vservers')">
<?php
	include_once( 'header.php' );
?>
<div class="jumbotron">
<div class="container">
<h1>VAT <small>Varnish administrative tool</small></h1>
<p>Get access to varnish administration for the servers added in varnish interface manager. Use with care. Enable or disable servers on the varnish server.</p>
</div>
</div>

<div class="container">
<!-- Nav tabs -->
<ul class="nav nav-tabs">
<li><a href="#saved" data-toggle="tab">Servers</a></li>
<li><a href="#custom" data-toggle="tab">Custom</a></li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
<div class="tab-pane in fade active" id="saved">

<div class="panel panel-default">
<div class="panel-heading"> 
	<div class="container" id="vservers"></div>
</div>
<div class="panel-body"> 
	<div class="container" id="vsdata">
	</div>
</div>
</div>
</div>

<div class="tab-pane fade" id="custom">
<form role="form" class="form-horizontal" action='javascript:void(0);'>
<input class="form-control" id='ip' placeholder='Enter the IP of varnish server' /><br/>
<input class="form-control" id='port' placeholder='Enter port (default 2000)' /><br />
<input class="btn btn-default" type="button" value="Get data" onclick="loadAjax('get_varnish_stat.php?ip='+getElementById('ip').value+'&port='+getElementById('port').value,'vdata');" />
</form>
<div class="container" id="vdata"></div>
</div>

</div>
</div>


<script src="//code.jquery.com/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
