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
$stmt = $dbh->prepare("DELETE p FROM Photo p INNER JOIN User u
	ON p.UserID= u.UserID WHERE PhotoID=? AND u.Login=?");
$stmt->execute(array($_POST['photo'], $_SESSION['logged_user']));
?>
