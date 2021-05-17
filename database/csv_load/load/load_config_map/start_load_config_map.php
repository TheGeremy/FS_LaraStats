<?php
// if savegame has new map, try to load config files for that map
// map config dir
$map_code = convert_string($map_title); // convert map title "Slovak Village" to "slovak_village"
//just_print("Map dir is: " . $map_dir); // user defined function
$map_dir = base_path() . "/fs_config/config_map/map_" . $map_code . "/";
// print info that this section start
print_heading("Map config load start");

// base_path() laravel base directory
// load modDesc.xml
$required_file = base_path() . '/database/csv_load/load/load_config_map/load_config_map_modDesc.php';
if(file_exists($required_file))  {
	require $required_file;
} else {
	exit("Unable to load required file: $required_file");
}

// load farmlands
$required_file = base_path() . '/database/csv_load/load/load_config_map/load_config_map_farmlands.php';
if(file_exists($required_file))  {
	require $required_file;
} else {
	exit("Unable to load required file: $required_file");
}

// load fill types
$required_file = base_path() . '/database/csv_load/load/load_config_map/load_config_map_fillTypes.php';
if(file_exists($required_file))  {
	require $required_file;
} else {
	exit("Unable to load required file: $required_file");
}

// load fill types
$required_file = base_path() . '/database/csv_load/load/load_config_map/load_config_map_translations.php';
if(file_exists($required_file))  {
	require $required_file;
} else {
	exit("Unable to load required file: $required_file");
}

// print info that this section end
print_heading("Map config load end");

unset($map_code);
unset($map_dir);
?>