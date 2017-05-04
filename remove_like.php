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
	$stmt = $dbh->prepare("SELECT UserID FROM User WHERE Login=?");
	$stmt->execute(array($_SESSION['logged_user']));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	if (!$row)
		return ;
	$sth = $dbh->prepare("DELETE FROM Heart WHERE UserID=? AND PhotoID=?");
	$sth->execute(array($row['UserID'], $_POST['PhotoID']));
	$sm = $dbh->prepare("UPDATE Photo SET Likes = Likes - 1 WHERE PhotoID=?");
	$sm->execute(array($_POST['PhotoID']));
	unset($sm);
	unset($sth);
	unset($stmt);
	$dbh = null;
?>
