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
	if ($_SESSION['logged_user'] === "" || !isset($_SESSION['logged_user']))
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
	<div id="gallery">
	<?php
		$stmt = $dbh->prepare("SELECT * FROM Photo ORDER BY PhotoID DESC");
		$stmt->execute();
		$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if (!$row)
			echo "<h2>No Pictures Yet</h2>";
		else
		{
			foreach ($row as $pic)
			{
				echo "<div id='pic'>";
				echo "<img src='". $pic['PicURL'] ."'>";
				echo "</div>";
			}
		}
	?>
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
		<title>Camagru - Gallery</title>
		<link rel="stylesheet" href="css/header.css">
		<link rel="stylesheet" href="css/gallery.css">
		<script>
		function add_heart(photoID)
		{
			var xhr = new XMLHttpRequest;
			var sending = "PhotoID=" + photoID;
			xhr.open("POST", "add_like.php", true);
			xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhr.send(sending);
			xhr.onreadystatechange = function() {
				if(xhr.readyState < 4)
					return;
				if(xhr.status !== 200)
					return;
				else if (xhr.readyState == 4)
					window.location.reload();
		}

		</script>
	</head>
	<body>
	<?php
		include "header.php";
	?>
	<div id="gallery">
	<?php
		$stmt = $dbh->prepare("SELECT P.PhotoID as PhotoID, PicURL, Likes,
			H.HeartID as HeartID, U.Login as Login, H.Hearted as Hearted
			FROM Photo P
			JOIN User U ON P.UserID = U.UserID
			LEFT JOIN Heart H ON U.UserID = H.UserID
			LEFT JOIN Comment C ON P.PhotoID = C.PhotoID
			ORDER BY PhotoID DESC");
		$stmt->execute();
		$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if (!$row)
			echo "<h2>No Pictures Yet</h2>";
		else
		{
			foreach ($row as $pic)
			{
				echo "<div id='pic'>";
				echo "<img id='img' src='". $pic['PicURL'] ."'><br/>";
				if (!isset($pic['Hearted']))
					echo "<img id='heart' onclick='add_heart(". $pic['PhotoID']. ")' width='50' height='50' src='./img/heart.svg'><br/>";
				echo "<p>".$pic['Likes']." likes</p>";
				// else
				// 	echo "<img width='50' height='50' src='./img/hearted.svg'";
				echo "</div>";
			}
		}
	?>
	</div>
	</body>
</html>
<?php
	}
?>
