<?php
// path to run in browser
// http://php.nuba.synology.me/console/read/read_savegame_xml.php

// base_path() laravel base directory
require base_path() . '/database/csv_load/scripts/user_defined_functions.php';

$xml_file_path = base_path() . "/database/csv_load/savegame3/environment.xml";

$xml_file = simplexml_load_file($xml_file_path);
$xml_current_day = (int)$xml_file->{'currentDay'};
$day_time = (int)$xml_file->{'dayTime'}; // used in load_savegame_xml.php

// check if savegame is valid
$xml_file_path = base_path() . "/database/csv_load/savegame3/careerSavegame.xml";
$xml_file = simplexml_load_file($xml_file_path);
$valid_save = (string)$xml_file->attributes()['valid'];

// array of values to save in db
$values_to_save = array(
"isPlantWitheringEnabled"
,"trafficEnabled"
,"stopAndGoBraking"
,"automaticMotorStartEnabled"
,"fruitDestruction"
,"plowingRequiredEnabled"
,"weedsEnabled"
,"limeRequired"
,"fuelUsageLow"
,"difficulty"
,"economicDifficulty"
,"plantGrowthRate"
,"dirtInterval"
,"timeScale"
,"autoSaveInterval"
,"money"
,"playTime");

// load carrier xml to array
$json_obj = json_encode($xml_file);
$savegame_arr = json_decode($json_obj, TRUE);

print_r("Valid game save: " . (($valid_save) ? 'Yes' : 'No') . "\n");

//connect to db
$connection = mariadb_connect();

//find if map is already stored in dimension table (fs_farm_dim)
// ?????

// if not store new map in dimension table (fs_farm_dim)
// later:
// look to the folder /fs_config/config_map/map_[map_title]/modDesc.xml - map_title must be converted via function convert_name
$query = "insert into fs_farm_dim ()";

// build query to save current savegame record
$columns = array("current_day","savegame_name","save_date","player_name");
$values = array($xml_current_day,$savegame_arr['settings']['savegameName'],$savegame_arr['settings']['saveDate'],$savegame_arr['settings']['playerName']);

// get last savegame id
$saveId = get_savegame_id($connection);
print_r("Savegame id (saveId): " . $saveId . "\n\n");

// prepare and run query
$query = prepare_query("fs_savegame",$columns,$values);
print_r($query . "\n\n");

// assemble query to update savegame settings and statistics
$query = "update fs_savegame set\n";
// settings values
foreach($savegame_arr['settings'] as $key => $value){
	if (in_array($key, $values_to_save, true)) {
   		$query .= convert_string($key) . "=" . $value . ",\n";
 	}
}
// statistics valeus
foreach($savegame_arr['statistics'] as $key => $value){
	if (in_array($key, $values_to_save, true)) {
   		$query .= convert_string($key) . "=" . $value . ",\n";
 	}
}

$query = substr($query,0,-2) . "\nwhere id=" . $saveId . ";";
print_r($query . "\n\n");

// update values from environment xml
$query = "update fs_savegame set dayHour=" . round($day_time/60,0) . ", dayMin=" . $day_time%60 . ", currentDay=" . $xml_current_day . " where id=" . $saveId . ";";
print_r($query . "\n\n");

?>