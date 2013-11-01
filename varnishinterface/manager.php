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

function buildMessage(elements, boundary) {
	var CRLF = "\r\n";
	var parts = [];
	elements.forEach(function(element, index, all) {
			var part = "";
			var type = "TEXT";
			if (element.nodeName.toUpperCase() === "INPUT") {
			type = element.getAttribute("type").toUpperCase();
			}
			if (type === "FILE" && element.files.length > 0) {
			var fieldName = element.name;
			var fileName = element.files[0].fileName;
			part += 'Content-Disposition: form-data; ';
			part += 'name="' + fieldName + '"; ';
			part += 'filename="'+ fileName + '"' + CRLF;
			part += "Content-Type: application/octet-stream";
			part += CRLF + CRLF;
			part += element.files[0].getAsBinary() + CRLF;
			} else {
				part += 'Content-Disposition: form-data; ';
				part += 'name="' + element.name + '"' + CRLF + CRLF;
				part += element.value + CRLF;
			}
			parts.push(part);
	});
	var request = "--" + boundary + CRLF;
	request+= parts.join("--" + boundary + CRLF);
	request+= "--" + boundary + "--" + CRLF;
	return request;
}
function generateBoundary() {
	return "AJAX-----------------------" + (new Date).getTime();
}
function mAddNewServer() {
	var fields=[];
	var boundary = generateBoundary();
	fields.push(document.getElementById('idNewServer'));
	fields.push(document.getElementById('fileuploadNew'));
	request = buildMessage(fields,boundary);
	var url = 'add_varnish.php';
    var xhr = new XMLHttpRequest;
    xhr.open("POST", url, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            alert(xhr.responseText);
        }
    };
    var contentType = "multipart/form-data; boundary=" + boundary;
    xhr.setRequestHeader("Content-Type", contentType);
    for (var header in this.headers) {
        xhr.setRequestHeader(header, headers[header]);
    }
    xhr.sendAsBinary(request);
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
<div class='container'>
<div class="panel panel-default">
<div class="panel-heading">Add a new varnish server</div>
<div class="panel-body">
<form class="form-inline" role="form" id='frmNewServer'>
<div class='form-group'>
	<input class='form-control' placeholder='Enter ip address of new server' id='idNewServer' />
</div>
<div class='form-group'>
	<span class="btn btn-success fileinput-button">
  <i class="glyphicon glyphicon-plus"></i>
  <label class="label" id="fileNameDivNew">Upload secret file</label>
  <input onchange="document.getElementById('fileNameDivNew').innerHTML=this.value;" id="fileuploadNew" type="file" />
	</span>
</div>
<div class='form-group'>
	<input type='button' class='btn' onclick='mAddNewServer();' value='Add new'/>
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
