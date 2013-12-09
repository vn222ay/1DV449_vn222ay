<?php

/**
* Called from AJAX to add stuff to DB
*/
function addToDB($name, $message, $pid) {
	$db = null;
	
	try {
		$db = new PDO("sqlite:db.db");
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch(PDOEception $e) {
		die("Something went wrong -> " .$e->getMessage());
	}
	$stm;
	$q = "INSERT INTO messages (message, name, pid, timestamp) VALUES(?, ?, ?, ?)";
	try {
		$stm = $db->prepare($q);
		$stm->bindParam(1, $message, PDO::PARAM_STR, 300);
		$stm->bindParam(2, $name, PDO::PARAM_STR, 20);
		$stm->bindParam(3, $pid, PDO::PARAM_INT);
		$stm->bindParam(4, time(), PDO::PARAM_INT);
		$stm->execute();
	}
	catch(PDOException $e) {
		die("Something went wrong -> " .$e->getMessage());
	}
}
