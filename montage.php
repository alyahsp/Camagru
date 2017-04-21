<?php
	session_start();
	if ($_SESSION['logged_user'])
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
			<video id="video"></video>
			<button id="startbutton">Prendre une photo</button>
			<canvas id="canvas"></canvas>
			<img src="http://placekitten.com/g/320/261" id="photo" alt="photo">
		</div>
		<div class="side">
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
