<?php

// path to xml to process
$xml_file = load_xml_file(base_path() . "/fs_config/savegame/npc.xml");
$npcs = $xml_file->xpath('//npcs/npc');
unset($xml_file);

$data = array();
$row = 1;
foreach ($npcs as $npc) {
    $data[$row]['save_id'] = $save_id;
    $data[$row]['name'] = (string)$npc->attributes()->name;
    $data[$row]['finished_missions'] = (string)$npc->attributes()->finishedMissions;
    ++$row;
}

$query = prepare_query_ml('fs_savegame_npc',$data);
// just_print($query);
execute_query($query);
just_print("Data loaded to fs_savegame_npc (" . (string)array_key_last($data)  . " rows).");
unset($data);
unset($npcs);

/*
<npcs>
    <npc name="NPC_01" finishedMissions="0"/>
    <npc name="NPC_02" finishedMissions="0"/>
    <npc name="NPC_03" finishedMissions="0"/>
</npcs>
*/
?>