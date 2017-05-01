<?php
session_start();
require_once('./config/database.php');
try{
	$dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch (PDOException $e){
	echo 'Connection failed: ' . $e->getMessage();
	exit;
}
$stmt = $dbh->prepare("SELECT UserID FROM User Where Login=?")
?>
