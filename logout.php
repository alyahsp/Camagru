<?php
session_start();
if ($_SESSION['logged_user'] !== "")
	$_SESSION['logged_user'] = "";
header('Location: index.php');
?>
