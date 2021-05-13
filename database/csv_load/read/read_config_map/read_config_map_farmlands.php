<?php
$xml_map_farmlands = simplexml_load_file($map_dir . "farmlands.xml");

if($xml_map_farmlands === false)
{
	die('Unable to load and parse the xml file: ' . error_get_last()['message'] );
}

$price_per_ha = (integer)$xml_map_farmlands->{'farmlands'}->attributes()->{'pricePerHa'};

// this only if xml field name differ from database field name
$field_mapping = array(
	"id" => "game_field_id",
	"npcName" => "npc_name",
	"priceScale" => "price_scale",
	"sizeHa" => "ha",
	"note" => "note",
	"defaultFarmProperty" => "owning"
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

$query = prepare_query_ml('fs_map_farmland_dim', $fs_farmland_dim_data);
//execute_query($connection, $query);
//just_print($query);
just_print("Data loaded to fs_map_farmland_dim (" . (string)($row - 1) . " rows).");
?>