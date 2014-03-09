<?php

require_once('external/firephp/fb.php');

function interogare($string, $data)
{
	global $db;
	try {
		$query = $db->prepare($string);
		if (empty($data)) {
			$query->execute();
		} else {
			$query->execute($data);
		}
	} catch (Exception $e) {
		echo('<span class="error">' . $e->getMessage() . '</span>');
		exit();
	}
	return $query;
}

function str_replace_assoc($subject, $scurt)
{
	if ($scurt) {
		$replace = array(
			'-01-' => '-Ian-',
			'-02-' => '-Feb-',
			'-03-' => '-Mar-',
			'-04-' => '-Apr-',
			'-05-' => '-Mai-',
			'-06-' => '-Iun-',
			'-07-' => '-Iul-',
			'-08-' => '-Aug-',
			'-09-' => '-Sep-',
			'-10-' => '-Oct-',
			'-11-' => '-Noi-',
			'-12-' => '-Dec-',
		);
	} else {
		$replace = array(
			'-01-' => ' ianuarie ',
			'-02-' => ' februarie ',
			'-03-' => ' martie ',
			'-04-' => ' aprilie ',
			'-05-' => ' mai ',
			'-06-' => ' iunie ',
			'-07-' => ' iulie ',
			'-08-' => ' august ',
			'-09-' => ' septembrie ',
			'-10-' => ' octombrie ',
			'-11-' => ' noiembrie ',
			'-12-' => ' decembrie ',
		);
	}
	return str_replace(array_keys($replace), array_values($replace), $subject);
}

$limit = 10;
$counter = 0;
$db_host = "localhost";
$db_name = "office";
$db_user = 'root';
$db_pass = 'mihai123';
// Conectare la baza de date
while (true) {
	try {
		$db = new PDO('mysql:host=' . $db_host . ';dbname=' . $db_name, $db_user, $db_pass);
		$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		$db->setAttribute(PDO::ATTR_PERSISTENT, true);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		break;
	} catch (PDOException $e) {
		$db = NULL;
		$counter++;
		if ($counter == $limit)
			throw $e; // după câteva încercări se afiseaza eroarea
	}
}