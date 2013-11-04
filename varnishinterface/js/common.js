var globalAsyncData;

function removeElement(el) {
el.parentNode.removeChild(el);
}

function toggleVdiv(id){
	if( document.getElementById(id).style.display == 'block' ) hidediv(id);
	else showdiv(id);
}
				 
function showdiv(id) {
	document.getElementById(id).style.display = 'block';
}
						  
function hidediv(id) {
	document.getElementById(id).style.display = 'none';
}
function ValidateIPaddress(ipaddress) 
{
	if (/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/.test(ipaddress))
	{
		return (true)
	}
		return (false)
}
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
			/*if(xmlhttp.responseText.substring(0,8)=="<script>") {
					//l = xmlhttp.responseText.length;
					document.head.appendChild(xmlhttp.responseText);
					//eval(xmlhttp.responseText.substring(8,l-9));			}
			else*/
					document.getElementById(divContainer).innerHTML=xmlhttp.responseText;
	}
  }
xmlhttp.open("GET",url,true);
xmlhttp.send();
}
function generateBoundary() {
	return "AJAX-----------------------" + (new Date).getTime();
}
function doVarnishExecute(action, serverId) {
	var servName = document.getElementById("backend"+serverId).innerHTML;
	if(confirm( 'Are you sure you want to apply ' + action + ' to ' + servName + '?' )) {
		loadAjax("./set_server.php?action="+action+"&server="+servName+"&c="+serverId,"status"+serverId);
	}
}
function buildMessage(elements, boundary) {
	var CRLF = "\r\n";
	var parts = [];
	elements.forEach(function(element, index, all) {
			var part = "";
			var type = "TEXT";
			type = element.getAttribute("type").toUpperCase();
			if (type === "FILE" && element.files.length > 0) {
			var fieldName = element.name;
			var fileName = element.files[0].fileName;
			part += 'Content-Disposition: form-data; ';
			part += 'name="' + fieldName + '"; ';
			part += 'filename="'+ fileName + '"' + CRLF;
			part += "Content-Type: application/octet-stream";
			part += CRLF + CRLF;
			var binary=globalAsyncData;
			part += binary + CRLF;
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

