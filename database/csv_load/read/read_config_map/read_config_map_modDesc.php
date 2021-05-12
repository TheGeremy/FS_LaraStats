<?php
// include external php code 
// base_path() laravel base directory
require base_path() . '/database/csv_load/scripts/user_defined_functions.php';

// path to xml to process
$xml_file_path = base_path() . "/fs_config/config_map/map_slovak_village/modDesc.xml";

// load xml file to array
$xml_file = simplexml_load_file($xml_file_path);

// create array for data, those will be inserted into database
$data = array();

$data[1]['map_title'] = (string)$xml_file->title->en;
$data[1]['author'] = (string)$xml_file->author; 
$data[1]['version'] = (string)$xml_file->version; 
$data[1]['mod_desc_version'] = (string)$xml_file->attributes()->descVersion;
$data[1]['short_desc_en'] = (string)$xml_file->maps->map->description->en;
$data[1]['short_desc_cz'] = (string)$xml_file->maps->map->description->cz;
$data[1]['description_en'] = (string)$xml_file->description->en;
$data[1]['description_cz'] = (string)$xml_file->description->cz;

$query = prepare_query_ml('fs_map_dim',$data);
print_r($query);
?>