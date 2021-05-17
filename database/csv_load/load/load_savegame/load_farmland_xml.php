<?php

// path to xml to process
$xml_file = load_xml_file(base_path() . "/fs_config/savegame/farmland.xml");
$farmlands = $xml_file->xpath('//farmlands/farmland'); // all greatDemand elements

$data = array();
$row = 1;

foreach ($farmlands as $farmland) {
	$data[$row]['save_id'] = $save_id;
	$data[$row]['land_id'] = (string)$farmland->attributes()->id;
	$game_id = (int)$farmland->attributes()->farmId;
	if($game_id == 0 ) {
		$data[$row]['farm_id'] = '0';		
	} else {
		$data[$row]['farm_id'] = game_to_farm_id($game_id); // change game farm_id stored as game_id in fs_farm to internal database farm_id which is id from fs_farm
	}
	++$row;
}

// proces other items
$query = prepare_query_ml('fs_farm_land',$data);
execute_query($query);
just_print("Data loaded to fs_farm_land (" . (string)array_key_last($data)  . " rows).");
?>