<?php
// base_path() laravel base directory
$required_file = base_path() . "/database/csv_load/scripts/user_defined_functions.php";

if(file_exists($required_file))  {
	require $required_file;
} else {
	exit("Unable to load required file: $required_file");
}

// savegame dir
$savegame_dir = base_path() . "/fs_config/savegame/";

// get last savegame id
$save_id = get_save_id();
just_print("Savegame id: " . $save_id);

// get last save_id for specific table
$last_save_id = check_last_save_id('fs_vehicle');
just_print("Last save_id for fs_vehicle: " . (string)$last_save_id);

// get last stored current day
$db_current_day = get_current_day();
just_print("DB current day: " .  $db_current_day);

// get savegame current game
$xml_file = load_xml_file($savegame_dir . "environment.xml");
$xml_current_day = (int)$xml_file->{'currentDay'};
just_print("XML current day: " . $xml_current_day);

// check if savegame is valid
$xml_file = load_xml_file($savegame_dir . "careerSavegame.xml");
$valid_savegame = (string)$xml_file->attributes()['valid'];
just_print("Valid savegame: " . (($valid_savegame) ? 'Yes' : 'No'));

$farm_map = get_maping($save_id,'fs_farm');


// if xml valid is true and xml current day is more than database current day
if($valid_savegame && $save_id > $last_save_id) {
	print_heading("vehicles.xml load start");
	require base_path() . "/database/csv_load/load/load_savegame/load_vehicles_xml.php";
	print_heading("vehicles.xml load end");
}  else {
	just_print("!!! No valid save or not a new day in save.");
}

