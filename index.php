<?php
	require_once('./config/setup.php');
	session_start();
	if (isset($_SESSION['logged_user']) && $_SESSION['logged_user'] !== "")
		header('Location: gallery.php');
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Camagru</title>
		<link rel="stylesheet" href="css/index.css">
	</head>
	<body>
		<div class="connect_boxes">
			<a href="gallery.php">
				<img alt="Camagru" src="img/Camagru.png">
			</a>
			<div class="new_client">
				<h3>Create Account</h3>
				<form action="email.php" method="post">
					<input class="txtbox" type="text" name="login" placeholder="Login" placeholder="Username" value=""/><br/>
					<input class="txtbox" type="password" name="passwd" placeholder="Password" value=""/><br/>
					<input class="txtbox" type="email" name="email" placeholder="Email" placeholder="E-mail" value=""/><br/>
					<input class="button" type="submit" name="submit" value="Sign Up" /> <br />
				</form>
			</div>
			<div class="divider">
				<p class="or">or</p>
				<div class="line"></div>
			</div>
				<h3>Already have an <br/>account?</h3>
				<form action="login.php" method="post">
					<input class="txtbox" type="text" name="login" placeholder="Username" value="" /><br/>
					<input class="txtbox" type="password" name="passwd" placeholder="Password" value=""/><br/>
					<input class="button" type="submit" name="submit" value="Login" /> <br />
				</form>
				<a id='forgot' href="forgotpwd.html">Forgot Password?</a>
		</div>
	</body>
</html>
