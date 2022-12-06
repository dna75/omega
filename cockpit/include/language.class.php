<?php
/*
 *	A handy language class which combines a user language detection script
 *	with a possibility to get and set various language fields. Conclusion: Awesome!
 *
 *	@company: 	Amplo
 *	@author:	 Nanne Dijkstra
 *	@version: 	0.1
 *
 */

define('LANGUAGE_DEFAULT', 'nl');

class Language
{

	public 	$language,
		$multilingual_site = true;

	function __construct($language = '', $cookie = true, $user_id = 0, $geo = false)
	{

		$this->get_language($language, $cookie, $user_id, $geo);
	}

	private function get_language($language = '', $cookie = true, $user_id = 0, $geo = false)
	{

		if ($language == '') {

			if ($user_id > 0) {
				$this->language = $this->get_language_user($user_id);
			} elseif ($cookie) {
				$this->language = $this->get_language_cookie();
			} elseif ($geo) {
				$this->language = $this->get_language_geo();
			} else {
				$this->language = LANGUAGE_DEFAULT;
			}
		} else {

			$this->language = ($this->check_language_code($language)) ? $language : LANGUAGE_DEFAULT;
		}
		$this->set_language('', $cookie, $user_id);

		return $this->language;
	}

	private function set_language($language = '', $cookie = true, $user_id = 0)
	{

		if ($language == '') $language = $this->language;
		$this->language = ($this->check_language_code($language)) ? $language : LANGUAGE_DEFAULT;

		if ($user_id > 0) $this->set_language_user($user_id, $this->language);
		if ($cookie) $this->set_language_cookie($this->language);
	}

	private function get_language_user($user_id)
	{

		$language = LANGUAGE_DEFAULT;
		$result = mysqli_query("SELECT 		language
								FROM		`users`
								WHERE		id 				= $user_id
								LIMIT		1
						");

		if (mysqli_num_rows($result) > 0) {

			while ($row = mysqli_fetch_array($result)) {

				if ($this->check_language_code($row['language'])) $language = $row['language'];
			}
		}
		return $language;
	}

	private function set_language_user($user_id, $language)
	{

		mysqli_query("UPDATE			users
						SET			language	= '$language'
						WHERE		id = " . $this_id . "
						");
	}

	private function get_language_cookie()
	{

		return ($this->check_language_code($_COOKIE["language"])) ? $_COOKIE["language"] : LANGUAGE_DEFAULT;
	}

	private function set_language_cookie($language)
	{

		setcookie("language", $language);
	}

	private function get_language_geo()
	{

		$language = LANGUAGE_DEFAULT;
		$client  = @$_SERVER['HTTP_CLIENT_IP'];
		$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
		$remote  = @$_SERVER['REMOTE_ADDR'];
		$result  = array('country' => '', 'city' => '');

		if (filter_var($client, FILTER_VALIDATE_IP)) {
			$ip = $client;
		} elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
			$ip = $forward;
		} else {
			$ip = $remote;
		}

		$ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));

		if ($ip_data && $ip_data->geoplugin_countryCode != null) {

			$language = ($this->check_language_code($ip_data->geoplugin_countryCode)) ? $ip_data->geoplugin_countryCode : LANGUAGE_DEFAULT;
		}
		return $language;
	}

	private function check_language_code($code = '')
	{

		if ($code == '') return false;

		$code = strtolower($code);

		$db = new mysqli(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_DB);

		$result = $db->query("SELECT 		id
								FROM		languages
								WHERE		active				= 1
								AND			code			= '" . $code . "'
								LIMIT		1
								") or die(mysqli_error($db));
		$return = (mysqli_num_rows($result) == 1) ? true : false;
		return $return;
	}

	public function get_field($slug, $field_type = 'auto')
	{

		$text = false;
		$language = $this->language;

		$db = new mysqli(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_DB);

		$result = $db->query("SELECT 		field_text,
											field_string
								FROM		`language_fields`
								WHERE		slug 					= '$slug'
								AND			language				= '$language'
								LIMIT		1
						");

		if (mysqli_num_rows($result) > 0) {

			while ($row = mysqli_fetch_array($result)) {

				switch ($field_type) {

					case "text":
						$text = $row['field_text'];
						break;
					case "string":
						$text = $row['field_string'];
						break;
					default:
						$text = ($row['field_text'] != '') ? $row['field_text'] : $row['field_string'];
						break;
				}
			}
		}
		return $text;
	}

	public function set_field($language, $slug, $text, $field_type = 'text')
	{

		$language = strtolower($language);

		if ($this->get_field($type . $language, $orig_id, $field_type) == false) {

			mysqli_query("DELETE FROM 	`language_fields`
							WHERE		language	= '$language'
							AND			slug		= '$slug'
							");
			mysqli_query("INSERT INTO	`language_fields`
										(slug, language, field_$field_type)
							VALUES		('$slug', '$language', '$text')
						");
		} else {

			mysqli_query("UPDATE			`language_fields`
							SET			field_$field_type	= '$text'
							WHERE		language			= '$language'
							AND			slug				= '$slug'
						");
		}
	}
}
