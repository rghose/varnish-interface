<?php
	session_start();

	include_once( 'config.php' );
?>

<table class="table table-hover">
<tbody>
<tr>
<td>IP Address of Server</td>
<td>Delete server</td>
</tr>
<?php
	$i=0;
	foreach (glob("$varnish_secret_path_prefix*") as $filename)	{
		echo "<tr>";
		echo "<td id=ipAddr$i>" . substr($filename, strlen($varnish_secret_path_prefix)) .  "</td>";
		echo "<td><a href='javascript:deleteVarnish($i);'><span class='glyphicon glyphicon-trash'></span></a></td>";
//		echo "<td><input type='button' onclick='deleteVarnish($i);'  class='btn btn-block' value='delete this server'/></td>";
		echo "</tr>";
		$i++;
	}
?>
</tbody>
</table>
</div>
