<?php
	session_start();
	if ($_SESSION['logged_user'] !== "" || !isset($_SESSION['logged_user']))
	{
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Camagru - Montage</title>
		<link rel="stylesheet" href="css/header.css">
		<link rel="stylesheet" href="css/montage.css">
		<script>

		var xhr = new XMLHttpRequest;
		xhr.onreadystatechange = ensureReadiness;

		function ensureReadiness() {
			if(xhr.readyState < 4) {
				return;
			}
			if(xhr.status !== 200) {
				return;
			}
			else if (xhr.readyState == 4)
				console.log(xhr.responseText);
		}

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

		function placefilter()
		{
			var check;
			if (check = document.getElementById("chosen"))
				check.parentNode.removeChild(check);
			check = 0;
			var chosen = chosenfilter();
			var node = document.createElement("img");
			node.src = "filters/"+chosen+".png";
			node.id = "chosen";
			document.getElementById("preview").appendChild(node);
			node.style.position = "absolute";
			node.style.left ="45%";
			node.style.top = "48%";
			node.style.width ="100px";
			node.style.height ="100px";
			node.draggable = "true";
			var save = document.getElementById("save");
			save.disabled = false;
		}

		function save_img()
		{
			var chosen = chosenfilter();
			var img = document.getElementById("photo");
			var data = "src=" + img.src + "&filter=" + chosen;
			xhr.open("POST", "add_filter.php", true);
			xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhr.send(data);
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
				<button id="startbutton" onclick="placefilter()">Create Preview</button>
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
				<button id="save" disabled onclick="save_img()" name="save">Save</button>
			</div>
		</div>
		<div class="side">
			<img id="savedphoto">
		</div>
	</div>
	<div class="footer">
		<p class="sp">spalmaro 2017</p>
	</div>
	</body>
		<script src="cam.js"></script>
</html>
<?php
	}
	else
	{
		echo "<p>Login or Create an account to access this page</p>";
		include "index.php";
	}
?>
