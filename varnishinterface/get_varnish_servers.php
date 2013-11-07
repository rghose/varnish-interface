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
<tr class="success">
<td>IP Address of Server</td>
<td>Hostname</td>
<td>Cluster number</td>
<td>Delete server</td>
</tr>
<?php
	}
	
	$dir = "sqlite:$sqlite_database_path";
	$dbh  = new PDO($dir) or die("cannot open the database, inform your nearest sysad asap!\n");
	$query = "create table if not exists main (ip text not null, port integer not null default 2000, hostname text not null, cluster text not null)";
	$dbh->exec($query);
	$query =  "select ip, hostname, cluster, port from main";
	foreach ($dbh->query($query) as $row) {
	$i=0;
		if( $showManagement ) {
			echo "<tr>";
  		echo "<td id=ipAddr$i>" . $row[0] .  "</td>";
  		echo "<td id=hostname$i>" . $row[1] .  "</td>";
  		echo "<td id=cluster$i>" . $row[2] .  "</td>";
			echo "<td><a href='javascript:deleteVarnish($i);'><span class='glyphicon glyphicon-trash'></span></a></td>";
			echo "</tr>";
		}
		else {
?>
			<a class="button" href="#" onclick="loadAjax('get_varnish_stat.php?ip=<?php echo $row[0];?>&port=<?php echo $row[3];?>','vsdata');" ><span id="ip<?php echo $i;?>">
<?php
			echo "$row[1] ($row[0])";
			echo "</span></a>";
		}
		$i++;
	}

	$dbh=null;

if( $showManagement ) {
?>
</tbody>
</table>
</div>
<?php
}
?>
