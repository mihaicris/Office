<?php

	require_once('external/firephp/fb.php');

	$limit   = 10;
	$counter = 0;
	$db_host = "mihaicris.go.ro";
	$db_name = "office";
	$db_user = 'root';
	$db_pass = 'mihai123';
	// Conectare la baza de date

	function interogare($string, $data)
	{
		global $db;
		try {
			$query = $db->prepare($string);
			if(empty($data)) {
				$query->execute();
			} else {
				$query->execute($data);
			}
		} catch(Exception $e) {
			echo('<span class="error">' . $e->getMessage() . '</span>');
			exit();
		}

		return $query;
	}

	while(true) {
		try {
			$db = new PDO('mysql:host=' . $db_host . ';dbname=' . $db_name, $db_user, $db_pass);
			$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
			$db->setAttribute(PDO::ATTR_PERSISTENT, true);
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			break;
		} catch(PDOException $e) {
			$db = NULL;
			$counter++;
			if($counter == $limit)
				throw $e; // după câteva încercări se afiseaza eroarea
		}
	}
?>