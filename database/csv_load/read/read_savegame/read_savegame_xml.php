<?php
// next
// create database function to return id of map from fs_map_dim
// add php code to check for existing record in fs_map_dim table if not insert new record and return id of map
// then go for farms.xml

// environment.xml file
$xml_environment = simplexml_load_file($savegame_dir . "environment.xml");
if($xml_environment === false)
{
	die('Unable to load and parse the xml file: ' . error_get_last()['message'] );
}
$day_time = (int)$xml_environment->{'dayTime'};

// careerSavegame.xml file
$xml_career_savegame = simplexml_load_file($savegame_dir . "careerSavegame.xml");
if($xml_career_savegame === false)
{
	die('Unable to load and parse the xml file: ' . error_get_last()['message'] );
}
$map_title = (string)$xml_career_savegame->{'settings'}->{'mapTitle'};

// find if map is already stored in dimension table (fs_farm_dim)
$map_id = get_map_id($map_title,$connection);

// if map is not stored in dimension table (fs_farm_dim)
if($map_id == 0) {
	// look to the folder /fs_config/config_map/map_[map_title]/modDesc.xml - map_title must be converted via function convert_string
	// and load new map info
	require base_path() . '/database/csv_load/read/read_config_map/start_read_config_map.php';	

	// update map_id
	$map_id = get_map_id($map_title,$connection);
}

print_r("Map id: " . $map_id . "\n");

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
//print_r($query . "\n");
print_r("Data loaded to fs_savegame.\n");

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

// update savegame_id
$savegame_id = get_savegame_id($connection);
print_r("Savegame id updated: " . $savegame_id . "\n");
$fs_savegame_detail_data[0]['save_id'] = $savegame_id;

foreach($xml_career_savegame->{'settings'}->children() as $key => $value) {
	if(in_array($key,$fs_savegame_detail_cols_list)) {
		//print_r(convert_string($key) . ": " . $value . "\n");
		$fs_savegame_detail_data[0][convert_string($key)] = $value;
	}
}

$query = prepare_query_ml('fs_savegame_detail',$fs_savegame_detail_data);
//print_r($query . "\n");
print_r("Data loaded to fs_savegame_detail.\n");


// load savegame mods (we strore all values so we don't need column list')
$savegame_mods = $xml_career_savegame->xpath('//careerSavegame/mod');
$row = 1; // to properly do the rows count
foreach($savegame_mods as $mod) {
	foreach($mod->attributes() as $key => $value) {
		$fs_mod_data[$row]['save_id'] = $savegame_id;
		$fs_mod_data[$row][convert_string($key)] = $value;
	}
	++$row; // next data row
}

$query = prepare_query_ml('fs_mod',$fs_mod_data);
//print_r($query . "\n");
print_r("Data loaded to fs_mod (" . (string)($row - 1) . " rows).\n");

?>