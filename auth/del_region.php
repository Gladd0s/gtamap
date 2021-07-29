<?php
	$have_error = false;
	
	if($_SERVER['REQUEST_METHOD'] != 'POST'){
		http_response_code(400);
		echo "wrong request";
		
		$have_error = true;
	}

	$name_str = '';
	$name = -1;

	if( empty($_POST['name'])){
		http_response_code(400);
		echo "missing ID";
		
		$have_error = true;
	}

	if(! $have_error){
		$name_str = $_POST['name'];
		
		if(! is_numeric($name_str)){
			http_response_code(400);
			echo "invalid name";

			$have_error = true;
		} else {
			$name = intval($name_str);
		}	
	}

	if(! $have_error){
		try {
		$db = new SQLite3('gtamap.db');

		$stmt = $db->prepare("DELETE FROM regions WHERE name = :name ;");
		$stmt->bindvalue(':name', $name, SQLITE3_INTEGER);

		$stmt->execute();
		
		$stmt = $db->prepare("DELETE FROM region_points WHERE region_name = :region_name ;");
		$stmt->bindvalue(':region_name', $name, SQLITE3_INTEGER);

		$stmt->execute();

		echo "OK";
		} catch(Exception $ex){
			echo "Error: " . $ex->getMessage();
		}
	}
?>
