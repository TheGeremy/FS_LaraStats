<?php
// path to run in browser
// http://php.nuba.synology.me/console/read/read_vehicles_pallets.php
 
// include external php code 
// base_path() laravel base directory
require base_path() . '/database/csv_load/scripts/user_defined_functions.php';

// path to xml to process
$xml_file_path = base_path() . "/database/csv_load/savegame3/vehicles.xml";

// array of values to save in db
$values_to_save = array(
// vehicle
"modName","filename","age","price","farmId","propertyState","operatingTime"
);

// load xml file to array
$xml_file = simplexml_load_file($xml_file_path);
$pallete_xml = $xml_file->xpath('//vehicles/vehicle'); // all vehicle elements under vehicles

// get last savegame id
//$connection = mariadb_connect();
//$saveId = get_savegame_id($connection);

// create mapping array to map fields to table columns
$mapping = array(
	'id' => 'game_id',
	'modName' => 'mod_name',
	'filename' => 'filename',
	'isAbsolute' => 'is_absolute',
	'age' => 'age',
	'price' => 'price',
	'farmId' => 'farm_id',
	'propertyState' => 'property_state',
	'operatingTime'  => 'operating_time',
	'index'  => 'index',
	'fillType'  => 'fill_name',
	'fillLevel'  => 'fill_level'
);


// create array for data, those will be inserted into database
$data = array();
$row = 1;

foreach ($xml_file as $item) {
	if(strpos($item->attributes()['filename'],"pallet")) {
		foreach($item->attributes() as $key => $value) {
			if($key == 'filename') {
				$value = cut_string($value,"/",".");
			}
			$data[$row][$mapping[$key]] = (string)$value;
		}
		foreach($item->fillUnit->unit->attributes() as $key => $value) {
			$data[$row][$mapping[$key]] = (string)$value;
		}
	++$row;
	}
}


$query = prepare_query_ml('fs19_item',$data);
print_r($query);
//print_r($data);

//mariadb_disconnect($connection);
 ?>