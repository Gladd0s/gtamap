<?php
	$have_error = false;
	
	if($_SERVER['REQUEST_METHOD'] != 'POST'){
		http_response_code(400);
		echo "wrong request";
		
		$have_error = true;
	}

	$x_str = '';
	$y_str = '';

	$x_pos = 0.0;
	$y_pos = 0.0;
	
	$draggable = false;
	$popup = '';
	$icon = '';

	$type = -1;
	$owner = -1;

	if( empty($_POST['x_pos']) ||
		empty($_POST['y_pos'])) {
		http_response_code(400);
		echo "missing coordinates";
		
		$have_error = true;
	}

	if(! $have_error){
		$x_str = $_POST['x_pos'];
		$y_str = $_POST['y_pos'];
		
		if(! is_numeric($x_str) || ! is_numeric($y_str)){
			http_response_code(400);
			echo "invalid coordinates";

			$have_error = true;
		} else {
			$x_pos = floatval($x_str);
			$y_pos = floatval($y_str);
		}	
	}

	if(! $have_error){
		if(! empty($_POST['popup'])){
			$popup = $_POST['popup'];
		}

		if(! empty($_POST['icon'])){
			$icon = $_POST['icon'];
		}

		if(isset($_POST['draggable']) && $_POST['draggable'] == 'Yes'){
			$draggable = true;
		}
		
		try {
		$db = new SQLite3('gtamap.db');

		$stmt = $db->prepare("INSERT INTO markers ( x_pos, y_pos, draggable, icon, popup, type, owner ) VALUES ( :x_pos, :y_pos, :draggable, :icon, :popup, :type, :owner);");

		$stmt->bindValue(':x_pos', $x_pos, SQLITE3_FLOAT);
		$stmt->bindValue(':y_pos', $y_pos, SQLITE3_FLOAT);
		$stmt->bindValue(':draggable', $draggable ? 1 : 0, SQLITE3_INTEGER);
		$stmt->bindValue(':icon', $icon, SQLITE3_TEXT);
		$stmt->bindValue(':popup', $popup, SQLITE3_TEXT);
		$stmt->bindValue(':type', $type, SQLITE3_TEXT);
		$stmt->bindValue(':owner', $owner, SQLITE3_TEXT);

		$stmt->execute();

		echo "OK";
		} catch(Exception $ex){
			echo "Error: " . $ex->getMessage();
		}
	}
?>
