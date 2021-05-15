<?php

// path to xml to process
$xml_file = load_xml_file(base_path() . "/fs_config/savegame/economy.xml");
$great_demands = $xml_file->xpath('//economy/greatDemands/greatDemand'); // all greatDemand elements

$data = array();
$row = 1;

// array of values to save in db
$mapping = array(
	"itemId" => "item_id",
	"fillTypeName" => "fill_type",
	"demandMultiplier" => "demand_multiplier",
	"demandStartDay" => "demand_start_day",
	"demandStartHour" => "demand_start_hour",
	"demandDuration" => "demand_duration",
	"isRunning" => "is_running",
	"isValid" => "is_valid"
);

foreach ($great_demands as $great_demand) {
	$data[$row]['save_id'] = $save_id;
	foreach ($great_demand->attributes() as $key => $value) {
		$data[$row][$mapping[$key]] = (string)$value;
	}
	++$row;
}

// proces other items
$query = prepare_query_ml('fs_savegame_great_demand',$data);
execute_query($query);
just_print("Data loaded to fs_savegame_great_demand (" . (string)array_key_last($data)  . " rows).");
?>