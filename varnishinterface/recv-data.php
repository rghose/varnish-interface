<?php
	echo 'Data recieved :)';
	parse_str( $_SERVER['QUERY_STRING'], $all_data );
	foreach(array_keys($all_data) as $key){
 	   print $key . ' is ' .  $all_data[$key] . "\n";
	}
?>
