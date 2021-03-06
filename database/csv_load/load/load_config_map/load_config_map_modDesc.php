<?php
// load modDesc.xml file for specified map mod
$required_file = base_path() . '/database/csv_load/load/load_config_map/load_config_map_modDesc.php';
if(file_exists($required_file))  {
	$xml_map_moddesc = load_xml_file($map_dir . "modDesc.xml");;
	$data[1] = array();
	$data[1]['title'] = $map_title;
	$data[1]['note'] = ""; // query preparing function will change this to nul
	$data[1]['author'] = (string)$xml_map_moddesc->author;
	$data[1]['version'] = (string)$xml_map_moddesc->version;
	$data[1]['desc_version'] = (string)$xml_map_moddesc->attributes()->descVersion;
	$data[1]['short_desc_en'] = trim((string)$xml_map_moddesc->maps->map->description->en);
	$data[1]['short_desc_cz'] = trim((string)$xml_map_moddesc->maps->map->description->cz);
	$data[1]['description_en'] = trim((string)$xml_map_moddesc->description->en);
	$data[1]['description_cz'] = trim((string)$xml_map_moddesc->description->cz);

	$query = prepare_query_ml('fs_map_dim', $data);
	execute_query($query);
	unset($xml_map_moddesc);
	unset($data);
	just_print('Map info stored in fs_map_dim.');
	// update map_id
	$map_id = get_map_id($map_title);
	just_print("Map id: " . $map_id);
} else {
	exit("Unable to load required file: $required_file");
}

?>