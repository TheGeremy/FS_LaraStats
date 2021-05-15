<?php
// load modDesc.xml file for specified map mod
$xml_map_moddesc = load_xml_file($map_dir . "modDesc.xml");

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
execute_query($query);
just_print('Map info stored in fs_map_dim.');
// update map_id
$map_id = get_map_id($map_title);
just_print("Map id: " . $map_id);
?>