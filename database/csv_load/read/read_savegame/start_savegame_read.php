<?php
// base_path() laravel base directory
require base_path() . '/database/csv_load/scripts/user_defined_functions.php';

// savegame dir
$savegame_dir = base_path() . "/database/csv_load/savegame3/";

//connect to db
$connection = mariadb_connect();

// get last savegame id
$savegame_id = get_savegame_id($connection);
print_r("Savegame id: " . $savegame_id . "\n");

// get last stored current day
$db_current_day = get_current_day($connection);
print_r("DB current day: " .  $db_current_day . "\n");

// get savegame current game
$xml_file = simplexml_load_file($savegame_dir . "environment.xml");

if($xml_file === false)
{
	die('Unable to load and parse the xml file: ' . error_get_last()['message'] );
}

$xml_current_day = (int)$xml_file->{'currentDay'};
print_r("XML current day: " . $xml_current_day . "\n");

// check if savegame is valid
$xml_file = simplexml_load_file($savegame_dir . "careerSavegame.xml");
$valid_savegame = (string)$xml_file->attributes()['valid'];
print_r("Valid savegame: " . (($valid_savegame) ? 'Yes' : 'No') . "\n");

// if xml valid is true and xml current day is more than database current day
if($valid_savegame && $xml_current_day > $db_current_day) {
	print_heading("Savegame load start");
	require base_path() . '/database/csv_load/read/read_savegame/read_savegame_xml.php';	
	print_heading("Savegame load end");
}  else {
	print_r("!!! No valid savegame or not a new day.\n");
}

// disconnect from db
mariadb_disconnect($connection);

?>