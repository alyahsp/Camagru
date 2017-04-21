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
		$stmt = $dbh->prepare("SELECT * FROM User WHERE Login=? AND Password=? AND Active=True");
		$stmt->execute(array($_POST['login'], hash('whirlpool', $_POST['passwd'])));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		if (!$row)
		{
			echo "Invalid Login or Password";
			return;
		}
		else
		{
			$_SESSION['logged_user'] = $_POST['login'];
			header('Location: gallery.php');
		}
	}
	else
	{
		echo "An error has occurred";
	}
?>
