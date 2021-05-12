<?php
// include external php code 
// base_path() laravel base directory
require base_path() . '/database/csv_load/scripts/user_defined_functions.php';

// path to xml to process
$xml_file_path = base_path() . "/fs_config/config_map/map_slovak_village/farmlands.xml";
$xml_file = simplexml_load_file($xml_file_path);
$xml_data = $xml_file->xpath('//map/farmlands/farmland'); // all farmland elements (tags)
// load xml file to array


// array of values to save in db
$mapping = array(
// vehicle
'id' => 'game_field_id',
'priceScale' => 'price_scale',
'npcName' => 'npc_name'
);

//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
// is static change to load from db
$map_id = 999;

// get last savegame id
//$connection = mariadb_connect();
//$saveId = get_savegame_id($connection);

// create array for data, those will be inserted into database
$data = array();
$row = 1;

foreach ($xml_data as $farmland) {
	$data[$row]['map_id'] = $map_id;
	foreach($farmland->attributes() as $key => $value) {
		if(array_key_exists($key,$mapping)) {
			$data[$row][$mapping[$key]] = $value;
		}
	}
	++$row;
}


// available fields
/*
	farm_id TinyInteger
	game_field_id  TinyInteger
	npcName length of 50
    priceScale
    land_name length of
    note length of 100
 */

$query = prepare_query_ml('fs_farmland_dim',$data);
print_r($query);
//print_r($data);

//mariadb_disconnect($connection);
 ?>