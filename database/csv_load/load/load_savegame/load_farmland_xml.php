<?php

// path to xml to process
$xml_file = load_xml_file(base_path() . "/fs_config/savegame/farmland.xml");
$farmlands = $xml_file->xpath('//farmlands/farmland'); // all greatDemand elements

$data = array();
$row = 1;

foreach ($farmlands as $farmland) {
	$game_id = (int)$farmland->attributes()->farmId;
	
	$data[$row]['save_id'] = $save_id;
	$data[$row]['land_id'] = (string)$farmland->attributes()->id;	
	$data[$row]['farm_id'] = find_farm_id($game_id,$farm_map);
	++$row;
}

// proces other items
$query = prepare_query_ml('fs_land',$data);
execute_query($query);
just_print("Data loaded to fs_land (" . (string)array_key_last($data)  . " rows).");
?>