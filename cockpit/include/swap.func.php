<?php
function swap($id, $old, $new, $sub = FALSE)
{
	$db->query('UPDATE menu SET niveau = \'|' . $new . '\' WHERE menu_id=' . $id) or die('swap 1' . $id . mysqli_error($db));
	$db->query('UPDATE menu SET niveau = \'' . $old . '\' WHERE niveau=\'' . $new . '\'') or die('swap 2' . mysqli_error($db));
	$db->query('UPDATE menu SET niveau = \'' . $new . '\' WHERE menu_id=' . $id) or die('swap 3' . mysqli_error($db));

	$result = $db->query('SELECT id, niveau FROM menu WHERE niveau LIKE \'' . $old . '.%\' ORDER BY niveau') or die('swap 4' . mysqli_error($db));

	while($item = mysqli_fetch_object($result))
	{
		$db->query('UPDATE menu SET niveau = \'|' . str_replace($old, $new, $item->niveau) . '\' WHERE id = ' . $item->id . '') or die('swap 5' . mysqli_error($db));
	}

	$result = $db->query('SELECT id, niveau FROM menu WHERE niveau LIKE \'' . $new . '.%\' ORDER BY niveau') or die('swap 6' . mysqli_error($db));

	while($item = mysqli_fetch_object($result))
	{
		$db->query('UPDATE menu SET niveau = \'' . str_replace($new, $old, $item->niveau) . '\' WHERE id = ' . $item->id . '') or die('swap 7' . mysqli_error($db));
	}

	$db->query('UPDATE menu SET niveau = REPLACE(niveau, \'|\', \'\');') or die('swap 8' . mysqli_error($db));
}

function cleanup_menu()
{
	// Cleanup main pages
	$old_niveau = 0;
	$result = $db->query('SELECT 			DISTINCT m.niveau, p.page_id 
							FROM 			menu m
							JOIN			pagina p
							ON				p.id = m.url
							ORDER BY 		m.niveau ASC') or die('cleanup 8' . mysqli_error($db));
	while($row = mysqli_fetch_object($result)) {
	
	
		$niveau = explode('.', $row->niveau);
		$main_niveau = $niveau[1];
		
		if ($niveau[2] == '') {
		
			if ($main_niveau != $old_niveau +1) {
			
				$new_niveau = (string) $niveau[0] . '.' . sprintf('%02d', (intval($niveau[1]) - 1));
				
				swap($row->page_id, $row->niveau, $new_niveau , FALSE);
				
				$old_niveau = $main_niveau - 1;
				
			} else {
			
				$old_niveau = $main_niveau;
			}
		}
	}
}

?>
