<?php
	define("ROOT_SERVER", $_SERVER['DOCUMENT_ROOT'] . "/");

	error_reporting(E_ALL);
	ini_set("display_errors", 1);

	require_once(ROOT_SERVER . "library/config.php");
	require_once(ROOT_SERVER . "library/classes/Strings.php");
	require_once(ROOT_SERVER . "library/classes/Image.php");
	require_once(ROOT_SERVER . "models/Gallery.php");

	$gallery = new Gallery();
	$image = new Image();
	$images = $gallery->getImages();

	Strings::dump($images);

	foreach ($images as $path)
	{
		Strings::dump($image->load($path));
	}


?>