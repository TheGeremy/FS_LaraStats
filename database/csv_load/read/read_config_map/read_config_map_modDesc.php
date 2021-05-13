<?php
// load modDesc.xml file for specified map mod
$xml_map_moddesc = simplexml_load_file($map_dir . "modDesc.xml");

if($xml_map_moddesc === false)
{
	die('Unable to load and parse the xml file: ' . error_get_last()['message'] );
}

$data[1] = array();
$data[1]['map_title'] = $map_title;
$data[1]['note'] = ""; // query preparing function will change this to nul
$data[1]['author'] = (string)$xml_map_moddesc->author;
$data[1]['version'] = (string)$xml_map_moddesc->version;
$data[1]['mod_desc_version'] = (string)$xml_map_moddesc->attributes()->descVersion;
$data[1]['short_desc_en'] = trim((string)$xml_map_moddesc->maps->map->description->en);
$data[1]['short_desc_cz'] = trim((string)$xml_map_moddesc->maps->map->description->cz);
$data[1]['description_en'] = trim((string)$xml_map_moddesc->description->en);
$data[1]['description_cz'] = trim((string)$xml_map_moddesc->description->cz);

$query = prepare_query_ml('fs_map_dim', $data);
//just_print($query);
//execute_query($connection, $query);
just_print('Map info stored in fs_map_dim.');

?>