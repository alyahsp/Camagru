<?php
	session_start();
	if (!isset($_SESSION['logged_user']) || $_SESSION['logged_user'] === "")
		header('Location: index.php');
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Camagru</title>
		<link rel="stylesheet" href="css/account.css">
		<link rel="stylesheet" href="css/header.css">
	</head>
	<?php include "header.php";?>
	<body>
		<div class="connect_boxes">
			<div class="new_client">
				<h3>Hello, <?=$_SESSION['logged_user'];?></h3>
				<form action="mod_login.php" method="post">
					<input class="txtbox" type="text" name="login" placeholder="Login" placeholder="Username" value="<?=$_SESSION['logged_user'];?>"/><br/>
					<input class="button" type="submit" name="submit" value="Change Login" /> <br />
				</form>
			</div>
			<div class="divider">
				<p class="or">or</p>
				<div class="line"></div>
			</div>
				<!-- <h3>Already have an <br/>account?</h3> -->
				<form action="mod_pwd.php" method="post">
					<input class="txtbox" type="text" name="login" placeholder="Old Password" value="" /><br/>
					<input class="txtbox" type="password" name="passwd" placeholder="New Password" value=""/><br/>
					<input class="button" type="submit" name="submit" value="Change Password" /> <br />
				</form>
			<div class="divider">
				<p class="or">or</p>
				<div class="line"></div>
			</div>
			<form action="del_acct.php" method="post">
				<input class="txtbox" type="password" name="passwd" placeholder="Password" value=""/><br/>
				<input class="button" type="submit" name="submit" value="Delete Account" /> <br />
			</form>
		</div>
	</body>
</html>
