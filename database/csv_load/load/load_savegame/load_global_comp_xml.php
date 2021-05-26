<?php
$xml_file = load_xml_file(base_path() . "/fs_config/savegame/globalCompany.xml");
$settings = $xml_file->xpath('//globalCompany/settings/setting');
unset($xml_file);
/*
<globalCompany>
    <settings>
        <setting name="horseHelper" value="true"/>
        <setting name="objectInfo" value="false"/>
        <setting name="cutBales" value="false"/>
        <setting name="extendedPlaceable" value="false"/>
        <setting name="moreTrees" value="false"/>
    </settings>
</globalCompany>
*/

$data = array();
$row = 1;
foreach ($settings as $line) {
    foreach($line->attributes() as $key => $value) {
        $data[$row]['save_id'] = $save_id;
        $data[$row]['setting'] = convert_string((string)$line->attributes()->name);
        $data[$row]['value'] = (string)$line->attributes()->value;
    }
    ++$row;
}

$query = prepare_query_ml('fs_global_comp',$data);
execute_query($query);
just_print("Data loaded to fs_global_comp (" . (string)array_key_last($data)  . " rows).");
unset($data);
?>