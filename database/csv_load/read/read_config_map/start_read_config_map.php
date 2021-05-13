<?php
// if savegame has new map, try to ready config files for that map
// map config dir
$map_code = convert_string($map_title); // convert map title "Slovak Village" to "slovak_village"
//just_print("Map dir is: " . $map_dir); // user defined function
print_heading("Map config load start");

$map_dir = base_path() . "/fs_config/config_map/map_" . $map_code . "/";

$xml_map_moddesc = simplexml_load_file($map_dir . "modDesc.xml");

if($xml_map_moddesc === false)
{
	die('Unable to load and parse the xml file: ' . error_get_last()['message'] );
}

$fs_map_dim_data[0] = array(
	"map_title" => $map_title,
	"note" => "", // query preparing function will change this to null
	"author" => (string)$xml_map_moddesc->author,
	"version" => (string)$xml_map_moddesc->version,
	"mod_desc_version" => (string)$xml_map_moddesc->attributes()->descVersion,
	"short_desc_en" => trim((string)$xml_map_moddesc->maps->map->description->en),
	"short_desc_cz" => trim((string)$xml_map_moddesc->maps->map->description->cz),
	"description_en" => trim((string)$xml_map_moddesc->description->en),
	"description_cz" => trim((string)$xml_map_moddesc->description->cz)
);

$query = prepare_query_ml('fs_map_dim', $fs_map_dim_data);
//just_print($query);
//execute_query($connection, $query);
just_print('Map info stored in fs_map_dim.');

$xml_map_farmlands = simplexml_load_file($map_dir . "farmlands.xml");
$price_per_ha = (integer)$xml_map_farmlands->{'farmlands'}->attributes()->{'pricePerHa'};

if($xml_map_farmlands === false)
{
	die('Unable to load and parse the xml file: ' . error_get_last()['message'] );
}

// this only if xml field name differ from database field name
$fs_farmland_dim_map = array(
	"id" => "game_field_id",
	"npcName" => "npc_name",
	"priceScale" => "price_scale",
	"sizeHa" => "ha",
	"note" => "note",
	"defaultFarmProperty" => "owning"
);

$fs_farmland_dim_data = array();
$row = 1;
foreach ($xml_map_farmlands->farmlands->farmland as $farmland) {
	$fs_farmland_dim_data[$row]['map_id'] = $map_id;
	foreach ($farmland->attributes() as $key => $value) {
		if(array_key_exists($key, $fs_farmland_dim_map)) {
			$fs_farmland_dim_data[$row][$fs_farmland_dim_map[$key]] = (string)$value;
		}
	}
	++$row;
}

$query = prepare_query_ml('fs_farmland_dim', $fs_farmland_dim_data);
//execute_query($connection, $query);
just_print($query);
//just_print("Data loaded to fs_farmland_dim (" . (string)($row - 1) . " rows).");

print_heading("Map config load end");

?>