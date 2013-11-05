<?php
	session_start();

	include_once( 'config.php' );

	$showManagement=false;
	if(isset($_GET['manage'])) {
		$showManagement=true;
	}
	if ($showManagement) {
?>
<table class="table table-hover">
<tbody>
<tr>
<td>IP Address of Server</td>
<td>Delete server</td>
</tr>
<?php
	}
	$i=0;
	foreach (glob("$varnish_secret_path_prefix*") as $filename)	{
		if( $showManagement ) {
			echo "<tr>";
			echo "<td id=ipAddr$i>" . substr($filename, strlen($varnish_secret_path_prefix)) .  "</td>";
			echo "<td><a href='javascript:deleteVarnish($i);'><span class='glyphicon glyphicon-trash'></span></a></td>";
			echo "</tr>";
		}
		else {
?>
			<a class="button" href="#" onclick="loadAjax('get_varnish_stat.php?ip='+getElementById('ip<?php echo $i;?>').innerHTML+'&port='+getElementById('port').value,'vsdata');" ><span id="ip<?php echo $i;?>">
<?php
			echo substr($filename, strlen($varnish_secret_path_prefix));	
			echo "</span></a>";
		}
		$i++;
	}

	if( $showManagement ) {
?>
</tbody>
</table>
</div>
<?php
		}
?>
