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

		function del_img(val)
		{
			var xhr = new XMLHttpRequest;
			var sending = "photo=" + val;
			xhr.open("POST", "del_img.php", true);
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

		function put_filter()
		{
			var xhr = new XMLHttpRequest;
			var check;
			if (check = document.getElementById("photo"))
				check.parentNode.removeChild(check);
			var chosen = chosenfilter();
			var img = document.createElement("img");
			img.id = "photo";
			document.getElementById("preview").appendChild(img);
			var sending = "src=" + document.getElementById('file').files[0].name + "&filter=" + chosen;
			// xhr.open("POST", "add_filter.php", true);
			// xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			// xhr.send(sending);
			// xhr.onreadystatechange = function(){
			// if (this.readyState == 4 && this.status == 200)
			// 	photo.setAttribute('src', "data:image/png;base64,"+this.responseText);
			// }
			var save = document.getElementById("save");
			save.disabled = false;
			save.style.backgroundColor = 'rgba(56, 151, 240, 1.0)';
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
					window.location.reload();
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
				<button id="startbutton">Capture</button><p>or</p>
				<input id="file" type="file" value="Choose File"/>
				<button id="uploadbutton" onclick="put_filter()">Upload</button>
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
				$sth = $dbh->prepare("SELECT Photo.PhotoID, Photo.PicURL FROM Photo INNER JOIN User
					ON Photo.UserID= User.UserID WHERE User.Login=? ORDER BY Photo.PhotoID DESC");
				$sth->execute(array($_SESSION['logged_user']));
				$row = $sth->fetchAll(PDO::FETCH_ASSOC);
				foreach ($row as $pic)
				{
					echo "<button id='trash' onclick='del_img(this.value)' value='".$pic['PhotoID']."'><img width='30' height='30' src='img/trash.svg'></button>";
					echo "<img style='height:172.5px; width:230px;' src='".$pic['PicURL']."'><br />";
				}
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
