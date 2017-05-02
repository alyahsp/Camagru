<?php
session_start();
if ($_SESSION['logged_user'] !== "" && isset($_SESSION['logged_user']))
{
	require_once('./config/database.php');
	try{
		$dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}catch (PDOException $e){
		echo 'Connection failed: ' . $e->getMessage();
		exit;
	}
	$sth = $dbh->prepare("SELECT UserID FROM User WHERE Login=?");
	$sth->execute(array($_SESSION['logged_user']));
	$uid = $sth->fetch(PDO::FETCH_ASSOC);
	if (!$uid || !$_POST['comment'])
		return ;
	$stmt = $dbh->prepare("INSERT INTO Comment VALUES(NULL, ?, ?, ?)");
	$stmt->execute(array($uid['UserID'], $_POST['PhotoID'], $_POST['comment']));
	unset($uid);
	unset($sth);
	unset($stmt);
	$s = $dbh->prepare("SELECT U.Email as Email FROM User U
		JOIN Photo P ON U.UserID=P.UserID WHERE P.PhotoID=?");
	$s->execute(array($_POST['PhotoID']));
	$email = $s->fetch(PDO::FETCH_ASSOC);
	$emailTo = $email['Email'];
	$emailFrom = 'team@camagru.com';
	$subject = "Camagru - New Comment On Your Photo";
	$message = "A new comment was posted on one of your photos\n";
	$headers = "From: " . $emailFrom . "\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	mail($emailTo, $subject, $message, $headers);
}
?>
