<?php
	session_start();
	if ($_SESSION['logged_user'] !== "")
	{
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Camagru - Gallery</title>
		<link rel="stylesheet" href="css/header.css">
		<link rel="stylesheet" href="css/gallery.css">
	</head>
	<body>
	<?php
		include "header.php";
	?>
	<div class="main">
		
	</div>
	</body>
</html>
<?php
	}
	else
	{
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
					<img class="lgt" alt="Login" src="img/login.svg">
				</a>
			</div>
			<div class="footer">
				<p class="sp">spalmaro 2017</p>
			</div>
		</div>
<?php
	}
?>
