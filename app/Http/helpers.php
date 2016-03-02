<?php

	// get controller name which has called this controller function
	// pass Route instance to this function
	// function get_controller_name($route) {
	// 	$route_action = $route->getAction();
	// 	return explode("@", class_basename($route_action['controller']))[0];
	// }


	// converts foo_bar & FooBar -> Foo Bar
	function awesome_case($string) {
		if (strpos($string, '_') !== false) {
			return ucwords(str_replace("_", " ", $string));
		}
		else {
			return ucwords(trim(preg_replace('/(?<!\ )[A-Z]/', ' $0', $string)));
		}
	}


	// generates a new random password
	function generate_password($length = null, $only_numbers = null) {
		if ($only_numbers) {
			$alphabet = "0123456789";
		}
		else {
			$alphabet = "abcdefghijklmnopqrstuwxyz_ABCDEFGHIJKLMNOPQRSTUWXYZ0123456789@#$.";
		}

		$pass = array(); //remember to declare $pass as an array
		$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
		$length = $length ? $length : 10;
		for ($i = 0; $i < $length; $i++) {
			$n = rand(0, $alphaLength);
			$pass[] = $alphabet[$n];
		}

		return implode($pass); //turn the array into a string
	}


	// show var dump output to web
	function web_dump($var) {
		ob_start();
		var_dump($var);
		$output = ob_get_clean();

		// Add formatting
		$output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);

		$output = '<pre style="background: #FFFEEF; color: #000; border: 1px dashed #888; padding: 10px; margin: 10px 0; text-align: left;">'.$output.'</pre>';

		echo $output;
	}
?>