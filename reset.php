<?php
require_once('./config/database.php');
session_start();
try{
	$dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch (PDOException $e){
	echo 'Connection failed: ' . $e->getMessage();
	exit;
}
if (isset($_GET['uid']))
{
	if ($_POST['submit'] === "Reset Password")
	{
		$sth = $dbh->prepare("SELECT * FROM User WHERE UserID=?");
		$sth->execute(array($_GET['uid']));
		$row = $sth->fetch(PDO::FETCH_ASSOC);
		if (!$row || ($_POST['passwd'] !== $_POST['cfmpasswd'] || !$_POST['passwd']))
		{
			echo "An ERROR has occured!\n";
			include "index.php";
			return;
		}
		if (strlen($_POST['passwd']) < 6) {
			echo "Password too short!";
			include "index.php";
			return ;
		}
		if (!preg_match("#[0-9]+#", $_POST['passwd'])) {
			echo "Password must include at least one number!";
			include "index.php";
			return ;
		}
		if (!preg_match("#[a-zA-Z]+#", $_POST['passwd'])) {
			echo "Password must include at least one letter!";
			include "index.php";
			return ;
		}
		$sth = $dbh->prepare("UPDATE User SET Password=? WHERE UserID=?");
		$sth->execute(array(hash('whirlpool', $_POST['passwd']), $_GET['uid']));
		header('Location: index.php');
	}
}
else
{
	echo "ERROR";
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Forgot Account?</title>
		<link rel="stylesheet" href="css/index.css">
	</head>
	<body>
		<div class="connect_boxes">
			<a href="index.php">
				<img alt="Camagru" src="img/Camagru.png">
			</a>
			<div class="new_client">
				<h3>Please enter a new<br/>password</h3>
				<form method="post">
					<input class="txtbox" type="password" name="passwd" placeholder="New Password" value=""/><br/>
					<input class="txtbox" type="password" name="cfmpasswd" placeholder="Confirm New Password" value=""/><br/>
					<input class="button" type="submit" name="submit" value="Reset Password" /> <br />
				</form>
			</div>
		</div>
	</body>
</html>
