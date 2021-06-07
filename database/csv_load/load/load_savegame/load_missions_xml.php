<?php

// path to xml to process
$xml_file = load_xml_file(base_path() . "/fs_config/savegame/missions.xml");
$missions = $xml_file->xpath('//missions/mission');
unset($xml_file);

// orginal web stats info
// Field	Size	Type	reward	Rental fee	Farm	Status

// array of values to save in db
$mapping = array (
    "mission_type" => "type",
    "mission_reward" => "reward",
    "mission_status" => "status",
    "mission_success" => "success",
    "numObjects" => "trans_amount",
    "field_id" => "field_id",
    "field_vehicleUseCost" => "rental",
    "field_fruitTypeName" => "fruit_type",              // only those missions that are able to specify fruit type
    "activeId" => "active_id",                          // when is mission contracted by any farm, else missiog, it looks like it is a farm_id
    "farmId" => "farm_id",                              // when is missoin contracted by any farm, else missing
    "stealingCost" => "stealing_cost"                   // when is missoin contracted by any farm, else missing
);

$data = array();
$row = 1;
foreach ($missions as $mission) {
    $data[$row]['save_id'] = $save_id;
    
    /*** only when mission is contracted by any farm ***/
    if ($mission->attributes()->farmId) {
        $data[$row]['farm_id'] = find_farm_id((int)$mission->attributes()->farmId, $farm_map); // if mission not contracted this key not exist so NULL
    }
    if ($mission->attributes()->activeId) {
        $data[$row]['active_id'] = (string)$mission->attributes()->activeId;        
    }
    if ($mission->attributes()->stealingCost) {
        $data[$row]['stealing_cost'] = (string)$mission->attributes()->stealingCost;
    }
    /*** only when mission is contracted by any farm ***/


    foreach ($mission->attributes() as $key => $value) {
        //just_print($mission->getName() . "_" . (string)$key . ": " . (string)$value);
        $key = $mission->getName() . "_" . (string)$key;
        if(array_key_exists($key, $mapping)) {
            $data[$row][$mapping[$key]] = (string)$value;
        }
    }

    foreach ($mission->children() as $child) {
        foreach ($child->attributes() as $key => $value) {
            //just_print($child->getName() . "_" . (string)$key . ": " . (string)$value);
            $key = $child->getName() . "_" . (string)$key;
            if(array_key_exists($key, $mapping)) {
                $data[$row][$mapping[$key]] = (string)$value;
            }
        }
    }

    if($mission->attributes()->type == "transport") {
        $data[$row]['time_left'] = (string)$mission->attributes()->timeLeft;
        $data[$row]['trans_product'] = (string)$mission->attributes()->config;
        $data[$row]['trans_from'] = (string)$mission->attributes()->pickupTrigger;
        $data[$row]['trans_to'] = (string)$mission->attributes()->dropoffTrigger;
    }

    if($mission->attributes()->type == 'harvest') {
        $data[$row]['sell_point'] = (string)$mission->harvest->attributes()->sellPoint;
        $data[$row]['expected_liters'] = (string)$mission->harvest->attributes()->expectedLiters;
        $data[$row]['deposited_liters'] = (string)$mission->harvest->attributes()->depositedLiters;
    }

    if($mission->attributes()->type == 'mow_bale') {
        $data[$row]['sell_point'] = (string)$mission->bale->attributes()->sellPoint;
        $data[$row]['deposited_liters'] = (string)$mission->bale->attributes()->depositedLiters;
        $data[$row]['bale_type'] = (string)$mission->bale->attributes()->fillTypeName;
    }

    ++$row;
}

$query = prepare_query_ml('fs_savegame_mission',$data);
//just_print($query);
execute_query($query);
just_print("Data loaded to fs_savegame_mission (" . (string)array_key_last($data)  . " rows).");
unset($data);
unset($mission);
unset($mapping);

/*
<missions>
    <mission type="cultivate" reward="3611" status="0" success="false">
        <field id="25" sprayFactor="0.333333" spraySet="false" plowFactor="1.000000" state="3" vehicleGroup="4" vehicleUseCost="1422.739502" spawnedVehicles="false" limeFactor="1.000000" weedFactor="1.000000"/>
    </mission>
	<mission type="mow_bale" reward="4139" status="0" success="false">
        <field id="8" sprayFactor="1.000000" spraySet="false" plowFactor="1.000000" state="2" vehicleGroup="2" vehicleUseCost="230.000000" spawnedVehicles="false" growthState="4" limeFactor="1.000000" weedFactor="1.000000" fruitTypeName="GRASS"/>
        <bale sellPoint="107" fillTypeName="DRYGRASS_WINDROW" depositedLiters="0.000000"/>
    </mission>
    <mission type="harvest" reward="3797" status="0" success="false">
        <field id="4" sprayFactor="0.000000" spraySet="false" plowFactor="1.000000" state="2" vehicleGroup="1" vehicleUseCost="421.899994" spawnedVehicles="false" growthState="5" limeFactor="0.000000" weedFactor="1.000000" fruitTypeName="CANOLA"/>
        <harvest sellPoint="134" expectedLiters="16517.384766" depositedLiters="0.000000"/>
    </mission>
    <mission type="harvest" activeId="1" reward="17105" status="1" success="false" farmId="1">
        <field id="5" sprayFactor="0.000000" spraySet="false" plowFactor="0.000000" state="2" vehicleGroup="1" vehicleUseCost="2695.335449" spawnedVehicles="true" growthState="6" limeFactor="1.000000" weedFactor="1.000000" fruitTypeName="CANOLA"/>
        <harvest sellPoint="17" expectedLiters="81171.062500" depositedLiters="0.000000"/>
    </mission>
    <mission type="fertilize" activeId="1" reward="15119" status="2" success="true" farmId="1" stealingCost="0.000000">
        <field id="31" sprayFactor="0.333333" spraySet="false" plowFactor="1.000000" state="2" vehicleGroup="5" vehicleUseCost="2159.872070" spawnedVehicles="false" growthState="7" limeFactor="0.000000" weedFactor="0.500000" fruitTypeName="SUGARBEET"/>
    </mission>
    <mission type="transport" reward="3653" status="0" success="false" timeLeft="99073154" config="OLD METAL" pickupTrigger="TRANSPORT09" dropoffTrigger="TRANSPORT03" objectFilename="$moddir$FS19_SlovakVillage/objects/pallets/missions/transportPalletOldMetalParts.i3d" numObjects="2"/>
    <mission type="snow" reward="28481" status="0" success="false" config="snowContract01"/>
</missions>
*/
?>