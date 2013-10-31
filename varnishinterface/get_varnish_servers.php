<?php
	session_start();

	include_once( 'config.php' );
?>
<div class="panel panel-default">
<table class="table">
<tbody>
<?php
	$i=0;
	foreach (glob("$varnish_secret_path_prefix*") as $filename)	{
		echo "<tr>";
		echo "<td id=ipAddr$i>" . substr($filename, strlen($varnish_secret_path_prefix)) .  "</td>";
//		echo "<td><input id='newSecret$i' type='file' value='Upload new secret file'/></td>";
?>
<td>
<span class="btn btn-success fileinput-button">
<i class="glyphicon glyphicon-plus"></i>
<label class="label" id="fileNameDiv<?php echo $i; ?>">upload new secret file</label>
<input onchange="document.getElementById('fileNameDiv<?php echo $i; ?>').innerHTML=this.value;" id="fileupload<?php echo $i; ?>" type="file" />
</span>
</td>
<?php
		echo "<td><input type='button' class='btn' value='Confirm changes'/></td>";
		echo "</tr>";
		$i++;
	}
?>
<tr>
<td><input class='form-control' placeholder='Enter ip address of new server' id='idNewServer' /></td>
<td>
<span class="btn btn-success fileinput-button">
  <i class="glyphicon glyphicon-plus"></i>
  <label class="label" id="fileNameDivNew">Upload secret file</label>
  <input onchange="document.getElementById('fileNameDivNew').innerHTML=this.value;" id="fileuploadNew" type="file" />
</span>
</td>
<td><input type='button' class='btn' onclick='mAddNewServer();' value='Add new'/></td>
</tr>
</tbody>
</table>
</div>
