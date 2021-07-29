<?php
	$have_error = false;
	
	if($_SERVER['REQUEST_METHOD'] != 'POST'){
		http_response_code(400);
		echo "wrong request";
		
		$have_error = true;
	}

	$id_str = '';
	$id = -1;

	if( empty($_POST['id'])){
		http_response_code(400);
		echo "missing ID";
		
		$have_error = true;
	}

	if(! $have_error){
		$id_str = $_POST['id'];
		
		if(! is_numeric($id_str)){
			http_response_code(400);
			echo "invalid ID";

			$have_error = true;
		} else {
			$id = intval($id_str);
		}	
	}

	if(! $have_error){
		try {
		$db = new SQLite3('gtamap.db');

		$stmt = $db->prepare("DELETE FROM markers WHERE id = :id ;");
		$stmt->bindvalue(':id', $id, SQLITE3_INTEGER);

		$stmt->execute();

		echo "OK";
		} catch(Exception $ex){
			echo "Error: " . $ex->getMessage();
		}
	}
?>
