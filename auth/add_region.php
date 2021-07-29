<?php
	$have_error = false;
	
	if($_SERVER['REQUEST_METHOD'] != 'POST'){
		http_response_code(400);
		echo "wrong request";
		
		$have_error = true;
	}

	$name = '';
	$type = -1;
	$owner = -1;
	$popup = '';
	$color = '';

	$points_str = '';
	$points = array();
	
	$have_error = false;

	if( empty($_POST['points'])){
		http_response_code(400);
		echo "missing points";
	} else {
		$points_str = $_POST['points'];
	
		$points_toks = explode(';', $points_str);

		foreach($points_toks as $point_str){
			$point_toks = explode(',', $point_str);

			if(count($point_toks) != 2 || ! is_numeric($point_toks[0]) || !is_numeric($point_toks[1])){
				http_response_code(400);
				echo "bad point: $point_str";
				$have_error = true;
	
				break;
			} else {
				array_push($points, array(floatval($point_toks[0]), floatval($point_toks[1])));
			}
		}
		
	}


	if(empty($_POST['name'])){
		http_response_code(400);
		echo "missing name";
		$have_error = true;
	} else $name = $_POST['name'];

	if(empty($_POST['color'])){
		http_response_code(400);
		echo "missing color";
		$have_error = true;
	} else $color = $_POST['color'];

	if(! $have_error){
		if(! empty($_POST['popup'])){
			$popup = $_POST['popup'];
		}

		if(! empty($_POST['type'])){
			if(! is_numeric($_POST['type'])){
				http_error_code(400);
				echo "bad type";
				$have_error = true;
			} else $type = intval($_POST['type']);
		}
		
		if(! empty($_POST['owner'])){
			if(! is_numeric($_POST['owner'])){
				http_error_code(400);
				echo "bad owner";
				$have_error = true;
			} else $owner = intval($_POST['owner']);
		}

	}	

	if(! $have_error){

		try {
		$db = new SQLite3('gtamap.db');

		$stmt = $db->prepare("INSERT INTO regions ( name, type, owner, popup, color) VALUES ( :name, :type, :owner, :popup, :color);");

		$stmt->bindValue(':name', $name, SQLITE3_TEXT);
		$stmt->bindValue(':popup', $popup, SQLITE3_TEXT);
		$stmt->bindValue(':type', $type, SQLITE3_INTEGER);
		$stmt->bindValue(':owner', $owner, SQLITE3_INTEGER);
		$stmt->bindValue(':color', $color, SQLITE3_TEXT);

		$stmt->execute();

		$indx = 0;
		foreach($points as $point){
		$stmt = $db->prepare("INSERT INTO region_points ( region_name, indx, x_pos, y_pos ) VALUES ( :region_name, :indx, :x_pos, :y_pos );");

		$stmt->bindValue(':region_name', $name, SQLITE3_TEXT);
		$stmt->bindValue(':indx', $indx, SQLITE3_INTEGER);
		$stmt->bindValue(':x_pos', $point[0], SQLITE3_FLOAT);
		$stmt->bindValue(':y_pos', $point[1], SQLITE3_FLOAT);

		$stmt->execute();
		}

		echo "OK";
		} catch(Exception $ex){
			echo "Error: " . $ex->getMessage();
		}
	}
?>
