<?php
	session_start();

if( !isset($_SESSION['user'] ) ) {
		header( 'Location: ./index.php' );
		die();
	}

	include_once('config.php');
?>
<!DOCTYPE html>
<html>
<head>
<title>Varnish Administrative Tool</title>
</head>
<script src="js/common.js"></script>
<script>
function loadServers() {
	loadAjax('get_varnish_servers.php?manage','vmgmtdata');
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
	var hostname = document.getElementById('idNewHostname');
	var cluster = document.getElementById('idClusterName');
	var text = document.getElementById('idNewServer');
	var url = 'add_varnish.php';
	if(hostname.value=="") {
		alert("Please enter hostname");
		return; 
	}
	if(!ValidateIPaddress(text.value)) {
		alert("Please correct the IP Address entered.");
	}
	else {
		var fields=[];
		var boundary = generateBoundary();
		var mode=1;
		fields.push(text);
		fields.push(hostname);
		fields.push(cluster);
		if(!isVisible('uploadButton') && isVisible('fileData')) {
			fields.push(document.getElementById('fileData'));
			mode=2;
		}
		else 
			fields.push(document.getElementById('fileuploadNew'));
		request = buildMessage(fields,boundary,mode);
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
<div class="panel-heading">Add a new varnish server
	<button data-toggle="button" type="button" class="btn btn-primary" id='toggleFileName' onclick='toggleVdiv("uploadButton");toggleVdiv("fileData");'>Toggle text mode</button>
</div>
<div class="panel-body">
<form class="form-inline" role="form" id='frmNewServer'>
<div class='form-group col-md-2'>
	<input class='form-control' type="text"  placeholder='Enter hostname' name="idNewHostname" id='idNewHostname' />
</div>
<div class='form-group col-md-2'>
	<input class='form-control' type="text"  placeholder='Enter ip address' name="idNewServer" id='idNewServer' />
</div>
<div class='form-group col-md-2'>
	<input class='form-control' type="text"  placeholder='Port (default 2000)' name="idPort" id='idPort' />
</div>
<div class='form-group col-md-2'>
	<input class='form-control' type="text"  placeholder='Cluster name' name="idClusterName" id='idClusterName' />
</div>
<div class='form-group col-md-2'>
	<input id='fileData' class='form-control' name="textSecret" style='display: none;' type='text' placeholder='Paste the secret here.'  />
	<span id='uploadButton' style='display: block;' class="btn btn-success fileinput-button">
  	<i class="glyphicon glyphicon-plus"></i>
	  <label class="label" id="fileNameDivNew">Upload secret file</label>
  	<input onchange="loadFile(this,'submitData');" name="fileuploadNew" id="fileuploadNew" type="file" />
	</span>
</div>
<div class='form-group col-md-2'>
	<input type='button' id="submitData" class='btn'  onclick="mAddNewServer('infoUpload');" value='Add new'/>
</div>
</form>
<br/>
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
