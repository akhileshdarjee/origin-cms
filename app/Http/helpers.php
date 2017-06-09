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
		exit();
	}


	// get translation text
	function _t($text) {
		$lang = Session::get('locale');
		App::setLocale($lang);
		$lang_text = trans($lang . '.' . $text);

		if (strpos($lang_text, $lang . '.') !== false) {
			return $text;
		}
		else {
			return $lang_text;
		}
	}


	// create description for activity
	function make_act_desc($activity_data) {
		$desc = false;
		$user_link = url('/form/user/' . $activity_data->user_id);
		$form_link = url('/form') . '/' . snake_case($activity_data->module) . '/' . $activity_data->form_id;

		$user = '<a class="text-primary" href="' . $user_link . '" target="_blank">';
		$user .= '<strong>' . _t($activity_data->user) . '</strong></a>';

		if ($activity_data->module == "Auth") {
			if ($activity_data->action == "Login") {
				$desc = $user . " " . _t("logged in");
			}
			else {
				$desc = $user . " " . _t("logged out");
			}
		}
		else {
			if ($activity_data->form_id) {
				$activity_link = '<a class="text-primary" href="' . $form_link . '" target="_blank">';
				$activity_link .= '<strong>' . _t($activity_data->module) . ': ' . _t($activity_data->record_identifier) . '</strong></a>';
			}

			if ($activity_data->action == "Create") {
				$desc = _t("New") . " " . $activity_link . " " . _t("created by") . " " . $user;
			}
			elseif ($activity_data->action == "Update") {
				$desc = $activity_link . " " . _t("updated by") . " " . $user;
			}
			elseif ($activity_data->action == "Delete") {
				$desc = '<strong>' . _t($activity_data->module) . ': ' . _t($activity_data->record_identifier) . '</strong>';
				$desc .= ' ' . _t('deleted by') . ' ' . $user;
			}
		}

		return $desc;
	}


	// convert hours to minutes
	function h2m($hours) { 
		$minutes = 0;

		if (strpos($hours, ':') !== false) {
			// Split hours and minutes. 
			list($hours, $minutes) = explode(':', $hours);
		}

		return $hours * 60 + $minutes;
	}


	// convert time to human readable format
	function human_readable($time) {
		$time = explode(":", $time);
		$hours = $time[0];
		$minutes = $time[1];
		$seconds = $time[2];

		$duration = '';

		if ((int) $hours) {
			$duration .= (int) $hours . ' hrs';
		}
		if ((int) $minutes) {
			$duration .= ' ' . (int) $minutes . ' mins';
		}
		if ((int) $seconds) {
			$duration .= ' ' . (int) $seconds . ' sec';
		}

		return $duration;
	}


	function insert_into_object($obj, $key, $value, $after) {
		$new_object = array();

		foreach((array) $obj as $k => $v) {
			$new_object[$k] = $v;

			if ($after == $k){
				$new_object[$key] = $value;
			}
		}

		$new_object = (object) $new_object;
		return $new_object;
	}
?>