<?php
	class Gallery
	{
		public function getImages($gallery = false)
		{
			$path = ROOT_SERVER . "images/galleries/";

			if ($gallery !== false && is_dir($path . $gallery))
			{
				$path .= $gallery;
			}

			$files = $this->readDirectory($path);
			$images = array();

			foreach ($files as $file)
			{
				$parts = explode(".", $file);
				$extenstion = end($parts);

				if (in_array($extenstion, array("jpg", "jpeg", "png", "gif")))
				{
					$images[] = $file;
				}
			}

			return $images;
		}

		private function readDirectory($path)
		{
			$list = array();
			$handle = opendir($path);

			if ($handle !== false)
			{
				while (false !== ($entry = readdir($handle)))
				{
					if (!in_array($entry, array(".", "..")))
					{
						$list[] = $entry;
					}
				}

				closedir($handle);
			}

			return $list;
		}
	}
?>