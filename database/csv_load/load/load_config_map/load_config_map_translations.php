<?php
$xml_lang_list = array('l10n_cz.xml', 'l10n_en.xml', 'l10n_de.xml');

function store_translation($map_dir, $xml_file_name){
   	if(file_exists($map_dir . $xml_file_name)) {
	   	$xml_file = simplexml_load_file($map_dir . $xml_file_name);
	   	$lang = substr($xml_file_name,strpos($xml_file_name,'_')+1,2);

		$mapping = array(
			"name" => "text_from",
			"text" => "text_to"
		);

		if($xml_file === false)
		{
			die('Unable to load and parse the xml file: ' . error_get_last()['message'] );
		}

		$data = array();
		$row = 1;
		foreach ($xml_file->texts->text as $line) {
			$data[$row]['type'] = 2;   ///1/2/3 game/map/mod
			$data[$row]['lang'] = $lang;
			foreach ($line->attributes() as $key => $value) {
				$data[$row][$mapping[$key]] = (string)$value;
			}
			++$row;
		}

		$query = prepare_query_ml('fs_translation_dim', $data);
		execute_query($query);
		just_print(strtoupper($lang) . " translation loaded to fs_translation_dim (" . (string)($row - 1) . " rows).");
	} else {
		just_print("Language files for modded map of given savegame were not found!");
	}
}

foreach ($xml_lang_list as $xml_file_name) {
	store_translation($map_dir, $xml_file_name);
}

unset($xml_lang_list);
?>