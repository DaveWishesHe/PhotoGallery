<?php
	class Image
	{
		protected $supported;

		public function __construct()
		{
			$this->supported = array("jpg" => "imagecreatefromjpeg", "jpeg" => "imagecreatefromjpeg", "png" => "imagecreatefrompng");
		}

		public function resize($path, $width = THUMB_WIDTH, $height = THUMB_HEIGHT, $crop = true)
		{
			$parts = explode(".", $path);
			$ext = end($parts);

			if (!isset($this->supported[$ext]))
			{
				throw new ImageException("Extension " . $ext . " not configured.");
			}

			if (!function_exists($this->supported[$ext]))
			{
				throw new ImageException("Call to undefined image function prevented.");
			}

			list($oldWidth, $oldHeight) = getimagesize($path);
			$ratio = $oldWidth / $oldHeight;

			if ($crop === true)
			{
				if ($oldWidth > $oldHeight)
				{
					$oldWidth = ceil($oldWidth - ($oldWidth * abs($ratio - $width / $height)));
				}
				else
				{
					$oldHeight = ceil($oldHeight - ($oldHeight * abs($ratio - $width / $height)));
				}
			}

			$function = $this->supported[$ext];
			$source = $function($path);
			$target = imagecreatetruecolor($width, $height);

			imagecopyresampled($target, $source, 0, 0, 0, 0, $width, $height, $oldWidth, $oldHeight);
			imagejpeg($target, ROOT_SERVER . "cache/tester.jpg", 100);
			return $target;
		}
	}

	class ImageException extends Exception
	{

	}
?>
