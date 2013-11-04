<!DOCTYPE html>
<?php
	session_start();
	
	include_once('config.php');
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
function loadFile(fileInput,uploadBtnId) {
	document.getElementById('fileNameDivNew').innerHTML=fileInput.value;
	hidediv(uploadBtnId);
	var r = new FileReader();
	r.onload = function(evt){ 
		globalAsyncData=(evt.target.result); 
		showdiv(uploadBtnId);
	};
	r.readAsBinaryString(fileInput.files[0]);
}
function mAddNewServer(destDivId) {
	var text = document.getElementById('idNewServer');
	if(!ValidateIPaddress(text.value)) {
		alert("Please correct the IP Address entered.");
	}
	else {
		var fields=[];
		var boundary = generateBoundary();
		fields.push(text);
		fields.push(document.getElementById('fileuploadNew'));
		request = buildMessage(fields,boundary);
		var url = 'add_varnish.php';
		var xhr = new XMLHttpRequest;
		xhr.open("POST", url, true);
		xhr.onreadystatechange = function() {
			if (xhr.readyState === 4) {
				document.getElementById(destDivId).innerHTML = xhr.responseText;
				loadServers();
			}
		};
		var contentType = "multipart/form-data; boundary=" + boundary;
		xhr.setRequestHeader("Content-Type", contentType);
		for (var header in this.headers) {
			xhr.setRequestHeader(header, headers[header]);
		}
		xhr.sendAsBinary(request);
	}
}
function deleteVarnish(rownum) {
	delIp=document.getElementById("ipAddr"+rownum).innerHTML;
	if(confirm("Are you sure you want to delete the configuration for "+delIp)) {
		loadAjax('del_varnish.php?ip='+delIp,'infoUpload');
		loadServers();
	}
}
</script>
<body onload="loadServers();">
<?php
	include_once( "header.php" );
?>
<div class="jumbotron">
<div class="container">
<h1>Varnish interface manager</h1>
<p>Add or remove varnish servers in this page. Upload the secret files for servers with authentication in varnishadm</p>
</div>
</div>
<link href="css/bootstrap.min.css" rel="stylesheet" media="screen"/>
<link href="css/fupload.css" rel="stylesheet" media="screen"/>
<div class='container'>
<div id='infoUpload'>
</div>
<div class="panel panel-info">
<div class="panel-heading">Add a new varnish server</div>
<div class="panel-body">
<form class="form-inline" role="form" id='frmNewServer'>
<div class='form-group'>
	<input class='form-control' type="text"  placeholder='Enter ip address of new server' name="idNewServer" id='idNewServer' />
</div>
<div class='form-group'>
	<span class="btn btn-success fileinput-button">
  <i class="glyphicon glyphicon-plus"></i>
  <label class="label" id="fileNameDivNew">Upload secret file</label>
  <input onchange="loadFile(this,'submitData');" name="fileuploadNew" id="fileuploadNew" type="file" />
	</span>
</div>
<div class='form-group pull-right'>
	<input type='button' id="submitData" class='btn'  onclick="mAddNewServer('infoUpload');" value='Add new'/>
</div>
</form>
</div>
</div>
</div>
</div>

<div class="container" id="vmgmtdata">
</div>
<script src="//code.jquery.com/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
