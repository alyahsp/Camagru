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
						window.location.reload();
				}
				return false;
			}
		}
		</script>
	</head>
	<body>
	<?php
	if ($_SESSION['logged_user'] !== "" && isset($_SESSION['logged_user']))
		include "header.php";
	else {
		echo "<div class='header'>
			<div class='logo'>
					<a href='gallery.php'>
						<img alt='Camagru' src='img/Camagru.png'>
					</a>
				</div>
				<div class='logout'>
					<a href='index.php'>
						<img class='lgt' alt='Login' src='img/login.svg'>
					</a>
				</div>
				<div class='footer'>
					<p class='sp'>spalmaro 2017</p>
				</div>
			</div>";
	}
	?>
	<div id="gallery">
	<?php
	$sth = $dbh->query("SELECT COUNT(PhotoID) as Total FROM Photo");
	$totalpics = $sth->fetch(PDO::FETCH_ASSOC);
	if (!$totalpics)
	{
		echo "<h2>No Pictures Yet</h2>";
		return ;
	}
	$rec_limit = 5;
	$rec_count = $totalpics['Total'];
	if (isset($_GET['page']))
	{
		$page = $_GET['page'] + 1;
		$offset = $rec_limit * ($page - 1);
	}
	else
	{
		$page = 1;
		$offset = 0;
	}

	$left_rec = $rec_count - ($page * $rec_limit);

	$stmt = $dbh->prepare("SELECT P.PhotoID as PhotoID, PicURL, Likes, U.Login as Login
		FROM Photo P JOIN User U ON P.UserID = U.UserID ORDER BY PhotoID DESC LIMIT $offset, $rec_limit");
	$stmt->execute();
	$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if (!$row)
		echo "<h2>No Pictures Yet</h2>";
	else
	{
		foreach ($row as $pic)
		{
			$thc = $dbh->prepare("SELECT HeartID FROM Heart H INNER JOIN User U ON H.UserID=U.UserID WHERE PhotoID=? AND U.Login=?");
			$thc->execute(array($pic['PhotoID'], $_SESSION['logged_user']));
			$tab = $thc->fetch(PDO::FETCH_ASSOC);
			$s = $dbh->prepare("SELECT Content, U.Login as Login FROM Comment C INNER JOIN User U ON C.UserID=U.UserID WHERE PhotoID=? ORDER BY CommentID ASC");
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
			if ($_SESSION['logged_user'] !== "" && isset($_SESSION['logged_user']))
			{
				if (empty($tab))
					echo "<section><img id='heart' onclick='add_heart(". $pic['PhotoID']. ")' width='30' height='30' src='./img/heart.svg'>";
				else
					echo "<section><img id='heart' onclick='remove_heart(". $pic['PhotoID']. ")' width='30' height='30' src='./img/hearted.svg'>";
				echo "<input id='c". $pic['PhotoID'] ."' type='text' onkeypress='return subComment(event, " .$pic['PhotoID']. ")' class='comment' aria-label='Add a comment…' placeholder='Add a comment…'' value=''></section>";
			}
			echo "</div>";
			unset($tab);
		}
	}
	if( $page > 1 && $left_rec > 0) {
		$last = $page - 2;
		echo "<a id='pagination' href = \"$_PHP_SELF?page=$last\">Back | </a>";
		echo "<a id='pagination' href = \"$_PHP_SELF?page=$page\">Next</a>";
	}
	else if( $page == 1 && $left_rec > 0)
	{
		echo $rec_count;
		echo "<a id='pagination' href = \"$_PHP_SELF?page=$page\">Next</a>";
	}
	else if ( $page != 1 && $left_rec < 1 ) {
		$last = $page - 2;
		echo "<a id='pagination' href = \"$_PHP_SELF?page=$last\">Back</a>";
	}
	$dbh = null;
	?>
	</div>
	</body>
</html>
