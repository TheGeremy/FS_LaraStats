<?php

// path to xml to process
$xml_file = load_xml_file(base_path() . "/fs_config/savegame/vehicles.xml");
$vehicles_items = $xml_file->xpath('//vehicles/vehicle'); // all vehicle elements under vehicles

$train = array();
$pallets = array();
$vehicles = array();
$attachments = array();
$attachments = $xml_file->xpath('//vehicles/attachments'); // all vehicle elements under vehicles

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

function process_items($data_to_process, $mapping, $save_id = 0, $farm_id = 0) {
	$data = array();
	$row = 1;
	foreach($data_to_process as $item) {
		if($save_id != 0) {
			$data[$row]['save_id'] = $save_id;
		}
		if($farm_id !=  0) {
			$data[$row]['farm_id'] = $farm_id;
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
$data = process_items($vehicles, $mapping, 0, $farm_id);
$query = prepare_query_ml('fs_farm_vehicle',$data);
execute_query($query);
unset($vehicles);
just_print("Data loaded to fs_farm_vehicle (" . (string)array_key_last($data)  . " rows).");

// process train
$data = process_items($train, $mapping, $save_id, 0);
$query = prepare_query_ml('fs_savegame_train',$data);
execute_query($query);
unset($train);
just_print("Data loaded to fs_savegame_train (" . (string)array_key_last($data)  . " rows).");

// proces other items
$data = process_items($pallets, $mapping, 0, $farm_id);
$query = prepare_query_ml('fs_farm_pallet',$data);
execute_query($query);
unset($pallets);
just_print("Data loaded to fs_farm_pallet (" . (string)array_key_last($data)  . " rows).");

// process attachments
unset($mapping);
$mapping = array(
	"rootVehicleId" => "root_Id",
	"attachmentId" => "attach_id",
	"inputJointDescIndex" => "joint_index_input",
	"jointIndex" => "joint_index",
	"moveDown" => "move_down",
);

$data = array();
$row = 1;
foreach ($attachments as $attach) {
	$data[$row]['save_id'] = $save_id;
	$data[$row]['root_id'] = (string)$attach->attributes()->rootVehicleId;
	$data[$row]['attach_id'] = (string)$attach->attachment->attributes()->attachmentId;
	$data[$row]['joint_index_input'] = (string)$attach->attachment->attributes()->inputJointDescIndex;
	$data[$row]['joint_index'] = (string)$attach->attachment->attributes()->jointIndex;
	$data[$row]['move_down'] = (string)$attach->attachment->attributes()->moveDown;
	++$row;
}

$query = prepare_query_ml('fs_savegame_attach',$data);
execute_query($query);
just_print("Data loaded to fs_savegame_attach (" . (string)array_key_last($data)  . " rows).");

unset($data);
unset($row);
unset($attachments);

?>