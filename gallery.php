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
		</div>
		<div class="side">
		</div>
	</div>
	<div class="footer">
		<p class="sp">spalmaro 2017</p>
	</div>
	</body>
</html>
<?php
	}
	else
		header('Location: index.php');
?>
