<?php
	session_start();	
?>
<html>
<head>
<title>Varnish interface</title>
</head>
<body>
<script>
function loadAjax(url,divContainer) {
var xmlhttp;
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  var status = "<br><br><img src='images/loading.gif' alt='Loading...'><br><br><br>";
  document.getElementById(divContainer).innerHTML = status; 
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById(divContainer).innerHTML=xmlhttp.responseText;
    }
  }
xmlhttp.open("GET",url,true);
xmlhttp.send();
}
</script>

<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
<div class="container">
<div class="navbar-header">
<h2>Varnish Interface</h2>
</div>
</div>

<div class="container">

<form role="form" class="form-horizontal" action='javascript:void(0);'>
<input class="form-control" id='ip' placeholder='Enter the IP of varnish server' /><br/>
<input class="form-control" id='port' placeholder='Enter port (default 2000)' /><br />
<!--input class="btn btn-default" type="submit" value="get data"-->
<input class="btn btn-default" value="Get data" onclick="loadAjax('get_varnish_stat.php?ip='+getElementById('ip').value+'&port='+getElementById('port').value,'vdata');" />
</form>

</div>

<div class="container" id="vdata">
</div>

<script src="//code.jquery.com/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
