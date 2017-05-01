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
$stmt = $dbh->prepare("SELECT UserID FROM User Where Login=?");
$stmt->execute(array($_SESSION['logged_user']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$row)
	return ;
$st = $dbh->prepare("SELECT * FROM Heart WHERE UserID=? AND PhotoID=?");
$st->execute(array($row['UserID'], $_POST['PhotoID']));
$tab = $st->fetch(PDO::FETCH_ASSOC);
if (!$tab)
{
	$sth = $dbh->prepare("INSERT INTO Heart VALUES (NULL, ?, ?, True)");
	$sth->execute(array($row['UserID'], $_POST['PhotoID']));
	$sm = $dbh->prepare("UPDATE Photo SET Likes = Likes + 1 WHERE PhotoID=?");
	$sm->execute(array($_POST['PhotoID']));
	unset($sm);
	unset($sth);
}
unset($tab);
unset($stmt);
?>
