<?php
	require_once('./config/database.php');
	session_start();
	$_SESSION['logged_user'] = "";
	if ($_POST['submit'] === "Reset Password")
	{
		if (!$_POST['email'])
		{
			echo "Please enter a valid email";
			include "forgotpwd.html";
			return ;
		}
		try{
			$dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
			$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}catch (PDOException $e){
			echo 'Connection failed: ' . $e->getMessage();
			exit;
		}
		$sth = $dbh->prepare("SELECT * FROM User WHERE Email=?");
		$sth->execute(array($_POST['email']));
		$row = $sth->fetch(PDO::FETCH_ASSOC);
		if (!$row)
		{
			echo "Please enter a valid email";
			include "forgotpwd.html";
			return ;
		}
		else
		{
			$url = explode('MyWebSite/', getcwd());
			$emailTo = $_POST['email'];
			$emailFrom = 'team@camagru.com';
			$subject = "Camagru - Reset Password";
			$message = "To reset your password, click on the link below <br/> <a href='http://localhost:8080/" .$url[1]. "/reset.php?uid=" . $row['UserID'] . "'>Reset Password</a>";
			$headers = "From: " . $emailFrom . "\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			mail($emailTo, $subject, $message, $headers);
			$sth = null;
			$dbh = null;
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Camagru</title>
		<link rel="stylesheet" href="css/header.css">
	</head>
	<body>
		<div class="header">
			<div class="logo">
				<a href="gallery.php">
					<img alt="Camagru" src="img/Camagru.png">
				</a>
			</div>
			<div class="logout">
				<a href="index.php">
					<img class="lgn" alt="Login" src="img/login.svg">
				</a>
			</div>
			<div class="footer">
				<p class="sp">spalmaro 2017</p>
			</div>
		</div>
	<h2>An e-mail was sent to reset your password!</h2>
	<div class="footer">
		<p class="sp">spalmaro 2017</p>
	</div>
	</body>
</html>
<?php
		}
	}
?>
