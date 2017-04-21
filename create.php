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
	if (isset($_GET['uid']))
	{
		$sth = $dbh->prepare("SELECT * FROM User WHERE Login=?");
		$sth->execute(array($_GET['uid']));
		$row = $sth->fetch(PDO::FETCH_ASSOC);
		if (!$row)
		{
			echo "An ERROR has occured!\n";
			return;
		}
		$sth = $dbh->prepare("UPDATE User SET Active=True WHERE Login=?");
		$sth->execute(array($_GET['uid']));
		header('Location: index.php');
	}
	else
	{
		echo "ERROR";
	}
?>
