<?php
	session_start();

	include_once( 'config.php' );

	if( !isset($_SESSION['user']) ) {
		header( 'Location: ./index.php' );
		die();
	}

	$showManagement=false;
	if(isset($_GET['manage'])) {
		$showManagement=true;
	}
	if ($showManagement) {
?>
<table class="table table-hover">
<tbody>
<tr class="success">
<td>Hostname</td>
<td>IP Address of Server</td>
<td>Port</td>
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
	$i=0;
	foreach ($dbh->query($query) as $row) {
		// This is for manager.php
		if( $showManagement ) {
			echo "<tr>";
?>
<td title="Double click to edit" id='hostname<?php echo $i; ?>'>
<span style="display: block;" id='cspan<?php echo $i; ?>0' ondblclick="editText('hostname','<?php echo $i?>');"><?php echo stripslashes($row[1]); ?></span>
<div class="input-group">
<input class='form-control' type='text' style='display:none;'id='ctext<?php echo $i;?>0'></input>
<span class="input-group-btn">
<button class="btn btn-default" type="button" style="display:none;" onclick="editText('hostname','<?php echo $i;?>');" id='ctextbtn<?php echo $i;?>0'>Save</button>
</span>
<span class="input-group-btn">
<button type="button" style="display:none;" class="btn"  id="ctextx<?php echo $i;?>0" onclick="editText('hostname','<?php echo $i;?>',2);">Cancel</button>
</span>
</td>
<?php
			echo "<td id=ipAddr$i>" . stripslashes($row[0]) . "</td>";
  		echo "<td id=port$i>". stripslashes($row[3]) . "</td>";
?>
<td title="Double click to edit" id=cluster<?php echo $i;?>>
<span style="display: block;" id='cspan<?php echo $i; ?>1' ondblclick="editText('cluster','<?php echo $i?>');"><?php echo stripslashes($row[2]); ?></span>
<div class="input-group">
<input class='form-control' type='text' style='display:none;' id='ctext<?php echo $i;?>1'></input>
<span class="input-group-btn">
<button class="btn btn-default" type="button" style="display:none;" onclick="editText('cluster','<?php echo $i;?>');" id='ctextbtn<?php echo $i;?>1'>Save</button>
</span>
<span class="input-group-btn">
<button type="button" style="display:none;" class="btn"  id="ctextx<?php echo $i;?>1" onclick="editText('cluster','<?php echo $i;?>',2);">Cancel</button>
</span>
</td>
<?php
			echo "<td><a href='javascript:deleteVarnish($i);'><span class='glyphicon glyphicon-trash'></span></a></td>";
			echo "</tr>";
			$i++;
		}
		else {
?>
			<a class="button" id="btnme<?php echo $i;?>" href="javascript:void(0);" onclick="selectButton('btnme<?php echo $i; ?>');loadAjax('get_varnish_stat.php?ip=<?php echo $row[0];?>&port=<?php echo $row[3];?>','vsdata');" ><span id="ip<?php echo $i;?>">
<?php
			echo "$row[1] ($row[0])";
			echo "</span></a>";
		}
		$i++;
	}

	$dbh=null;

// This is for the manager.php view
if( $showManagement ) {
?>
</tbody>
</table>
</div>
<?php
}
?>
