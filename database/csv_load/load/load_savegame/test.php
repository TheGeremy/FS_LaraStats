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

$farm_map = get_maping($save_id,'fs_farm');

check_seasons() ? just_print("Savegame has seasons mod enabled") : just_print("Savegame has seasons mod disabled");
check_gcomp() ? just_print("Savegame has global company mod enabled") : just_print("Savegame has global company mod disabled");

// check last save_id for specific table
$last_save_id = check_last_save_id('fs_vehicle');


// ddd($last_save_id);

// testing
// require base_path() . "/database/csv_load/load/load_savegame/load_vehicles_xml.php";

?>