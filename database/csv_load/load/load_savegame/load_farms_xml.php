<?php

// path to xml to process
$xml_file = load_xml_file(base_path() . "/fs_config/savegame/farms.xml");
$farms = $xml_file->xpath('//farms/farm'); // all farm
$stats = $xml_file->xpath('//farms/farm/statistics'); // various stats about farm
$fin_stats = $xml_file->xpath('//farms/farm/finances'); // finance stats about farm

unset($xml_file);

// array of values to save into fs_farm
$mapping = array(
	"name" => "name",
	"color" => "color",
	"loan" => "loan",
	"money" => "money",
	"loanAnnualInterestRate" => "loan_annual_rate"
);

$data = array();
$row = 1;
foreach ($farms as $farm) {
	$data[$row]['save_id'] = $save_id;
	foreach ($farm->attributes() as $key => $value) {
		if(array_key_exists($key,$mapping)) {
			$data[$row][$mapping[$key]] = (string)$value;
		}
	}
	++$row;
}

$query = prepare_query_ml('fs_farm', $data);
execute_query($query);
unset($farms);

$farm_id = get_farm_id();

//--- process farm various statistics -----------------------------------------
unset($mapping);
$mapping = array(
    "revenue" => "revenues",
    "expenses" => "expenses", 
    // bales
    "baleCount" => "bales",
    // distance
    "traveledDistance" => "traveled_distance",
    // new animals
    "breedCowsCount" => "breed_cows",
    "breedSheepCount" => "breed_sheeps",
    "breedPigsCount" => "breed_pigs",
    "breedChickenCount" => "breed_chickens",
    "breedHorsesCount" => "breed_horses",
    // missions
    "fieldJobMissionCount" => "field_job_mission",
    "fieldJobMissionByNPC" => "field_job_mission_npc",
    "transportMissionCount" => "transport_mission",
    // forestry
    "plantedTreeCount" => "planted_trees",
    "cutTreeCount" => "cut_trees",
    "treeTypesCut" => "tree_types_cut",
    "woodTonsSold" => "wood_tons_sold",
    // usage
    "fuelUsage" => "fuel_usage",
    "seedUsage" => "seed_usage",
    "sprayUsage" => "spray_usage",
	// hectares
    "workedHectares" => "worked_ha",
    "cultivatedHectares" => "cultivated_ha",
    "sownHectares" => "sown_ha",
    "fertilizedHectares" => "fertilized_ha",
    "threshedHectares" => "threshed_ha",
    "plowedHectares" => "plowed_ha",
    // time
    "workedTime" => "worked_time",
    "cultivatedTime" => "cultivated_time",
    "sownTime" => "sown_time",
    "fertilizedTime" => "fertilized_time",
	"threshedTime" => "threshed_time",
    "plowedTime" => "plowed_time",
    "playTime" => "play_time"
);

unset($data);
$data = array();
foreach($stats[0] as $key => $value) {
	$data[0]['farm_id'] = $farm_id;
	if(array_key_exists($key,$mapping)) {
		$data[0][$mapping[$key]] = (string)$value;
	}	
}

$query = prepare_query_ml('fs_farm_stat', $data);
execute_query($query);
unset($stats);

//-- process farm finance statistics --------------------------------------------

unset($mapping);
$mapping = array(
	// incomes
	"soldBuildings" => "sold_buildings",
	"fieldSelling" => "field_selling",
	"propertyIncome" => "property_income",
	"soldAnimals" => "sold_animals",
	"soldWood" => "sold_wood",
	"soldBales" => "sold_bales",
	"soldWool" => "sold_wool",
	"soldMilk" => "sold_wilk",
	"soldVehicles" => "sold_vehicles",
	"harvestIncome" => "harvest_income",
	"incomeBga" => "income_bga",
	"missionIncome" => "mission_income",
	// costs
	"newVehiclesCost" => "new_vehicles_cost",	
	"newAnimalsCost" => "new_animals_cost",	
	"constructionCost" => "construction_cost",	
	"fieldPurchase" => "field_purchase",	
	"vehicleRunningCost" => "vehicle_running_cost",
	"vehicleLeasingCost" => "vehicle_leasing_cost",
	"animalUpkeep" => "animal_upkeep",
	"propertyMaintenance" => "property_maintenance",
	"purchaseFuel" => "purchase_fuel",
	"purchaseSeeds" => "purchase_seeds",
	"purchaseFertilizer" => "purchase_fertilizer",
	"purchaseSaplings" => "purchase_saplings",
	"purchaseWater" => "purchase_water",
	"wagePayment" => "wage_payment",
	"other" => "other",
	"loanInterest" => "loan_interest",
	"seasons_livery_stable" => "seasons_livery_stable"
);

unset($data);
$data = array();
$row = 1;
foreach($fin_stats[0] as $stat) {
	$data[$row]['farm_id'] = $farm_id;
	$data[$row]['stats_day'] = (string)$stat->attributes()->day;
	foreach ($stat as $key => $value) {
		$data[$row][$mapping[$key]] = (string)$value;
	}
	++$row;
}

$query = prepare_query_ml('fs_farm_fin_stat',$data);
execute_query($query);
unset($fin_stats);

unset($mapping);
unset($data);
unset($row);

?>