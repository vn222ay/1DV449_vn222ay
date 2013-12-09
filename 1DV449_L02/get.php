<?php

//NEW
//Get last message
function getNewerMessage($lastId, $pid) {
	$db = null;
	try {
		$db = new PDO("sqlite:db.db");
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch(PDOEception $e) {
		die("Del -> " .$e->getMessage());
	}
	$q = "SELECT * FROM messages WHERE serial > ? AND pid = ? ORDER BY serial ASC";
	
	$result;
	$stm;
	try {
		$stm = $db->prepare($q);
		$stm->bindParam(1, $lastId, PDO::PARAM_INT);
		$stm->bindParam(2, $pid, PDO::PARAM_INT);
		$stm->execute();
		//$stm->bind_result($result);
		$result = $stm->fetchAll();
	}
	catch(PDOException $e) {
		echo("Error creating query: " .$e->getMessage());
		return false;
	}
	
	if($result) {
		$result[0]["message"] = htmlentities($result[0]["message"], ENT_QUOTES);
		$result[0]["name"] = htmlentities($result[0]["name"], ENT_QUOTES);
		return $result[0];
	}
	else {
	 	return false;
	}
}

// get the specific message
function getMessage($nr) {
	$db = null;
	try {
		$db = new PDO("sqlite:db.db");
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch(PDOEception $e) {
		die("Del -> " .$e->getMessage());
	}
	$q = "SELECT * FROM messages WHERE serial = ?";
	
	$result;
	$stm;	
	try {
		$stm = $db->prepare($q);
		$stm->bindParam(1, $nr, PDO::PARAM_INT);
		$stm->execute();
		$result = $stm->fetchAll();
	}
	catch(PDOException $e) {
		echo("Error creating query: " .$e->getMessage());
		return false;
	}
	
	if($result)
		return $result;
	else
	 	return false;
}


function getMessageIdForProducer($pid) {
	$db = null;

	try {
		$db = new PDO("sqlite:db.db");
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch(PDOEception $e) {
		die("Del -> " .$e->getMessage());
	}
	
	$q = "SELECT serial FROM messages WHERE pid = ?";
	
	$result;
	$stm;	
	try {
		$stm = $db->prepare($q);
		$stm->bindParam(1, $pid, PDO::PARAM_INT);
		$stm->execute();
		$result = $stm->fetchAll();
	}
	catch(PDOException $e) {
		echo("Error creating query: " .$e->getMessage());
		return false;
	}
	
	if($result)
		return $result;
	else
	 	return false;
}

function getProducer($id) {
	$db = null;

	try {
		$db = new PDO("sqlite:producerDB.sqlite");
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch(PDOEception $e) {
		die("Del -> " .$e->getMessage());
	}
	
	$q = "SELECT * FROM Producers WHERE producerID = ?";
	
	$result;
	$stm;	
	try {
		$stm = $db->prepare($q);
		$stm->bindParam(1, $id, PDO::PARAM_INT);
		$stm->execute();
		$result = $stm->fetchAll();
	}
	catch(PDOException $e) {
		echo("Error creating query: " .$e->getMessage());
		return false;
	}
	
	if($result)
		return $result[0];
	else
	 	return false;
}

function getProducers() {
	$db = null;

	try {
		$db = new PDO("sqlite:producerDB.sqlite");
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch(PDOEception $e) {
		die("Del -> " .$e->getMessage());
	}
	
	$q = "SELECT * FROM Producers";
	
	$result;
	$stm;	
	try {
		$stm = $db->prepare($q);
		$stm->execute();
		$result = $stm->fetchAll();
	}
	catch(PDOException $e) {
		echo("Error creating query: " .$e->getMessage());
		return false;
	}
	
	if($result)
		return $result;
	else
	 	return false;
}