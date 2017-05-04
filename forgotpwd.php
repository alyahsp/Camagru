<?php
	require_once('./config/database.php');
	session_start();
	$_SESSION['logged_user'] = "";
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
				<h3>Forgot your password?</h3>
				<form action="forgotpwd.php" method="post">
					<input class="txtbox" type="email" name="email" placeholder="Email" placeholder="E-mail" value=""/><br/>
					<input class="button" type="submit" name="submit" value="Reset Password" /> <br />
				</form>
			</div>
		</div>
	</body>
</html>
<?php
	try{
		$dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}catch (PDOException $e){
		echo 'Connection failed: ' . $e->getMessage();
		exit;
	}
	if ($_POST['submit'] === "Reset Password")
	{
		if (!$_POST['email'])
		{
			echo "<p>Please enter a valid email</p>";
			// header('Location: forgotpwd.php');
			return ;
		}
		$sth = $dbh->prepare("SELECT * FROM User WHERE Email=?");
		$sth->execute(array($_POST['email']));
		$row = $sth->fetch(PDO::FETCH_ASSOC);
		if (!$row)
		{
			echo "Please enter a valid email";
			// header('Location: forgotpwd.php');
			return ;
		}
		else
		{
			$emailTo = $_POST['email'];
			$emailFrom = 'team@camagru.com';
			$subject = "Camagru - Reset Password";
			$message = "To reset your password, click on the link below <br/> <a href='http://localhost:8080/Camagru/reset.php?uid=" . $row['UserID'] . "'>Reset Password</a>";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			mail($emailTo, $subject, $message, $headers);
		}
	}
?>
