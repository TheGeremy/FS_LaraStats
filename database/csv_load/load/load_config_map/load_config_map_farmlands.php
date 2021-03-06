<?php
$required_file = $map_dir . "farmlands.xml";
if(file_exists($required_file)) {
	$xml_map_farmlands = simplexml_load_file($map_dir . "farmlands.xml");
	$price_per_ha = (integer)$xml_map_farmlands->{'farmlands'}->attributes()->{'pricePerHa'};

	// this only if xml field name differ from database field name
	$field_mapping = array(
		"id" => "land_id",
		"npcName" => "npc_name",
		"priceScale" => "price_scale",
		"sizeHa" => "ha",
		"note" => "note",
		"defaultFarmProperty" => "default"
	);

	$data = array();
	$row = 1;
	foreach ($xml_map_farmlands->farmlands->farmland as $farmland) {
		$fs_farmland_dim_data[$row]['map_id'] = $map_id;
		foreach ($farmland->attributes() as $key => $value) {
			if(array_key_exists($key, $field_mapping)) {
				$fs_farmland_dim_data[$row][$field_mapping[$key]] = (string)$value;
			}
		}
		++$row;
	}

	$query = prepare_query_ml('fs_map_land_dim', $fs_farmland_dim_data);
	execute_query($query);
	unset($xml_map_farmlands);
	unset($price_per_ha);
	unset($field_mapping);
	unset($data);
	unset($fs_farmland_dim_data);
	just_print("Data loaded to fs_map_land_dim (" . (string)($row - 1) . " rows).");
} else {
	just_print("File (farmlands.xml) for modded map of given savegame was not found!");
}
?>