<?php
// if savegame has new map, try to read config files for that map
// map config dir
$map_code = convert_string($map_title); // convert map title "Slovak Village" to "slovak_village"
//just_print("Map dir is: " . $map_dir); // user defined function
$map_dir = base_path() . "/fs_config/config_map/map_" . $map_code . "/";
// print info that this section start
print_heading("Map config load start");

// base_path() laravel base directory
// load modDesc.xml
require base_path() . '/database/csv_load/read/read_config_map/read_config_map_modDesc.php';

// load farmlands
require base_path() . '/database/csv_load/read/read_config_map/read_config_map_farmlands.php';

// load fill types
require base_path() . '/database/csv_load/read/read_config_map/read_config_map_fillTypes.php';

// load fill types
require base_path() . '/database/csv_load/read/read_config_map/read_config_map_translations.php';

// print info that this section end
print_heading("Map config load end");

?>