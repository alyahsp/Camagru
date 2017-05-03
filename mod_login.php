<?php
session_start();
if ($_SESSION['logged_user'] !== "" && isset($_SESSION['logged_user']))
{
	if (!$_POST['login'])
	{
		echo "Login cannot be an empty string";
		include "account.php";
		return;
	}
	require_once('./config/database.php');
	try{
		$dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}catch (PDOException $e){
		echo 'Connection failed: ' . $e->getMessage();
		exit;
	}
	if ($_POST['submit'] === "Change Login")
	{
		$stmt = $dbh->prepare("SELECT * FROM User WHERE Login=?");
		$stmt->execute(array($_POST['login']));
		$taken = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($taken)
		{
			echo "This username is already taken.";
			include "account.php";
			return;
		}
		else {
			$sth = $dbh->prepare("UPDATE User SET Login=? WHERE Login=?");
			$sth->execute(array($_POST['login'], $_SESSION['logged_user']));
			$_SESSION['logged_user'] = $_POST['login'];
			include "account.php";
			unset($sth);
			return ;
		}
		unset($stmt);
		unset($taken);
	}
}
else
	header('Location: index.php');
?>
