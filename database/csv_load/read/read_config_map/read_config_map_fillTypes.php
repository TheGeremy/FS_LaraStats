<?php
$xml_map_fillTypes = simplexml_load_file($map_dir . "fillTypes.xml");

if($xml_map_fillTypes === false)
{
	die('Unable to load and parse the xml file: ' . error_get_last()['message'] );
}

$data = array();
$row = 1;
foreach ($xml_map_fillTypes->fillTypes->fillType as $fillType) {
	$data[$row]['map_id'] = $map_id;
	$data[$row]['name'] = (string)$fillType->attributes()->name;
	$data[$row]['price_table'] = (string)$fillType->attributes()->showOnPriceTable;
	$data[$row]['price_per_liter'] = (float)$fillType->attributes()->pricePerLiter;
	$data[$row]['mass_per_liter'] = (float)$fillType->physics->attributes()->massPerLiter;
	++$row;
}

$query = prepare_query_ml('fs_map_fill_type_dim', $data);
//execute_query($connection, $query);
//just_print($query);
just_print("Data loaded to fs_map_fill_type (" . (string)($row - 1) . " rows).");
?>