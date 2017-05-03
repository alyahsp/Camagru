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
	<link rel="stylesheet" href="css/gallery.css">
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
		$stmt = $dbh->prepare("SELECT P.PhotoID as PhotoID, PicURL, Likes,
			U.Login as Login FROM Photo P JOIN User U ON P.UserID = U.UserID
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
				echo "<p id='name'>" . $pic['Login'] . "</p>";
				echo "<img id='img' src='". $pic['PicURL'] ."'><br/>";
				echo "<p id='likes'>".$pic['Likes']." likes</p>";
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
		}

		function remove_heart(photoID)
		{
			var xhr = new XMLHttpRequest;
			var sending = "PhotoID=" + photoID;
			xhr.open("POST", "remove_like.php", true);
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
		}

		function subComment(e, photoID) {
			if (e.keyCode == 13) {
				var comment = document.getElementById('c' + photoID).value;
				var xhr = new XMLHttpRequest;
				var sending = "PhotoID=" + photoID + "&comment=" + comment;
				xhr.open("POST", "post_comment.php", true);
				xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xhr.send(sending);
				xhr.onreadystatechange = function() {
					if(xhr.readyState < 4)
						return;
					if(xhr.status !== 200)
						return;
					else if (xhr.readyState == 4)
					{
						console.log('why not?');
						console.log('why not?');
						window.location.reload();
					}
				}
				return false;
			}
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
				$st = $dbh->prepare("SELECT UserID FROM User WHERE Login=?");
				$st->execute(array($_SESSION['logged_user']));
				$rw = $st->fetch(PDO::FETCH_ASSOC);
				$sth = $dbh->prepare("SELECT * FROM Heart WHERE PhotoID=? AND UserID=?");
				$sth->execute(array($pic['PhotoID'], $rw['UserID']));
				$tab = $sth->fetch(PDO::FETCH_ASSOC);
				$s = $dbh->prepare("SELECT Content, U.Login as Login FROM Comment C INNER JOIN User U ON C.UserID=U.UserID WHERE PhotoID=?");
				$s->execute(array($pic['PhotoID']));
				$com = $s->fetchAll(PDO::FETCH_ASSOC);
				echo "<div id='pic'>";
				echo "<p id='name'>" . $pic['Login'] . "</p>";
				echo "<img id='img' src='". $pic['PicURL'] ."'><br/>";
				echo "<p id='likes'>".$pic['Likes']." likes</p>";
				if ($com)
				{
					foreach($com as $comment)
						echo "<div><p id='name'>" . $comment['Login'] . "<span class='com'> ".$comment['Content']."</span></p></div>";
				}
				if (!$tab)
					echo "<section><img id='heart' onclick='add_heart(". $pic['PhotoID']. ")' width='30' height='30' src='./img/heart.svg'>";
				else
					echo "<section><img id='heart' onclick='remove_heart(". $pic['PhotoID']. ")' width='30' height='30' src='./img/hearted.svg'>";
				echo "<input id='c". $pic['PhotoID'] ."' type='text' onkeypress='return subComment(event, " .$pic['PhotoID']. ")' class='comment' aria-label='Add a comment…' placeholder='Add a comment…'' value=''></section>";
				echo "</div>";
				unset($tab);
			}
		}
	?>
	</div>
	</body>
</html>
<?php
	}
?>
