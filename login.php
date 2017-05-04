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
	if ($_POST['submit'] === "Login")
	{
		$stmt = $dbh->prepare("SELECT * FROM User WHERE Login=? AND Password=?");
		$stmt->execute(array($_POST['login'], hash('whirlpool', $_POST['passwd'])));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		if (!$row)
		{
			echo "<p>Invalid Login or Password<p>";
			$stmt = null;
			$dbh = null;
			include "index.php";
			return;
		}
		else if ($row['Active'] == True)
		{
			$_SESSION['logged_user'] = $_POST['login'];
			$row = null;
			$stmt = null;
			$dbh = null;
			header('Location: gallery.php');
		}
		else
		{
			echo "<p>Check your email to confirm your account<p>";
			$stmt = null;
			$dbh = null;
			include "index.php";
			return;
		}
	}
	else
	{
		echo "An error has occurred";
	}
?>
