<?php
	session_start();
	if ($_SESSION['logged_user'] === "" || !isset($_SESSION['logged_user']))
	{
		echo "<p>Login or Create an account to access this page</p>";
		include "index.php";
		return;
	}
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
		<title>Camagru - Montage</title>
		<link rel="stylesheet" href="css/header.css">
		<link rel="stylesheet" href="css/montage.css">
		<script>

		function chosenfilter()
		{
			var chosen;
			var filters = document.getElementsByName('filter');
			for (var i = 0; i < 5; i++)
			{
				if (filters[i].checked)
					chosen = filters[i].value;
			}
			return chosen;
		}

		function save_img()
		{
			var xhr = new XMLHttpRequest;
			xhr.onreadystatechange = function() {
				if(xhr.readyState < 4) {
					return;
				}
				if(xhr.status !== 200) {
					return;
				}
				else if (xhr.readyState == 4)
					console.log(xhr.responseText);
			}
			var chosen = chosenfilter();
			var img = document.getElementById("photo");
			var tosend = "src=" + img.src + "&filter=" + chosen;
			xhr.open("POST", "save.php", true);
			xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhr.send(tosend);
		}
		</script>
	</head>
	<body>
	<?php
		include "header.php";
	?>
	<div class="divider" id="divider">
		<div id="main">
			<div class="camera">
				<video id="video"></video>
				<button id="startbutton">Create Preview</button>
			</div>
			<div class="filter">
				<form>
					<input type="radio" name="filter" value="flower" checked>
						<img width="55" height="55" src="./filters/flower.png">
					<input type="radio" name="filter" value="stars">
						<img width="55" height="55" src="./filters/stars.png">
					<input type="radio" name="filter" value="crown">
						<img width="55" height="55" src="./filters/crown.png">
					<input type="radio" name="filter" value="fire">
						<img width="55" height="55" src="./filters/fire.png">
					<input type="radio" name="filter" value="hearts">
						<img width="55" height="55" src="./filters/hearts.png">
				</form>
			</div>
			<canvas id="canvas"></canvas>
			<div id="preview">
				<img id="photo">
			</div>
			<button id="save" disabled onclick="save_img()">Save</button>
		</div>
		<div class="side">
			<?php
				$sth = $dbh->prepare("SELECT Photo.PicURL FROM Photo INNER JOIN User
					ON Photo.UserID= User.UserID WHERE User.Login=?");
				$sth->execute(array($_SESSION['logged_user']));
				$row = $sth->fetchAll(PDO::FETCH_COLUMN, 0);
				// print_r($row);
				// var_dump($row[0]);
				foreach ($row as $pic)
					echo "<img style='height:172.5px; width:230px;' src='".$pic."'><br />";
				// echo "<img width='240' height='180' src='" . $row[0] . "' ><br/>";
			?>
		</div>
	</div>
	<div class="footer">
		<p class="sp">spalmaro 2017</p>
	</div>
	</body>
		<script src="cam.js"></script>
</html>
