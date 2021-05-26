<?php
// process vehicles.xml
// load tables:
//   fs_vehicle
//   fs_vehicle_fill
//   fs_savegame_train
//   fs_savegame_train_fill
//   fs_farm_pallet
//   fs_savegame_attach

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
	} elseif(str_contains(strtolower($item->attributes()->filename),'pallets')) {
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

function process_items($data_to_process, $mapping, $farm_map = 0, $save_id = 0) {
	$data = array();
	$row = 1;
	foreach($data_to_process as $item) {
		if($save_id != 0) {
			$data[$row]['save_id'] = $save_id;
		}
		if($farm_map != 0) {
			$data[$row]['farm_id'] = find_farm_id((int)$item->attributes()->farmId,$farm_map);			
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
if(array_key_last($vehicles) === NULL) {
	just_print("No vehicles in savegame!");
} else {
	$data = process_items($vehicles, $mapping, $farm_map, $save_id);
	$query = prepare_query_ml('fs_vehicle',$data);
	execute_query($query);
	just_print("Data loaded to fs_vehicle (" . (string)array_key_last($data)  . " rows).");
}

// vehicle maping
sleep(3);
$vehicle_map = get_maping($save_id, 'fs_vehicle');

// process vehicle fill
unset($data);
$data = array();
$row = 1;
foreach ($vehicles as $vehicle) {
	//just_print((string)$vehicle->attributes()->id);
	// vehicle fill units
	if($vehicle->fillUnit)	{
		foreach ($vehicle->fillUnit->unit as $unit) {
			$fill_type = (string)$unit->attributes()->fillType;
			if(!($fill_type == 'DIESEL' OR $fill_type == 'DEF' OR $fill_type == 'AIR')) {
				$data[$row]['vehicle_id'] = $vehicle_map[(int)$vehicle->attributes()->id];
				$data[$row]['fill_type'] = (string)$unit->attributes()->fillType;
	        	$data[$row]['fill_level'] = (float)$unit->attributes()->fillLevel;
	        	++$row;
			}
		}
	}
}

if(!empty($data)) {
	$query = prepare_query_ml('fs_vehicle_fill',$data);
	execute_query($query);
	just_print("Data loaded to fs_vehicle_fill (" . (string)array_key_last($data)  . " rows).");
}
unset($vehicles);
unset($vehicle_map);

// process train
if(array_key_last($train) === NULL) {
	just_print("No train in savegame!");
} else {
	$data = process_items($train, $mapping, 0, $save_id);
	$query = prepare_query_ml('fs_savegame_train',$data);
	execute_query($query);
	just_print("Data loaded to fs_savegame_train (" . (string)array_key_last($data)  . " rows).");
}

// train mapping
sleep(3);
$train_map = get_maping($save_id, 'fs_savegame_train');

// process train fill
unset($data);
$data = array();
$row = 1;
foreach ($train as $vagon) {
	// vehicle fill units
	if($vagon->fillUnit)	{
		foreach ($vagon->fillUnit->unit as $unit) {
			$fill_type = (string)$unit->attributes()->fillType;
			$data[$row]['train_id'] = $train_map[(int)$vagon->attributes()->id];
			$data[$row]['fill_type'] = (string)$unit->attributes()->fillType;
	        $data[$row]['fill_level'] = (float)$unit->attributes()->fillLevel;
	        ++$row;
		}
	}
}
$query = prepare_query_ml('fs_savegame_train_fill',$data);
execute_query($query);
just_print("Data loaded to fs_savegame_train_fill (" . (string)array_key_last($data)  . " rows).");
unset($train);
unset($train_map);

// proces other items
if(array_key_last($pallets) === NULL) {
	just_print("No pallets in savegame!");
} else {
	$data = process_items($pallets, $mapping, $farm_map);
	$query = prepare_query_ml('fs_farm_pallet',$data);
	execute_query($query);
	unset($pallets);
	just_print("Data loaded to fs_farm_pallet (" . (string)array_key_last($data)  . " rows).");
}

// process attachments
if(array_key_last($attachments) === NULL) {
	just_print("No attachments in savegame!");
} else {
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
		$data[$row]['root_id'] = (string)$attach->attributes()->rootVehicleId;  // id from vehicles.xml file game_id
		$data[$row]['attach_id'] = (string)$attach->attachment->attributes()->attachmentId; // id from vehicles.xml file game_id
		$data[$row]['joint_index_input'] = (string)$attach->attachment->attributes()->inputJointDescIndex;
		$data[$row]['joint_index'] = (string)$attach->attachment->attributes()->jointIndex;
		$data[$row]['move_down'] = (string)$attach->attachment->attributes()->moveDown;
		++$row;
	}

	$query = prepare_query_ml('fs_savegame_attach',$data);
	execute_query($query);
	just_print("Data loaded to fs_savegame_attach (" . (string)array_key_last($data)  . " rows).");
}

unset($data);
unset($row);
unset($attachments);
unset($mapping);

?>