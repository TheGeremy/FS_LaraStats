<?php
// path to run in browser
// http://php.nuba.synology.me/console/read/read_gameOwn/read_fruit_types.php
 
// include external php code 
require './../../scripts/user_defined_functions.php';

// path to xml to process
$xml_file_path = "./../../../fs_config/_gameOwn/maps_fruitTypes.xml";

// array of values to save in db
$values_to_save = array(
// vehicle
"modName","filename","age","price","farmId","propertyState","operatingTime"
);

print_before();

// load xml file to array
$xml_file = simplexml_load_file($xml_file_path);
$json_obj = json_encode($xml_file);
$xml_data = $xml_file->xpath('//map/fruitTypes/fruitType'); // all fruitType elements (tags)
//json_decode($json_obj, TRUE);

// array - mapping attribute to database table column
$mapping = array(
	'fruitType_name' => 'fruit_name',
	'fruitType_useForFieldJob' => 'field_job',
	'cultivation_needsSeeding' => 'needs_seeding',
	'cultivation_allowsSeeding' => 'allow_seeding',
	'cultivation_alignsToSun' => 'align_to_sun',
	'cultivation_seedUsagePerSqm' => 'seed_per_sqm',
	'harvest_minHarvestingGrowthState' => 'min_harvest_growth_state',
	'harvest_maxHarvestingGrowthState'  => 'max_harvest_growth_state',
	'harvest_cutState' => 'cut_growth_state',
	'harvest_minForageGrowthState' => 'min_forage_growth_state',
	'harvest_literPerSqm' => 'harvest_per_sqm',
	'growth_witheringNumGrowthStates' => 'withering_growth_states',
	'growth_numGrowthStates' => 'growth_states',
	'growth_growthStateTime' => 'growth_state_time',
	'growth_resetsSpray' => 'resets_spray',
	'growth_growthRequiresLime' => 'growth_requires_lime',
	'windrow_name' => 'windrow_name',
	'windrow_litersPerSqm' => 'windrow_per_sqm',
	'options_lowSoilDensityRequired' => 'low_soil_density_required',
	'options_increasesSoilDensity' => 'increases_soil_density',
	'options_consumesLime' => 'consumes_lime'
);

// list of subtags of fruitType tag to read
$only_tags = array(
	'cultivation',
	'harvest',
	'growth',
	'windrow',
	'options'
);
// get last savegame id
//$connection = mariadb_connect();

// array to store all vehicles
$data = array();
$row = 0;

foreach($xml_data as $element) {
	// this info about fruit is from game base, so for default maps, the map id is therefore zero
	// each map can have specific fruit types
	$data[$row]['map_id'] = 0;

	// first read fruitType tag which is main tag for fruut type
	// then reed some subtags of fruitType tag defined in only_tags array

	// get tag name for fruitType tag
	$tag = $element->getName();
	foreach($element->attributes() as $key => $value) {
		$composit_key = $tag . '_' . $key;
		if(array_key_exists($composit_key,$mapping)) {
			$data[$row][$mapping[$composit_key]] = (string)$value;
		}		
	}
 	
 	// go trough sub tags of fruitType tag, but only those listed in only_tags
	foreach($only_tags as $tag) {
		// if fruitType tag has tag from only_tags array		
		if($element->$tag) {
			// go trough all the attributes
			foreach($element->$tag->attributes() as $key => $value) {
				// some tags have same name of attribute so combine tag name with attribute
				$composit_key = $tag . '_' . $key;
				// if attribute listed in mappings put it to array to create query to store them in database
				if(array_key_exists($composit_key,$mapping)) {
					$data[$row][$mapping[$composit_key]] = (string)$value;
				}		
			}
		}
	}
	++$row;
}

$query = prepare_query_ml('fs_fruit_type_dim',$data);
print_r($query . "\n\n");
//execute_query($connection,$query);


/*
array_key_exists
in_array
 */
//print_r("done");
print_after();

//mariadb_disconnect($connection);

?>