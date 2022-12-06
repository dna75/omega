<?php
function set_value($key, $default = '')
{
	if(isset($_POST[$key]))
	{
		return $_POST[$key];
	}

	return $default;
}
?>
