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

		function placefilter()
		{
			if (node)
				node.parentNode.removeChild(node);
			node = 0;
			var chosen;
			var filters = document.getElementsByName('filter');
			for (var i = 0; i < 5; i++)
			{
				if (filters[i].checked)
					chosen = filters[i].value;
			}
			var node = document.createElement("img");
			node.src = "filters/"+chosen+".png";
			node.id = "chosen";
			document.getElementById("main").appendChild(node);
			node.style.position = "absolute";
			node.style.left ="450px";
			node.style.top = "630px";
			node.style.width ="100px";
			node.style.height ="100px";
		}

		var xhr = new XMLHttpRequest;

		xhr.onreadystatechange = ensureReadiness;

		function ensureReadiness() {
			if(xhr.readyState < 4) {
				return;
			}
			if(xhr.status !== 200) {
				return;
			}
		}

		// function isfilter()
		// {
		// 	var chosen;
		// 	var filters = document.getElementsByName('filter');
		// 	for (var i = 0; i < 5; i++)
		// 	{
		// 		if (filters[i].checked)
		// 			chosen = filters[i].value;
		// 	}
		// 	var div = document.createElement("div");
		// 	div.id = "remove";
		// 	div = document.getElementById("divider").appendChild(div);
		// 	img = document.createElement("img");
		// 	img = div.appendChild(img);
		// 	img.src = "filters/"+chosen+".png";
		// 	img.style.position = "absolute";
		// 	img.style.left = String(ev.pageX + 1) + "px";
		// 	img.style.top = String(ev.pageY + 1) + "px";
		// }
		// function putfilter()
		// {
		//
		// }
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
				<form action="add_filter.php" method="post">
					<input type="radio" name="filter" value="crown" checked>
						<img width="55" height="55" src="./filters/crown.png">
					<input type="radio" name="filter" value="kiss">
						<img width="55" height="45" src="./filters/kiss.png">
					<input type="radio" name="filter" value="eyes">
						<img width="55" height="55" src="./filters/eyes.png">
					<input type="radio" name="filter" value="fire">
						<img width="55" height="55" src="./filters/fire.png">
					<input type="radio" name="filter" value="hearts">
						<img width="55" height="55" src="./filters/hearts.png">
				</form>
			</div>
			<img onmousemove="isfilter()" onclick="putfilter()" id="photo">
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
