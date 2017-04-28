<?php
	session_start();
	if (isset($_SESSION['logged_user']) && $_SESSION['logged_user'] !== "")
	{
		$filter = "./filters/" . $_POST['filter'] . ".png";
		$photo = $_POST['src'];
		// list($type, $photo) = explode(';', $photo);
		// list(, $photo) = explode(',', $photo);
		// print_r($photo);
		$photo = str_replace(' ','+', $photo);
		list($type, $photo) = explode(';', $photo);
		list(, $photo)      = explode(',', $photo);
		$photo = base64_decode($photo);
		if (!is_dir("./save"))
			mkdir("./save", 0777, true);
		file_put_contents('./save/tmp.png', $photo);
		$size = getimagesize("./save/tmp.png");
		// file_put_contents("tmp.png", base64_decode($photo[1]));
		$src = imagecreatefrompng($filter);
		// $dest = imagecreatefrompng($photo);
		// size =
		$dest = imagecreatefrompng("./save/tmp.png");
		imagecopymerge ($dest, $src , 0 , 0 , 0 , 0 , 100 , 100 , 100 );
		imagepng($dest, './save/image.png');

	}
?>
