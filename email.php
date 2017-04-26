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
			$stmt = $dbh->prepare("INSERT INTO User VALUES (NULL, ?, ?, ?, False)");
			$stmt->execute(array($_POST['login'], hash('whirlpool', $_POST['passwd']), $_POST['email']));
			$emailTo = $_POST['email'];
			$emailFrom = 'team@camagru.com';
			$subject = "Camagru - Confirm Your Account";
			$message = "To create your account, confirm by clicking on the link below <br/> <a href='http://localhost:8080/Camagru/create.php?uid=" . $_POST['login'] . "'>Confirm account</a>";
			echo $mesage . "\n";
			$headers = "From: " . $emailFrom . "\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			mail($emailTo, $subject, $message, $headers);
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
	<h1>Thank you for signing up!</h1><br/>
	<h2>An e-mail was sent to confirm your account!</h2>
	<div class="footer">
		<p class="sp">spalmaro 2017</p>
	</div>
	</body>
</html>
<?php
		}
	}
	else {
		echo "An error has occurred";
	}
?>
