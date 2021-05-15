<?php

// path to xml to process
$xml_file = load_xml_file(base_path() . "/fs_config/savegame/vehicles.xml");
$vehicles_items = $xml_file->xpath('//vehicles/vehicle'); // all vehicle elements under vehicles

$train = array();
$pallets = array();
$vehicles = array();

foreach ($vehicles_items as $item) {
	if(str_contains(strtolower($item->attributes()->filename),'train')) {
		array_push($train,$item); 
	} elseif(str_contains(strtolower($item->attributes()->filename),'pallet')) {
		array_push($pallets,$item); 
	} else {
		array_push($vehicles,$item); 
	}
}

unset($vehicles_items);
unset($xml_file);

// array of values to save in db
$mapping = array(
	// vehicle
	"id" => "game_id",
	"modName" => "mod_name",
	"filename" => "filename",
	"age" => "age",		
	"price" => "price",
	"farmId" => "farm_id",
	"propertyState" => "property_state",
	"operatingTime" => "operating_time",
	// fillUnit >> unit >> attributes
	"diesel" => "diesel",
	"def" => "def",
	"air" => "air",
	// FS19_RM_Seasons >> seasonsVehicle >> attributes 
	"years" => "seasons_years",
	"nextRepair" => "seasons_next_repair"
);

function check_fill_type($fill_type) {
	return $fill_type == 'UNKNOWN' ? NULL : $fill_type;
}

function check_fill_level($fill_level) {
	return $fill_level == floatval(0) ? NULL : $fill_level;
}

function process_train_items($data_to_process, $mapping, $save_id, $item_type = '') {
	$data = array();
	$row = 1;
	foreach($data_to_process as $item) {
		$data[$row]['save_id'] = $save_id;
		if($item_type != '') {
			$data[$row]['item_type'] = $item_type;
		}
		// item atributes
		foreach ($item->attributes() as $key => $value) {
			if(array_key_exists($key,$mapping)) {
				$data[$row][$mapping[$key]] = (string)$value;
			}
		}
		// item fill units
		if($item->fillUnit)	{
			foreach ($item->fillUnit->unit as $unit) {
				$fill_type = (string)$unit->attributes()->fillType;
				if($fill_type == 'DIESEL' OR $fill_type == 'DEF' OR $fill_type == 'AIR') {	
					switch ((string)$unit->attributes()->fillType) {
				    	case 'DIESEL':
				        	$data[$row]['diesel'] = (float)$unit->attributes()->fillLevel;
				        	break;
				    	case 'DEF':
				        	$data[$row]['def'] = (float)$unit->attributes()->fillLevel;
				        	break;
				    	case 'AIR':
				        	$data[$row]['air'] = (float)$unit->attributes()->fillLevel;
				        	break;
					}			
				} else {				
					// we store only 3 fill types (should be enough)
					switch ((int)$unit->attributes()->index) {
				    	case 1:
				        	$data[$row]['fill_type_1'] = check_fill_type((string)$unit->attributes()->fillType);
				        	$data[$row]['fill_level_1'] = check_fill_level((float)$unit->attributes()->fillLevel);
				        	break;
				    	case 2:
				        	$data[$row]['fill_type_2'] = check_fill_type((string)$unit->attributes()->fillType);
				        	$data[$row]['fill_level_2'] = check_fill_level((float)$unit->attributes()->fillLevel);
				        	break;
				    	case 3:
				        	$data[$row]['fill_type_3'] = check_fill_type((string)$unit->attributes()->fillType);
				        	$data[$row]['fill_level_3'] = check_fill_level((float)$unit->attributes()->fillLevel);
				        	break;
					}
				}
			}
		}
		// item seasons information
		if($item->FS19_RM_Seasons) {
			$data[$row]['seasons_years'] = (float)$item->FS19_RM_Seasons->seasonsVehicle->attributes()->years;
			$data[$row]['seasons_next_repair'] = (float)$item->FS19_RM_Seasons->seasonsVehicle->attributes()->nextRepair;
		}
		++$row;
	}
	return $data;
}

// process vehicles
$data = process_train_items($vehicles, $mapping, $save_id);
$query = prepare_query_ml('fs_farm_vehicle',$data);
execute_query($query);
just_print("Data loaded to fs_farm_vehicle (" . (string)array_key_last($data)  . " rows).");

// process train
$data = process_train_items($train, $mapping, $save_id);
$query = prepare_query_ml('fs_savegame_train',$data);
execute_query($query);
just_print("Data loaded to fs_savegame_train (" . (string)array_key_last($data)  . " rows).");

// proces other items
$data = process_train_items($pallets, $mapping, $save_id, "pallet");
$query = prepare_query_ml('fs_farm_item',$data);
execute_query($query);
just_print("Data loaded to fs_farm_item (" . (string)array_key_last($data)  . " rows).");

?>