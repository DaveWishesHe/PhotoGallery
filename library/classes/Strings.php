<?php
	class Strings
	{
		public static function dump($data)
		{
			echo "<pre>";
				ob_start();
				var_dump($data);
				$data = ob_get_clean();
				print_r(str_replace("=>\n", "=> ", $data));
			echo "</pre>";
		}
	}
?>