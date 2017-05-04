<?php
session_start();
if ($_SESSION['logged_user'] !== "" && isset($_SESSION['logged_user']))
{
	if (!$_POST['passwd'])
	{
		echo "Password field cannot be empty";
		include "account.php";
		return;
	}
	if ($_POST['submit'] === "Delete Account")
	{
		require_once('./config/database.php');
		try{
			$dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
			$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}catch (PDOException $e){
			echo 'Connection failed: ' . $e->getMessage();
			exit;
		}
		$stmt = $dbh->prepare("SELECT * FROM User WHERE Login=? AND Password=? AND Active=True");
		$stmt->execute(array($_SESSION['logged_user'], hash('whirlpool', $_POST['passwd'])));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		if (!$row)
		{
			echo "Invalid Password";
			$stmt = null;
			$dbh = null;
			include "account.php";
			return;
		}
		if (hash('whirlpool', $_POST['passwd']) === $row['Password'])
		{
			$sth = $dbh->prepare("DELETE FROM User WHERE Login=?");
			$sth->execute(array($_SESSION['logged_user']));
			$_SESSION['loggued_on_user'] ="";
			echo "Your account was deleted\n";
			include "index.php";
			unset($stmt);
			unset($row);
			unset($sth);
			$dbh = null;
			return;
		}
	}
}
else
	include "index.php";
?>
