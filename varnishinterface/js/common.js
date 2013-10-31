function removeElement(el) {
el.parentNode.removeChild(el);
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
function doVarnishExecute(action, serverId) {
	var servName = document.getElementById("backend"+serverId).innerHTML;
	if(confirm( 'Are you sure you want to apply ' + action + ' to ' + servName + '?' )) {
		loadAjax("./set_server.php?action="+action+"&server="+servName+"&c="+serverId,"status"+serverId);
	}
//	removeElement(document.getElementById('varnishExecButton'+serverId));
}

