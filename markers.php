<?php
	$db = new SQLite3('auth/gtamap.db');

	$results = $db->query("SELECT * FROM markers;");

	$result_data = array();

	while($row = $results->fetchArray(SQLITE3_ASSOC)){
		array_push($result_data, $row);
	}

	echo json_encode($result_data);
?>
