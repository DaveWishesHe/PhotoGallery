<?php
	define("ROOT_SERVER", $_SERVER['DOCUMENT_ROOT'] . "/");

	require_once(ROOT_SERVER . "library/classes/Strings.php");
	require_once(ROOT_SERVER . "models/Gallery.php");

	$gallery = new Gallery();
	$images = $gallery->getImages();

	Strings::dump($images);
?>