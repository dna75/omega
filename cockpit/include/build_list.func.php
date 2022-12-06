<?php
function build_list($type = 'ul', $list, $attributes = '', $depth = 0)
{
	if(!is_array($list))
	{
		return $list;
	}

	$out = '';

	if (is_array($attributes))
	{
		$atts = '';
		foreach ($attributes as $key => $val)
		{
			$atts .= ' ' . $key . '="' . $val . '"';
		}
		$attributes = $atts;
	}

	$out .= "<".$type.$attributes.">\n";

	static $_last_list_item = '';

	foreach ($list as $key => $val)
	{
		$_last_list_item = $key;

		$out .= "<li class='menu".$depth /4 ." '>";

		if ( ! is_array($val))
		{
			$out .= $val;
		}
		else
		{
			$out .= $_last_list_item."\n";
			$out .= build_list($type, $val, '', $depth + 4);
		}

		$out .= "</li>\n";
	}

	$out .= "</".$type.">\n";

	return $out;
}
?>
