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

// if xml valid is true and xml current day is more than database current day
if($valid_savegame && $xml_current_day > $db_current_day) {
	print_heading("Savegame load start");
	// load savegame.xml
	require base_path() . '/database/csv_load/load/load_savegame/load_savegame_xml.php';
	// load farms.xml
	require base_path() . "/database/csv_load/load/load_savegame/load_farms_xml.php";	
	// load farmland.xml
	require base_path() . "/database/csv_load/load/load_savegame/load_farmland_xml.php";
	// load economy.xml
	require base_path() . "/database/csv_load/load/load_savegame/load_economy_xml.php";
	// load missions.xml
	require base_path() . "/database/csv_load/load/load_savegame/load_missions_xml.php";
	// load npc.xml
	require base_path() . "/database/csv_load/load/load_savegame/load_npc_xml.php";
	// load treePlant.xml
	require base_path() . "/database/csv_load/load/load_savegame/load_treePlant_xml.php";

	if(check_seasons()) {
		// load seasons.xml
		just_print("Savegame has seasons mod enabled");
		require base_path() . "/database/csv_load/load/load_savegame/load_seasons_xml.php";
	}
	
	if(check_gcomp()) {
		// load globalCompany.xml
		just_print("Savegame has global company mod enabled");
		require base_path() . "/database/csv_load/load/load_savegame/load_global_comp_xml.php";
	}
	
	// load vehicles.xml
	//require base_path() . "/database/csv_load/load/load_savegame/load_vehicles_xml.php";
	// load economy.xml
	//require base_path() . "/database/csv_load/load/load_savegame/load_items_xml.php";
	print_heading("Savegame load end");
}  else {
	just_print("!!! No valid save or not a new day in save.");
}

?>