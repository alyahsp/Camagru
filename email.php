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
	if ($_POST['submit'] === "Sign Up")
	{
		if (!$_POST['login'] || !$_POST['passwd'] || !$_POST['email'])
		{
			echo "Please enter a valid login/password/email";
			return ;
		}
		$sth = $dbh->prepare("SELECT * FROM User WHERE Login=? OR Email=?");
		$sth->execute(array($_POST['login'], $_POST['email']));
		$row = $sth->fetch(PDO::FETCH_ASSOC);
		if ($row)
		{
			echo 'Login or email already exists';
			return ;
		}
		else
		{
			$_SESSION['logged_user'] = $_POST['login'];
			$stmt = $dbh->prepare("INSERT INTO User VALUES (NULL, ?, ?, ?, False)");
			$stmt->execute(array($_POST['login'], hash('whirlpool', $_POST['passwd']), $_POST['email']));
			$emailTo = $_POST['email'];
			$emailFrom = 'team@camagru.com';
			$subject = "Camagru - Confirm Your Account";
			$message = "To create your account, confirm by clicking on the link below <br/> <a href='http://localhost:8080/Camagru/create.php?uid=" . $_POST['login'] . "'>Confirm account</a>";
			echo $mesage . "\n";
			// $headers = "From: $fromTitle <$emailFrom>\r\n";
			// $headers .= "Reply-To: " . $emailFrom . "\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			mail($emailTo, $subject, $message, $headers);
			echo "Email sent";
		}
	}
?>
