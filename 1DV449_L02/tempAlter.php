<?php


	$db = null;
	
	try {
		$db = new PDO("sqlite:db.db");
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch(PDOEception $e) {
		die("Something went wrong -> " .$e->getMessage());
	}
	$q = "ALTER TABLE messages ADD COLUMN timestamp INTEGER";
	try {
		if(!$db->query($q)) {
			die("Fel vid insert");
		}
	}
	catch(PDOException $e) {
		die("Something went wrong -> " .$e->getMessage());
	}
echo "Seeems okkii!";