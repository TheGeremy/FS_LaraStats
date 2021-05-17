<?php
$xml_map_fillTypes = load_xml_file($map_dir . "fillTypes.xml");

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
execute_query($query);
unset($xml_map_fillTypes);
unset($data);
just_print("Data loaded to fs_map_fill_type (" . (string)($row - 1) . " rows).");
?>