<?php
	session_start();
	if (isset($_SESSION['logged_user']) && $_SESSION['logged_user'] !== "")
	{
		function imagecopymerge_alpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct){
			// creating a cut resource
			$cut = imagecreatetruecolor($src_w, $src_h);
			// copying relevant section from background to the cut resource
			imagecopy($cut, $dst_im, 0, 0, $dst_x, $dst_y, $src_w, $src_h);
			// copying relevant section from watermark to the cut resource
			imagecopy($cut, $src_im, 0, 0, $src_x, $src_y, $src_w, $src_h);
			// insert cut resource to destination image
			imagecopymerge($dst_im, $cut, $dst_x, $dst_y, 0, 0, $src_w, $src_h, $pct);
		}
		$filter = "./filters/" . $_POST['filter'] . ".png";
		// $photo = $_POST['src'];
		$photo = str_replace(' ','+', $_POST['src']);
		list($type, $photo) = explode(';', $photo);
		list(, $photo)      = explode(',', $photo);
		$photo = base64_decode($photo);
		if (is_dir("./previews"))
		{
			foreach (glob("./previews"."/*.*") as $filename)
			{
				if (is_file($filename))
					unlink($filename);
			}
		}
		if (!is_dir("./previews"))
			mkdir("./previews", 0777, true);
		file_put_contents('./previews/tmp.png', $photo);
		$src = imagecreatefrompng($filter);
		$dest = imagecreatefrompng("./previews/tmp.png");
		imagecopymerge_alpha($dest, $src , 140 , 30 , 0 , 0 , 100 , 100 , 100 );
		unlink("./previews/tmp.png");
		imagepng($dest, './previews/'.date("Y-m-d-H-i-s").'.png');
		echo (base64_encode(file_get_contents("./previews/".date("Y-m-d-H-i-s").".png")));
	}
?>
