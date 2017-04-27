<?php
	session_start();
	if (isset($_SESSION['logged_user']) && $_SESSION['logged_user'] !== "" && $_POST['save'] == "Save")
	{
		$filter = $_POST['filter'] . ".png";
		$dest = imagecreatefrompng('a.png'); //300 x 300
		$src = imagecreatefrompng('b.png');
	}
?>
