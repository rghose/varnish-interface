var globalAsyncData;

function removeElement(el) {
el.parentNode.removeChild(el);
}
function isVisible(id) {
	if(document.getElementById(id).style.display == 'block') return true;
	return false;
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
function vSyncAll(action,serverId) {
	var servName = document.getElementById("backend"+serverId).innerHTML;
	if(confirm( 'Are you sure you want to apply ' + action + ' to ' + servName + '?' )) {
		loadAjax("./set_server.php?cluster&action="+action+"&server="+servName+"&c="+serverId,"status"+serverId);
	}
}

function doVarnishExecute(action, serverId) {
	var servName = document.getElementById("backend"+serverId).innerHTML;
	if(confirm( 'Are you sure you want to apply ' + action + ' to ' + servName + '?' )) {
		loadAjax("./set_server.php?action="+action+"&server="+servName+"&c="+serverId,"status"+serverId);
	}
}
function buildMessage(elements, boundary,mode) {
	if(mode==undefined)
		mode=1
	var CRLF = "\r\n";
	var parts = [];
	elements.forEach(function(element, index, all) {
			var part = "";
			var type = "TEXT";
			type = element.getAttribute("type").toUpperCase();
			if ((mode == 2 && element.name == "textSecret") || (type === "FILE" && element.files.length > 0)) {
			var fieldName = (mode==2 ? ('fileuploadNew') : element.name);
			var fileName = ((mode==2)? ('text') : element.files[0].fileName);
			part += 'Content-Disposition: form-data; ';
			part += 'name="' + fieldName + '"; ';
			part += 'filename="'+ fileName + '"' + CRLF;
			part += "Content-Type: application/octet-stream";
			part += CRLF + CRLF;
			globalAsyncData = document.getElementById('fileData').value;
			if(mode==2) globalAsyncData += "\n";
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
function doAjaxPost(url,params,areaId) {
	if(window.XMLHttpRequest) xh = new XMLHttpRequest();
	else xh = new ActiveXObject("Microsoft.XMLHTTP");
	xh.open("POST", url, true);

	xh.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xh.setRequestHeader("Content-length", params.length);
	xh.setRequestHeader("Connection", "close");

	xh.onreadystatechange = function() {
		var status = "<br><br><img src='images/loading.gif' alt='Loading...'><br><br><br>";
		document.getElementById(areaId).innerHTML = status;
		if(xh.readyState == 4 && xh.status == 200) {
			document.getElementById(areaId).innerHTML = xh.responseText;
		}
	}
	xh.send(params);
} 
function loadVarnishServers(divId) {
	loadAjax('get_varnish_servers.php',divId);
}
function editText(key,cn,mode){
	var cm;
	if(key=="hostname") cm=0;
	else if(key=="cluster") cm=1;
	var id1='cspan'+cn+cm;
	var id2='ctext'+cn+cm;
	var id3='ctextbtn'+cn+cm;
	var id4='ctextx'+cn+cm;
	toggleVdiv(id1);
	toggleVdiv(id2);
	toggleVdiv(id3);
	toggleVdiv(id4);
	var val1=document.getElementById(id1).innerHTML;
	var val2=document.getElementById(id2).value;
	if(isVisible(id1) && mode!=2) { 
		loadAjax('edit_varnish.php?key='+key+'&v='+val2+'&old='+val1,id1);
	}
	else { 
		document.getElementById(id2).value=val1;
		document.getElementById(id2).focus();
		document.getElementById(id2).select();
	}
}
function selectButton(id) {
	elements=document.getElementsByClassName('clickedbutton');
	for( i=0; i<elements.length; i++) {
		elements[i].className='button';
	}
	document.getElementById(id).className="clickedbutton"
}
