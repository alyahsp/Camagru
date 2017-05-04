<?php
session_start();
if (isset($_SESSION['logged_user']) && $_SESSION['logged_user'] !== "")
{
	require_once('./config/database.php');
	try{
		$dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}catch (PDOException $e){
		echo 'Connection failed: ' . $e->getMessage();
		exit;
	}
	function imagecopymerge_alpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct){
		$cut = imagecreatetruecolor($src_w, $src_h);
		imagecopy($cut, $dst_im, 0, 0, $dst_x, $dst_y, $src_w, $src_h);
		imagecopy($cut, $src_im, 0, 0, $src_x, $src_y, $src_w, $src_h);
		imagecopymerge($dst_im, $cut, $dst_x, $dst_y, 0, 0, $src_w, $src_h, $pct);
	}
	$filter = "./filters/" . $_POST['filter'] . ".png";
	$photo = str_replace(' ','+', $_POST['src']);
	list($type, $photo) = explode(';', $photo);
	list(, $photo)      = explode(',', $photo);
	$photo = base64_decode($photo);
	if (!is_dir("./save"))
		mkdir("./save", 0777, true);
	file_put_contents('./save/tmp.png', $photo);
	$src = imagecreatefrompng($filter);
	$dest = imagecreatefrompng("./save/tmp.png");
	imagecopymerge_alpha($dest, $src , 140 , 30 , 0 , 0 , 100 , 100 , 100 );
	unlink("./save/tmp.png");
	$name = './save/'.date("Y-m-d-H-i-s").'.png';
	imagepng($dest, $name);
	$sth = $dbh->prepare("SELECT UserID FROM User WHERE Login=?");
	$sth->execute(array($_SESSION['logged_user']));
	$row = $sth->fetch(PDO::FETCH_ASSOC);
	if (!$row)
	{
		echo "Something went wrong";
		return ;
	}
	$sth = $dbh->prepare("INSERT INTO Photo VALUES (NULL, ?, ?, 0)");
	$sth->execute(array($row['UserID'], $name));
	$sth = null;
	$dbh = null;
	$row = null;
}
?>
