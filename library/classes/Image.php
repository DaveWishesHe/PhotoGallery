<?php
	class Image
	{
		protected $supported;

		public function __construct()
		{
			$this->supported = array("jpg" => "imagecreatefromjpeg", "jpeg" => "imagecreatefromjpeg", "png" => "imagecreatefrompng");
		}

		public function load($path, $width = THUMB_WIDTH, $height = THUMB_HEIGHT, $crop = true)
		{
			if ($this->checkCache($path, $width, $height) === true)
			{
				return $this->src;
			}

			return $this->resize($path, $width, $height, $crop) === true ? $this->src : "";
		}

		public function resize($path, $width, $height, $crop)
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
			return $this->cache($target, $path, $width, $height, $ext);
		}

		private function cache($image, $path, $width, $height, $ext)
		{
			$parts = explode("/", $path);
			$filename = $this->getCacheName(end($parts), $width, $height);
			$this->src = $filename;

			switch ($ext)
			{
				case "jpg":
				case "jpeg":
					return imagejpeg($image, $filename, 100);
					break;
				case "png":
					return imagepng($image, $filename, 0);
					break;
				default:
					return false;
					break;
			}
		}

		private function checkCache($path, $width, $height)
		{
			$parts = explode("/", $path);
			$filename = $this->getCacheName(end($parts), $width, $height);

			// TODO: Cache expiry. Currently a cached image lives forever (perhaps not a bad thing, but would be nice to have options)
			if (file_exists($filename))
			{
				$this->src = $filename;
				return true;
			}

			return false;
		}

		private function getCacheName($filename, $width, $height)
		{
			return ROOT_CACHE . $width . "x" . $height . "_" . $filename;
		}
	}

	class ImageException extends Exception
	{

	}
?>
