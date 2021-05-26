<?php

$xml_file = load_xml_file(base_path() . "/fs_config/savegame/treePlant.xml");
$trees = $xml_file->xpath('//treePlant/tree');
unset($xml_file);

if(!empty($trees)) {
    $mapping = array(
        "treeType" => "type",
        "position" =>  "position",
        "growthState" => "growth_state",
        "growthStateI" => "growth_state_i",
        "isGrowing" => "is_growing"
    );

    $data = array();
    $row = 1;
    foreach ($trees as $tree) {
        $data[$row]['save_id'] = $save_id;
        foreach ($tree->attributes() as $key => $value) {
            if(array_key_exists($key,$mapping)) {
                $data[$row][$mapping[$key]] = (string)$value;
            }
        }
        ++$row;
    }

    $query = prepare_query_ml('fs_savegame_forestry',$data);
    // just_print($query);
    execute_query($query);
    just_print("Data loaded to fs_savegame_npc (" . (string)array_key_last($data)  . " rows).");
    unset($data);
    unset($mapping);
}

unset($trees);
/*
<treePlant>
    <tree treeType="TREEFIR" position="-703.0046 41.2415 391.4611" rotation="0.0000 317.0735 0.0000" growthState="0.941832" growthStateI="6" isGrowing="false" splitShapeFileId="1"/>
    <tree treeType="TREEFIR" position="-710.2549 41.8766 398.2556" rotation="0.0000 308.3946 0.0000" growthState="0.940443" growthStateI="6" isGrowing="false" splitShapeFileId="2"/>
    <tree treeType="TREEFIR" position="-715.5269 41.6141 395.4814" rotation="0.0000 4.8787 0.0000" growthState="0.895256" growthStateI="6" isGrowing="false" splitShapeFileId="3"/>
</treePlant>
*/
?>