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
				<button id="startbutton">Create Preview</button>
			</div>
			<div class="filter">
				<form action="add_filter.php" method="post">
					<input type="radio" name="filter" value="crown" checked>
						<img width="100" height="100" src="./filters/crown.png">
					<input type="radio" name="filter" value="kiss">
						<img width="100" height="90" src="./filters/kiss.png">
					<input type="radio" name="filter" value="eyes">
						<img width="90" height="90" src="./filters/eyes.png">
				</form>
			</div>
			<img id="photo">
		</div>
		<canvas id="canvas"></canvas>
		<div class="side">
			<img id="savedphoto">
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
