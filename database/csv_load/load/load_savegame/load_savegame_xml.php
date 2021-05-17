<?php
// next
// create database function to return id of map from fs_map_dim
// add php code to check for existing record in fs_map_dim table if not insert new record and return id of map
// then go for farms.xml

// environment.xml file
$xml_environment = load_xml_file($savegame_dir . "environment.xml");
$day_time = (int)$xml_environment->{'dayTime'};
unset($xml_environment);

// careerSavegame.xml file
$xml_career_savegame = load_xml_file($savegame_dir . "careerSavegame.xml");
$map_title = (string)$xml_career_savegame->{'settings'}->{'mapTitle'};

// find if map is already stored in dimension table (fs_farm_dim)
$map_id = get_map_id($map_title);
just_print("Map id: " . $map_id);

// if map is not stored in dimension table (fs_farm_dim)
if($map_id == 0) {
	// look to the folder /fs_config/config_map/map_[map_title]/modDesc.xml - map_title must be converted via function convert_string
	// and load new map info
	$required_file = base_path() . '/database/csv_load/load/load_config_map/start_load_config_map.php';

	if(file_exists($required_file))  {
		require $required_file;
	} else {
		exit("Unable to load required file: $required_file");
	}
}

//--- fs_savegame table ----------------------------------------------------------------------------------------------------------------

// fs_savegame columns
$fs_savegame_data[0] = array(
	"current_day" => $xml_current_day,
	"map_id" => $map_id,
	"savegame_name" => (string)$xml_career_savegame->settings->savegameName,
	"save_date" => (string)$xml_career_savegame->settings->saveDate,
	"money" => (int)$xml_career_savegame->statistics->money,
	"play_time" => (float)$xml_career_savegame->statistics->playTime,
	"day_hour" => round($day_time/60,0),
	"day_min" => $day_time%60,
	"player_name" => (string)$xml_career_savegame->settings->playerName
);

$query = prepare_query_ml('fs_savegame',$fs_savegame_data);
execute_query($query);
unset($fs_savegame_data);
just_print("Data loaded to fs_savegame.");

//--- fs_savegame_detail table ----------------------------------------------------------------------------------------------------------------

// values from careerSavegame.xml >> settings
$fs_savegame_detail_cols_list = array(
	"isPlantWitheringEnabled",
	"trafficEnabled",
	"stopAndGoBraking",
	"automaticMotorStartEnabled",
	"fruitDestruction",
	"plowingRequiredEnabled",
	"weedsEnabled",
	"limeRequired",
	"fuelUsageLow",
	"helperBuyFuel",
	"helperBuySeeds",
	"helperBuyFertilizer",
	"helperSlurrySource",
	"helperManureSource",
	"difficulty",
	"economicDifficulty",
	"plantGrowthRate",
	"dirtInterval",
	"timeScale",
	"autoSaveInterval"
);

// update save_id
$save_id = get_save_id();
just_print("Savegame id updated: " . $save_id);
$fs_savegame_detail_data[0]['save_id'] = $save_id;

foreach($xml_career_savegame->{'settings'}->children() as $key => $value) {
	if(in_array($key,$fs_savegame_detail_cols_list)) {
		//print_r(convert_string($key) . ": " . $value . "\n");
		$fs_savegame_detail_data[0][convert_string($key)] = $value;
	}
}

$query = prepare_query_ml('fs_savegame_detail',$fs_savegame_detail_data);
execute_query($query);
unset($fs_savegame_detail_cols_list);
unset($fs_savegame_detail_data);
just_print("Data loaded to fs_savegame_detail.");

//--- fs_map_detail table --------------------------------------------------------------------------------------------------------------------------

$mapping = array(
	"densityMapRevision" => "density_map_revision",
	"terrainLodTextureRevision" => "terrain_lod_texture_revision",
	"splitShapesRevision" => "split_shapes_revision",
	"tipCollisionRevision" => "tip_collision_revision",
	"placementCollisionRevision" => "placement_collision_revision",
	"mapDensityMapRevision" => "map_density_map_revision",
	"mapTerrainLodTextureRevision" => "map_terrain_lod_texture_revision",
	"mapSplitShapesRevision" => "map_split_shapes_revision",
	"mapTipCollisionRevision" => "map_tip_collision_revision", 
	"mapPlacementCollisionRevision" => "map_placement_collision_revision"
);

foreach($xml_career_savegame->{'settings'}->children() as $key => $value) {
	$fs_map_detail_data[0]['save_id'] = $save_id;
	$fs_map_detail_data[0]['map_id'] = $map_id;
	if(array_key_exists($key,$mapping)) {
		$fs_map_detail_data[0][$mapping[$key]] = $value;
	}
}

$query = prepare_query_ml('fs_map_detail',$fs_map_detail_data);
execute_query($query);
unset($mapping);
unset($fs_map_detail_data);
just_print("Data loaded to fs_map_detail.");


//--- fs_mod table --------------------------------------------------------------------------------------------------------------------------

// load savegame mods (we strore all values so we don't need column list')
$savegame_mods = $xml_career_savegame->xpath('//careerSavegame/mod');
unset($xml_career_savegame);
$row = 1; // to properly do the rows count
foreach($savegame_mods as $mod) {
	foreach($mod->attributes() as $key => $value) {
		$fs_mod_data[$row]['save_id'] = $save_id;
		$fs_mod_data[$row][convert_string($key)] = $value;
	}
	++$row; // next data row
}

$query = prepare_query_ml('fs_savegame_mod',$fs_mod_data);
execute_query($query);
unset($savegame_mods);
unset($fs_mod_data);
just_print("Data loaded to fs_savegame_mod (" . (string)($row - 1) . " rows).");

?>