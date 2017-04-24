<?php
	session_start();
	if ($_SESSION['logged_user'] !== "")
	{
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Camagru - Montage</title>
		<link rel="stylesheet" href="css/header.css">
		<link rel="stylesheet" href="css/montage.css">
	</head>
	<body>
	<?php
		include "header.php";
	?>
	<div class="divider">
		<div class="main">
			<div class="camera">
				<video id="video"></video>
			</div>
			<div class="filter">
				<form action="add_filter.php" method="post">
					<input type="submit" id="startbutton" value = "Prendre une photo">
					<input type="radio" name="filter" value="crown" checked>
						<img width="100" height="100" src="./img/crown.png">
					<input type="radio" name="filter" value="kiss">
						<img width="100" height="100" src="./img/kiss.png">
					<input type="radio" name="filter" value="eyes">
						<img width="100" height="100" src="./img/eyes.png">
				</form>
			</div>
		</div>
		<canvas id="canvas"></canvas>
		<div class="side">
			<img id="photo">
		</div>
	</div>
	<div class="footer">
		<p class="sp">spalmaro 2017</p>
	</div>
	</body>
		<script src="cam.js"> </script>
</html>
<?php
	}
	else
		header('Location: index.php');
?>
