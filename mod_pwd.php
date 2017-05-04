<?php
session_start();
if ($_SESSION['logged_user'] !== "" && isset($_SESSION['logged_user']))
{
	if (!$_POST['oldpwd'] || !$_POST['newpwd'])
	{
		echo "Password fields cannot be empty";
		include "account.php";
		return;
	}
	if ($_POST['submit'] === "Change Password")
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
		$stmt->execute(array($_SESSION['logged_user'], hash('whirlpool', $_POST['oldpwd'])));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		if (!$row)
		{
			echo "Invalid Password";
			include "account.php";
			$stmt = null;
			$dbh = null;
			return;
		}
		if (strlen($_POST['newpwd']) < 6) {
			echo "Password too short!";
			include "account.php";
			$stmt = null;
			$row = null;
			$dbh = null;
			return ;
		}
		if (!preg_match("#[0-9]+#", $_POST['newpwd'])) {
			echo "Password must include at least one number!";
			include "account.php";
			$stmt = null;
			$row = null;
			$dbh = null;
			return ;
		}
		if (!preg_match("#[a-zA-Z]+#", $_POST['newpwd'])) {
			echo "Password must include at least one letter!";
			include "account.php";
			$stmt = null;
			$row = null;
			$dbh = null;
			return ;
		}
		$sth = $dbh->prepare("UPDATE User SET Password=? WHERE Login=?");
		$sth->execute(array(hash('whirlpool', $_POST['newpwd']), $_SESSION['logged_user']));
		echo "Password was changed";
		include "account.php";
		$stmt = null;
		$row = null;
		$sth = null;
		$dbh = null;
		return ;
	}
}
else
	header('Location: index.php');
?>
