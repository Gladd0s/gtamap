<?php
	$db = new SQLite3('auth/gtamap.db');

	$results = $db->query("SELECT * FROM regions;");

	$result_data = array();

	while($row = $results->fetchArray(SQLITE3_ASSOC)){
		$region_name = $row["name"];

		$stmt = $db->prepare("SELECT * FROM region_points WHERE region_name = :region_name ORDER BY indx;");
		$stmt->bindValue(':region_name', $region_name, SQLITE3_TEXT);

		if(! $stmt){
			echo $db->lastErrorMsg();
		}

		$point_results = $stmt->execute();

		$row["points"] = array();
		while($point_row = $point_results->fetchArray(SQLITE3_ASSOC)){
			array_push($row["points"], array($point_row['x_pos'], $point_row['y_pos']));
		}
		
		array_push($result_data, $row);
	}
	
	echo json_encode($result_data);
?>
