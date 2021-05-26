<?php
// process items.xml
// load tables:
//   fs_farm_bale
//   fs_husbandry
//   fs_husbandry_animal
//   fs_husbandry_pallet
//   fs_husbandry_spillage
//   fs_husbandry_food
//   fs_husbandry_straw
//   fs_husbandry_milk
//   fs_husbandry_manure
//   fs_husbandry_lmanure
//   fs_husbandry_water
//   fs_sellst
//   fs_sellst_product
//   fs_sellst_product_curve
//   fs_silo
//   fs_silo_product
//   fs_farm_tree
//   fs_bsilo
//   fs_bsilo_bunker
//   fs_bga
//   fs_bga_product
//   fs_bga_bunker
//   fs_buyst
//   fs_item

// items classNames to process
// Bale (possible values )
// AnimalHusbandry - will be separate because goes to another table not to fs_farm_item
// BuyingStationPlaceable / SellingStationPlaceable or everything that has Sell / Buy in className
// TreePlaceable or anything that has Tree in filename
// SiloPlaceable or anything that has silo in filename
// then other items except those mentioned above

// path to xml to process
$xml_file = load_xml_file(base_path() . "/fs_config/savegame/items.xml");
$all_items = $xml_file->xpath('//items/item');

// separate items to categories to store each in different table
$bales = array();
$animal_husbandries = array();
$sell_stations = array();
$buy_stations = array();
$bunker_silos = array();
$bgas = array();
$trees = array();
$silos = array();
$items = array();

foreach ($all_items as $item) {
	$filename = (string)$item->attributes()->filename;
	$class_name = (string)$item->attributes()->className;

	if($class_name == 'Bale') {
		array_push($bales, $item);
	} elseif($class_name == 'AnimalHusbandry') {
		array_push($animal_husbandries, $item);
	} elseif($class_name == 'SellingStationPlaceable' OR str_contains(strtolower($class_name), 'sell')) {
		array_push($sell_stations,$item);
	} elseif($class_name == 'BuyingStationPlaceable') {
		array_push($buy_stations,$item);
	} elseif($class_name == 'BgaPlaceable') {
		array_push($bgas,$item);	
	} elseif($class_name == 'BunkerSiloPlaceable') {
		array_push($bunker_silos,$item);
	} elseif(   $class_name == 'TreePlaceable' 
			 OR str_contains(strtolower($class_name), 'tree') 
			 OR str_contains(strtolower($filename), 'tree')
			) {
				array_push($trees,$item);
	} elseif($class_name == 'SiloPlaceable' OR $item->storage) {
		array_push($silos,$item);
	} else {
		if(!str_contains(strtolower($class_name),'train')) {
			array_push($items,$item);
		}
	}
}

unset($all_items);
unset($xml_file);

//--- process bales ---------------------------------------------------------------------------------------------------------------------------
if(!empty($bales)) {
	// mapping for fs_farm_bale
	$mapping = array(
		"id" => "game_id",
		"isConnectedToInline" => "is_inline",
		"filename" => "filename",
		"position" => "position",
		"valueScale" => "value_scale",
		"fillLevel" => "fill_level",
		"wrappingState" => "wrapping_state",
		"wrappingColor" => "wrapping_color",
		"age" => "age",
		"rotVolume" => "rot_volume",
		"initVolume" => "init_volume"
	);

	// manually added fields
	// 'bale_type'
	// 'fill_type'

	$data = array();
	$row = 1;
	foreach($bales as $bale) {
		$filename = (string)$bale->attributes()->filename;
		$data[$row]['farm_id'] = find_farm_id((int)$bale->attributes()->farmId, $farm_map);
		
		// basic game types
		if(str_contains(strtolower($filename),'square')) {
			$data[$row]['bale_type'] = 'SQUARE';	
		} elseif(str_contains(strtolower($filename),'round')) {
			$data[$row]['bale_type'] = 'ROUND';	
		} else {
			$data[$row]['bale_type'] = 'OTHER';	
		} 
			
		// basic game types
		if(str_contains(strtolower($filename),'hay')) {
			$data[$row]['fill_type'] = 'HAY';
		} elseif(str_contains(strtolower($filename),'straw')) {
			$data[$row]['fill_type'] = 'STRAW';
		} elseif(str_contains(strtolower($filename),'grass')) {
			$data[$row]['fill_type'] = 'GRASS';
		} elseif(str_contains(strtolower($filename),'silage')) {
			$data[$row]['fill_type'] = 'SILAGE';
		} elseif(str_contains(strtolower($filename),'cotton')) {
			$data[$row]['fill_type'] = 'COTTON';
		} else {
			$data[$row]['fill_type'] = 'OTHER';
		}
		
		foreach($bale->attributes() as $key => $value) {
			if(array_key_exists($key,$mapping)) {
				$data[$row][$mapping[$key]] = (string)$value;
			}
		}
		++$row;
	}

	$query = prepare_query_ml('fs_farm_bale', $data);
	execute_query($query);
	just_print("Data loaded to fs_farm_bale (" . (string)array_key_last($data)  . " rows).");
	unset($data);
}
unset($bales);

//--- process animal husbandries ---------------------------------------------------------------------------------------------------------------------------
if(!empty($animal_husbandries)) {
	unset($mapping);
	// mapping for fs_husbandry table
	$mapping = array(
		"id" => "game_id",
		"modName" => "mod_name",
		"className" => "class_name",
		"filename" => "filename",
		"position" => "position",
		"age" => "age",
		"price" => "price",
		"boughtWithFarmland" => "default",
		"seasonsYears" => "seasons_years",
		"globalProductionFactor" => "glb_prod_factor",
		"averageGlobalProductionFactor" => "avg_glb_prod_factor"
	);

	$data = array(); 		// fs_husbandry
	$row = 1;
	foreach($animal_husbandries as $animal_husbandry) {
		$data[$row]['save_id'] = $save_id;
		$data[$row]['farm_id'] = find_farm_id((int)$animal_husbandry->attributes()->farmId,$farm_map);
		
		foreach($animal_husbandry->module as $module) {
			if($module->animal) {
				$animal = (string)$module->animal->attributes()->fillType;
				$data[$row]['animal'] = substr($animal,0,strpos($animal,'_'));
				break;
			} else {
				$data[$row]['animal'] = 'UNKNOWN';
			}
		}
		foreach($animal_husbandry->attributes() as $key => $value) {
			if(array_key_exists($key,$mapping)) {
				$data[$row][$mapping[$key]] = (string)$value;		
			}
		}
		++$row;
	}

	$query = prepare_query_ml('fs_husbandry', $data);
	execute_query($query);
	just_print("Data loaded to fs_husbandry (" . (string)array_key_last($data)  . " rows).");
	unset($data);

	//--- process anmal husbandries modules ---------------------------------------------------------------------------------------------------------------------------
	sleep(3);
	// load mapping of husbandries -> mapping game id to database id
	$husbandry_map = get_maping($save_id,'fs_husbandry');

	foreach($animal_husbandries as $animal_husbandry) {
		if(array_key_exists((int)$animal_husbandry->attributes()->id,$husbandry_map)) {
			$husbandry_id = $husbandry_map[(int)$animal_husbandry->attributes()->id];
		}

		foreach($animal_husbandry->module as $module) {
			if($module->attributes()->name == 'animals') {
	        	/*
	        	<module name="animals" fillCapacity="0.000000">
	            	<animal fillType="COW_TYPE_BLACK_WHITE" dirtScale="0.000000" seasons_isFemale="true" seasons_weight="507.768646" seasons_age="2.084472" seasons_id="101" seasons_timeUntilBirth="0.000000" seasons_timeSinceBirth="-99.837463"/>
	        	</module>
	        	-- other animals
	        		<animal fillType="PIG_TYPE_RED" dirtScale="0.000000" seasons_isFemale="true" seasons_weight="198.114136" seasons_age="1.166664" seasons_id="1" seasons_timeUntilBirth="0.000000" seasons_timeSinceBirth="-99.840935"/>			
		            <animal fillType="CHICKEN_TYPE_WHITE" dirtScale="0.000000" seasons_isFemale="true" seasons_weight="2.315912" seasons_age="0.633559" seasons_id="200" seasons_timeUntilBirth="0.000000" seasons_timeSinceBirth="-99.752396"/>        		
					<animal fillType="HORSE_TYPE_GREY" dirtScale="0.000000" seasons_isFemale="false" seasons_weight="545.000000" seasons_age="3.052222" seasons_id="1" isRidingActive="false" name="Edward" fitnessScale="0.347671" healthScale="1.000000" ridingTimer="0.000000"/>
					<animal fillType="SHEEP_TYPE_BROWN" dirtScale="0.000000" seasons_isFemale="true" seasons_weight="52.503162" seasons_age="1.374996" seasons_id="114" seasons_timeUntilBirth="0.336667" seasons_timeSinceBirth="-99.624573"/>
	        	*/
	        	unset($mapping);
	        	// maping for fs_husbandry_animal
	        	$mapping = array(
	        		"fillType" => "fill_type",
	        		"dirtScale" => "dirt_scale",
	        		"seasons_isFemale" => "seasons_is_female",
	        		"seasons_weight" => "seasons_weight",
	        		"seasons_age" => "seasons_age",
	        		"seasons_id" => "seasons_id",
	        		"seasons_timeUntilBirth" => "seasons_time_to_birth",
	        		"seasons_timeSinceBirth" => "seasons_time_from_birth",
	        		"isRidingActive" => "riding_active",
	        		"name" => "name",
	        		"fitnessScale" => "fitness_scale",
	        		"healthScale" => "health_scale",
	        		"ridingTimer" => "riding_timer"
	        	);
	        	$data = array(); 			// fs_husbandry_animal
	        	$row = 1;
				foreach($module->children() as $animal) {
					$data[$row]['husbandry_id'] = $husbandry_id;
					$data[$row]['type'] = ucwords(substr((string)$animal->attributes()->fillType,0,strpos((string)$animal->attributes()->fillType,'_')));
					foreach ($animal->attributes() as $key => $value) {
						if(array_key_exists($key,$mapping)) {
							$data[$row][$mapping[$key]] = (string)$value;
						}
					}
					
					++$row;
				}
				if(!empty($data)) {
					$query = prepare_query_ml('fs_husbandry_animal', $data);
					execute_query($query);
					just_print("Data loaded to fs_husbandry_animal (" . (string)array_key_last($data)  . " rows).");
					unset($data);
				}
			}

			if($module->attributes()->name == 'pallets') {
				//<module name="pallets" fillCapacity="800000.000000" palletFillDelta="-0.000000"/>
				$data = array();
				
				$data[1]['husbandry_id'] = $husbandry_id;
				$data[1]['fill_capacity'] = (string)$module->attributes()->fillCapacity;
				$data[1]['fill_delta'] = (string)$module->attributes()->palletFillDelta;
				
				if(!empty($data)) {
					$query = prepare_query_ml('fs_husbandry_pallet', $data);
					execute_query($query);
					just_print("Data loaded to fs_husbandry_pallet (" . (string)array_key_last($data)  . " rows).");
					unset($data);
				}
			}

			if($module->attributes()->name == 'foodSpillage') {
				//<module name="foodSpillage" fillCapacity="13.032518" cleanlinessFactor="1.000000" foodToDrop="5.612739"/>
				//<module name="foodSpillage" fillCapacity="768.934753" cleanlinessFactor="0.236214" foodToDrop="37.481037"/>
				$data = array();
				
				$data[1]['husbandry_id'] = $husbandry_id;
				$data[1]['fill_capacity'] = (string)$module->attributes()->fillCapacity;
				$data[1]['clean_factor'] = (string)$module->attributes()->cleanlinessFactor;
				$data[1]['food_to_drop'] = (string)$module->attributes()->foodToDrop;

				if(!empty($data)) {
					$query = prepare_query_ml('fs_husbandry_spillage', $data);
					execute_query($query);
					just_print("Data loaded to fs_husbandry_spillage (" . (string)array_key_last($data)  . " rows).");
					unset($data);
				}
			}

			if($module->attributes()->name == 'food') {
				/*
				<module name="food" fillCapacity="0.000000" seasons_grazingConsumed="0.000000">
	            	<fillLevel fillType="HAYPELLETS" fillLevel="5810.201172"/>
	            	<fillLevel fillType="FORAGE" fillLevel="456.991943"/>
	            	<fillLevel fillType="GRASS_WINDROW" fillLevel="30048.962891"/>
	            	<fillLevel fillType="DRYGRASS_WINDROW" fillLevel="0.000000"/>
	        	    <fillLevel fillType="SILAGE" fillLevel="3799.492432"/>
			    </module>
				*/
			    /*
			    <module name="food" fillCapacity="0.000000" seasons_grazingConsumed="0.000000">
	            	<fillLevel fillType="WHEAT" fillLevel="22.442196"/>
	            	<fillLevel fillType="BARLEY" fillLevel="0.000000"/>
	            	<fillLevel fillType="CANOLA" fillLevel="0.000000"/>
	            	<fillLevel fillType="SUNFLOWER" fillLevel="0.000000"/>
	            	<fillLevel fillType="SOYBEAN" fillLevel="22.442196"/>
	            	<fillLevel fillType="MAIZE" fillLevel="44.884392"/>
	        	</module>
			     */
				$data = array();
				$row = 1;

				foreach ($module->children() as $fill_level) {
					$data[$row]['husbandry_id'] = $husbandry_id;
					$data[$row]['fill_capacity'] = (string)$module->attributes()->fillCapacity;
					$data[$row]['seasons_grazing'] = (string)$module->attributes()->seasons_grazingConsumed;
					$data[$row]['fill_type'] = (string)$fill_level->attributes()->fillType;
					$data[$row]['fill_level'] = (string)$fill_level->attributes()->fillLevel;
					++$row;
				}

				if(!empty($data)) {
					$query = prepare_query_ml('fs_husbandry_food', $data);
					execute_query($query);
					just_print("Data loaded to fs_husbandry_food (" . (string)array_key_last($data)  . " rows).");
					unset($data);
				}			
			}
			
			if($module->attributes()->name == 'straw') {
				/*
				<module name="straw" fillCapacity="7500.000000">
	            	<fillLevel fillType="STRAW" fillLevel="7500.000000"/>
	            	<fillLevel fillType="STRAWPELLETS" fillLevel="7500.000000"/>
	        	</module>
				*/
				$data = array();
				$row = 1;

				foreach ($module->children() as $fill_level) {
					$data[$row]['husbandry_id'] = $husbandry_id;
					$data[$row]['fill_capacity'] = (string)$module->attributes()->fillCapacity;
					$data[$row]['fill_type'] = (string)$fill_level->attributes()->fillType;
					$data[$row]['fill_level'] = (string)$fill_level->attributes()->fillLevel;
					++$row;
				}

				if(!empty($data)) {
					$query = prepare_query_ml('fs_husbandry_straw', $data);
					execute_query($query);
					just_print("Data loaded to fs_husbandry_straw (" . (string)array_key_last($data)  . " rows).");
					unset($data);		
				}	
			}
	        
	        if($module->attributes()->name == 'milk') {
				/*
		        <module name="milk" fillCapacity="800000.000000">
	            	<fillLevel fillType="MILK" fillLevel="0.000000"/>
	        	</module>			
				*/      
				$data = array();
				$row = 1;

				foreach ($module->children() as $fill_level) {
					$data[$row]['husbandry_id'] = $husbandry_id;
					$data[$row]['fill_capacity'] = (string)$module->attributes()->fillCapacity;
					$data[$row]['fill_type'] = (string)$fill_level->attributes()->fillType;
					$data[$row]['fill_level'] = (string)$fill_level->attributes()->fillLevel;
					++$row;
				}

				if(!empty($data)) {
					$query = prepare_query_ml('fs_husbandry_milk', $data);
					execute_query($query);
					just_print("Data loaded to fs_husbandry_milk (" . (string)array_key_last($data)  . " rows).");
					unset($data);		
				}				  	
	        }

	        if($module->attributes()->name == 'manure') {
	        	// <module name="manure" fillCapacity="800000.000000" manureToDrop="158.341248" manureToRemove="0.000000"/>
				$data = array();
				
				$data[1]['husbandry_id'] = $husbandry_id;
				$data[1]['fill_type'] = strtoupper($module->attributes()->name);
				$data[1]['fill_capacity'] = (string)$module->attributes()->fillCapacity;
				$data[1]['manure_to_drop'] = (string)$module->attributes()->manureToDrop;
				$data[1]['manure_to_remove'] = (string)$module->attributes()->manureToRemove;

				if(!empty($data)) {
					$query = prepare_query_ml('fs_husbandry_manure', $data);
					execute_query($query);
					just_print("Data loaded to fs_husbandry_manure (" . (string)array_key_last($data)  . " rows).");
					unset($data);      
				}  	        	
	        }

	        if($module->attributes()->name == 'liquidManure') {
	        	/*
		        <module name="liquidManure" fillCapacity="800000.000000">
	    	        <fillLevel fillType="LIQUIDMANURE" fillLevel="0.000000"/>
	        	</module>        
	        	*/
				$data = array();
				$row = 1;

				foreach ($module->children() as $fill_level) {
					$data[$row]['husbandry_id'] = $husbandry_id;
					$data[$row]['fill_capacity'] = (string)$module->attributes()->fillCapacity;
					$data[$row]['fill_type'] = (string)$fill_level->attributes()->fillType;
					$data[$row]['fill_level'] = (string)$fill_level->attributes()->fillLevel;
					++$row;
				}

				if(!empty($data)) {
					$query = prepare_query_ml('fs_husbandry_lmanure', $data);
					execute_query($query);
					just_print("Data loaded to fs_husbandry_lmanure (" . (string)array_key_last($data)  . " rows).");
					unset($data);		
				}	        
	        }

	        if($module->attributes()->name == 'water') {
	        	/*
	        	<module name="water" fillCapacity="46173.214844">
	            	<fillLevel fillType="WATER" fillLevel="3515.359131"/>
	        	</module>        	
	        	 */
				$data = array();
				$row = 1;

				foreach ($module->children() as $fill_level) {
					$data[$row]['husbandry_id'] = $husbandry_id;
					$data[$row]['fill_capacity'] = (string)$module->attributes()->fillCapacity;
					$data[$row]['fill_type'] = (string)$fill_level->attributes()->fillType;
					$data[$row]['fill_level'] = (string)$fill_level->attributes()->fillLevel;
					++$row;
				}

				if(!empty($data)) {
					$query = prepare_query_ml('fs_husbandry_water', $data);
					execute_query($query);
					just_print("Data loaded to fs_husbandry_water (" . (string)array_key_last($data)  . " rows).");
					unset($data);	
				}		        	
	        }
		}
	}
}
unset($animal_husbandries);

//--- process selling station ---------------------------------------------------------------------------------------------------------------------------
if(!empty($sell_stations)) {
	$data = array();
	$row = 1;
	foreach($sell_stations as $item) {
		/*
		<item className="SellingStationPlaceable" id="151" modName="FS19_SlovakVillage" filename="$moddir$FS19_SlovakVillage/placeables/sellingStations/sellingStationBank.xml" position="-366.9000 82.0000 877.5000" rotation="0.0000 0.0000 0.0000" age="0" price="1.000000" farmId="0" mapBoundId="sellingStationBank" boughtWithFarmland="false" seasonsYears="0.000000">
	        <sellingStation>
	            <stats fillType="GOLD" received="0.000000" paid="0.000000" priceVersion="1" isInPlateau="true" nextPlateauNumber="0" plateauDuration="51840000" meanValue="0.000000" plateauTime="29173030.000000">
	                <curveBaseCurve nominalAmplitude="12.000000" nominalAmplitudeVariation="9.000000" amplitudeDistribution="2" nominalPeriod="172800000" nominalPeriodVariation="64800000" periodDistribution="1" amplitude="4.409726" period="73636448.000000" time="18409112.000000"/>
	                <curve1 nominalAmplitude="6.000000" nominalAmplitudeVariation="1.200000" amplitudeDistribution="1" nominalPeriod="604800000" nominalPeriodVariation="120960000" periodDistribution="1" amplitude="5.704686" period="542764992.000000" time="34183896.000000"/>
	            </stats>
	        </sellingStation>
	    </item>
	    */   	
	    
	   	unset($mapping);
	   	// mapping for fs_sellstation
	   	$mapping = array(
	   		"id" => "game_id",
	   		"modName" => "mod_name",
	   		"filename" => "filename",
	   		"position" => "position",
	   		"age" => "age",
	   		"price" => "price",
			"mapBoundId" => "map_bound_id",
	   		"boughtWithFarmland" => "bought",  // original boughtWithFarmland
	   		"seasonsYears" => "seasons_years"
	   	);

	   	$data[$row]['farm_id'] = find_farm_id((int)$item->attributes()->farmId,$farm_map);
	   	$data[$row]['save_id'] = $save_id;
	   	foreach ($item->attributes() as $key => $value) {
	   		if(array_key_exists($key,$mapping)) {
	   			$data[$row][$mapping[$key]] = (string)$value;
	   		}
	   	}
	   	++$row;
	}

	$query = prepare_query_ml('fs_sellst', $data);
	execute_query($query);
	just_print("Data loaded to fs_sellst (" . (string)array_key_last($data)  . " rows).");
	unset($data);

	//--- process selling station products ---------------------------------------------------------------------------------------------------------------------------
	// wait for data to be commit
	sleep(3);
	$sell_st_map = get_maping($save_id, 'fs_sellst');

	$data = array();
	$row = 1;
	foreach($sell_stations as $item) {
	   	
	   	unset($mapping);
	   	// mapping for fs_sellstation_product
	   	$mapping = array(
	   		"fillType" => "fill_type",
	   		"received" => "received",
	   		"paid" => "paid",
	   		"priceVersion" => "price_version",
	   		"isInPlateau" => "plateau",
	   		"nextPlateauNumber" => "next_plateau_number",
	   		"plateauDuration" => "plateau_duration",
	   		"meanValue" => "mean_value",
	   		"plateauTime" => "plateau_time"
	   	);

		if($item->sellingStation->stats) {
			foreach($item->sellingStation->children() as $product) {
				$data[$row]['sell_st_id'] = $sell_st_map[(int)$item->attributes()->id];
				foreach ($product->attributes() as $key => $value) {
	   				if(array_key_exists($key,$mapping)) {
	   					$data[$row][$mapping[$key]] = (string)$value;
	   				}
	   			}
	   			++$row;
			}		
		}
	}

	$query = prepare_query_ml('fs_sellst_product', $data);
	execute_query($query);
	just_print("Data loaded to fs_sellst_product (" . (string)array_key_last($data)  . " rows).");
	unset($data);
	unset($sell_st_map);

	//--- process selling station product curvers ---------------------------------------------------------------------------------------------------------------------------

	$product_map = get_product_map($save_id);

	$data = array();
	$row = 1;
	foreach($sell_stations as $item) {
		$sel_st_game_id = (int)$item->attributes()->id;
	   	unset($mapping);
	   	// mapping for fs_sellst_product_curves
	   	$mapping = array(
	   		"nominalAmplitude" => "nominal_amplitude",
	   		"nominalAmplitudeVariation" => "nominal_amplitude_variation",
	   		"amplitudeDistribution" => "amplitude_distribution",
	   		"nominalPeriod" => "nominal_period",
	   		"nominalPeriodVariation" => "nominal_period_variation",
	   		"periodDistribution" => "period_distribution",
	   		"amplitude" => "amplitude",
	   		"period" => "period",
	   		"time" =>	"time"
	   	);

		if($item->sellingStation->stats) {		
			foreach ($item->sellingStation->stats as $stats) {
				$product = (string)$stats->attributes()->fillType;
				foreach($stats->children() as $curve) {			
					$data[$row]['product_id'] = $product_map[$sel_st_game_id][$product];
					$data[$row]['type'] = $curve->getName();
					foreach ($curve->attributes() as $key => $value) {
		   				if(array_key_exists($key,$mapping)) {
		   					$data[$row][$mapping[$key]] = (string)$value;
		   				}
		   			}
		   			++$row;
				}
			}		
		}
	}

	$query = prepare_query_ml('fs_sellst_product_curve', $data);
	execute_query($query);
	just_print("Data loaded to fs_sellst_product_curve (" . (string)array_key_last($data)  . " rows).");
	unset($data);
	unset($product_map);
}
unset($sell_stations);

//--- process silos and sios products ---------------------------------------------------------------------------------------------------------------------------
if(!empty($silos)) {
	unset($mapping);
	$mapping = array(
		"className" => "class_name",
		"id" => "game_id",
		"modName" => "mod_name",
		"filename" => "filename",
		"position" => "position",
		"age" => "age",
		"price" => "price",
		"boughtWithFarmland" => "bought",
		"seasonsYears" => "seasons_years"
	);

	$data = array();
	$row = 1;	
	foreach ($silos as $silo) {
		/*
		<item className="SiloPlaceable" id="176" modName="FS19_SlovakVillage" filename="$moddir$FS19_SlovakVillage/placeables/universalSilo/universalSilo.xml" position="248.0000 83.9500 -73.0000" rotation="0.0000 -90.0000 0.0000" age="40" price="250000.000000" farmId="1" boughtWithFarmland="false" seasonsYears="0.833324">
	        <storage index="1" farmId="1">
	            <node fillType="WHEAT" fillLevel="116458.687500"/>
	            <node fillType="OAT" fillLevel="18762.419922"/>
	            <node fillType="CANOLA" fillLevel="124981.179688"/>
	            <node fillType="SUNFLOWER" fillLevel="143386.406250"/>
	            <node fillType="SOYBEAN" fillLevel="81580.234375"/>
	            <node fillType="MAIZE" fillLevel="200224.078125"/>
	        </storage>
	    </item>
	    */
		
		$data[$row]['farm_id'] = find_farm_id((int)$silo->attributes()->farmId,$farm_map);
		$data[$row]['save_id'] = $save_id;
		$data[$row]['name'] = cut_string((string)$silo->attributes()->filename,'/','.');
	   	foreach ($silo->attributes() as $key => $value) {
	   		if(array_key_exists($key,$mapping)) {
	   			$data[$row][$mapping[$key]] = (string)$value;
	   		}
	   	}
	   	++$row;
	}

	$query = prepare_query_ml('fs_silo',$data);
	execute_query($query);
	just_print("Data loaded to fs_silo (" . (string)array_key_last($data)  . " rows).");
	unset($data);

	sleep(3);
	$silo_map = get_maping($save_id, 'fs_silo');

	$data = array();
	$row = 1;	
	foreach ($silos as $silo) {
		/*
		<item className="SiloPlaceable" id="176" modName="FS19_SlovakVillage" filename="$moddir$FS19_SlovakVillage/placeables/universalSilo/universalSilo.xml" position="248.0000 83.9500 -73.0000" rotation="0.0000 -90.0000 0.0000" age="40" price="250000.000000" farmId="1" boughtWithFarmland="false" seasonsYears="0.833324">
	        <storage index="1" farmId="1">
	            <node fillType="WHEAT" fillLevel="116458.687500"/>
	            <node fillType="OAT" fillLevel="18762.419922"/>
	            <node fillType="CANOLA" fillLevel="124981.179688"/>
	            <node fillType="SUNFLOWER" fillLevel="143386.406250"/>
	            <node fillType="SOYBEAN" fillLevel="81580.234375"/>
	            <node fillType="MAIZE" fillLevel="200224.078125"/>
	        </storage>
	    </item>
	    */
		
		foreach ($silo->storage->children() as $node) {
			$data[$row]['silo_id'] = $silo_map[(int)$silo->attributes()->id];
		   	$data[$row]['fill_type'] = (string)$node->attributes()->fillType;
		   	$data[$row]['fill_level'] = (string)$node->attributes()->fillLevel;
		   	++$row;
		}
	}

	if(!empty($data)) {
		$query = prepare_query_ml('fs_silo_product',$data);
		execute_query($query);
		just_print("Data loaded to fs_silo_product (" . (string)array_key_last($data)  . " rows).");
	}
	unset($data);
	unset($silo_map);
}
unset($silos);

//--- process not forestry trees ---------------------------------------------------------------------------------------------------------------------------
if(!empty($trees)) {
	unset($mapping);
	$mapping = array(
		"className" => "class_name",
		"id" => "game_id",
		"modName" => "mod_name",
		"filename" => "filename",
		"position" => "position",
		"age" => "age",
		"price" => "price",
		"boughtWithFarmland" => "bought",
		"seasonsYears" => "seasons_years"
	);

	$data = array();
	$row = 1;
	foreach($trees as $tree) {
		$data[$row]['farm_id'] = find_farm_id((int)$tree->attributes()->farmId,$farm_map);
		foreach($tree->attributes() as $key => $value) {
			if(array_key_exists($key,$mapping)) {
				if($key == 'filename') {
					// $moddir$FS19_decorativeTrees/pine/pineNC3.xml >> pine
					// $moddir$FS19_LapachoTree/lapachoTree.xml				
					$data[$row]['name'] = (string)substr($value,strpos($value,'/')+1,strpos($value,'/',strpos($value,'/')+1)-strpos($value,'/')-1) == '' ? strtoupper(str_replace('_','',convert_string((string)cut_string($value, '/', '.')))) : strtoupper((string)substr($value,strpos($value,'/')+1,strpos($value,'/',strpos($value,'/')+1)-strpos($value,'/')-1));	
				}
				$data[$row][$mapping[$key]] = (string)$value;
			}		
		}
		++$row;
	}

	$query = prepare_query_ml('fs_farm_tree',$data);
	execute_query($query);
	just_print("Data loaded to fs_farm_tree (" . (string)array_key_last($data)  . " rows).");
	unset($data);
}
unset($trees);

//--- process bunker silos ---------------------------------------------------------------------------------------------------------------------------
/*
    <item className="BunkerSiloPlaceable" id="187" modName="FS19_SlovakVillage" filename="$moddir$FS19_SlovakVillage/placeables/bunkerSilo/bunkerSiloLarge.xml" position="133.0000 86.9900 -165.0000" rotation="0.0000 90.0000 0.0000" age="40" price="45000.000000" farmId="1" boughtWithFarmland="false" seasonsYears="0.833324">
        <bunkerSilo index="1" state="2" fillLevel="2134968.250000" compactedFillLevel="2134968.250000" fermentingTime="345600000.000000" openedAtFront="false" openedAtBack="false"/>
    </item>

 */
if(!empty($bunker_silos)) {
	unset($mapping);
	// mapping for fs_bsilo
	$mapping = array(
		"className" => "class_name",
		"id" => "game_id",
		"modName" => "mod_name",
		"filename" => "filename",
		"position" => "position",
		"age" => "age",
		"price" => "price",
		"boughtWithFarmland" => "bought",
		"seasonsYears" => "seasons_years"
	);

	$data = array();
	$row = 1;
	foreach($bunker_silos as $silo) {
		$data[$row]['save_id'] = $save_id;
		$data[$row]['farm_id'] = find_farm_id((int)$silo->attributes()->farmId,$farm_map);
		foreach($silo->attributes() as $key => $value) {
			if(array_key_exists($key,$mapping)) {
				if($key == 'filename') {
					// $moddir$FS19_SlovakVillage/placeables/bunkerSilo/bunkerSiloLarge.xml >> bunkerSiloLarge
					$data[$row]['name'] = (string)substr($value,strpos($value,'/')+1,strpos($value,'/',strpos($value,'/')+1)-strpos($value,'/')-1) == '' ? strtoupper(str_replace('_','',convert_string((string)cut_string($value, '/', '.')))) : strtoupper((string)substr($value,strpos($value,'/')+1,strpos($value,'/',strpos($value,'/')+1)-strpos($value,'/')-1));	
				}
				$data[$row][$mapping[$key]] = (string)$value;
			}		
		}
		++$row;
	}

	$query = prepare_query_ml('fs_bsilo',$data);
	execute_query($query);
	just_print("Data loaded to fs_bsilo (" . (string)array_key_last($data)  . " rows).");
	unset($data);

	// bunker_silo mapping
	sleep(3); 
	$bsilo_map = get_maping($save_id, 'fs_bsilo');

	unset($mapping);
	// mapping for fs_bsilo_bunker
	$mapping = array(
		"index" => "index_id",
		"state" => "state",
		"fillLevel" => "fill_level",
		"compactedFillLevel" => "compacted_level",
		"fermentingTime" => "fermenting_time",
		"openedAtFront" => "opened_front",
		"openedAtBack" => "opened_back"
	);

	$data = array();
	$row = 1;
	foreach($bunker_silos as $silo) {
		foreach($silo->children() as $bunker) {
			$data[$row]['bsilo_id']	= $bsilo_map[(int)$silo->attributes()->id];
			foreach($bunker->attributes() as $key => $value) {
				if(array_key_exists($key,$mapping)) {
					$data[$row][$mapping[$key]] = (string)$value;
				}		
			}
			++$row;
		}
	}
	
	if(!empty($data)) {	
		$query = prepare_query_ml('fs_bsilo_bunker',$data);
		execute_query($query);
		just_print("Data loaded to fs_bsilo_bunker (" . (string)array_key_last($data)  . " rows).");
	}
	unset($data);
	unset($bsilo_map);
}
unset($bunker_silos);

//--- process BGA stations ---------------------------------------------------------------------------------------------------------------------------
/*
    <item className="BgaPlaceable" id="54" modName="FS19_SlovakVillage" filename="$moddir$FS19_SlovakVillage/placeables/bga/bga.xml" position="878.0000 81.4000 840.0000" rotation="0.0000 0.0000 0.0000" age="0" price="1.000000" farmId="15" mapBoundId="bga" boughtWithFarmland="true" seasonsYears="0.000000">
        <bga money="0.000000">
            <digestateSilo>
                <storage index="1" farmId="15"/>
            </digestateSilo>
            <slot index="1">
                <fillType fillType="GRASS_WINDROW" fillLevel="0.000000"/>
                <fillType fillType="MANURE" fillLevel="0.000000"/>
                <fillType fillType="DRYGRASS_WINDROW" fillLevel="0.000000"/>
                <fillType fillType="SILAGE" fillLevel="0.000000"/>
            </slot>
            <slot index="2">
                <fillType fillType="LIQUIDMANURE" fillLevel="0.000000"/>
            </slot>
        </bga>
        <bunkerSilo index="1" state="0" fillLevel="0.000000" compactedFillLevel="0.000000" fermentingTime="0.000000" openedAtFront="false" openedAtBack="false"/>
        <bunkerSilo index="2" state="0" fillLevel="0.000000" compactedFillLevel="0.000000" fermentingTime="0.000000" openedAtFront="false" openedAtBack="false"/>
    </item>
*/
if(!empty($bgas)) {
	unset($mapping);
	// mapping for fs_bsilo_bunker
	$mapping = array(
		"className" => "class_name",
		"id" => "game_id",
		"modName" => "mod_name",
		"filename" => "filename",
		"position" => "position",
		"age" => "age",
		"price" => "price",
		"mapBoundId" => "map_bound_id",
		"boughtWithFarmland" => "bought",
		"seasonsYears" => "seasons_years"
	);

	$data = array();
	$row = 1;
	foreach($bgas as $bga) {
		$data[$row]['save_id'] = $save_id;
		$data[$row]['farm_id'] = find_farm_id((int)$bga->attributes()->farmId,$farm_map);	
		$data[$row]['money'] = (string)$bga->bga->attributes()->money;
		foreach($bga->attributes() as $key => $value) {		
			if($key == 'filename') {
				$data[$row]['name'] = cut_string((string)$value, '/', '.');
			}
			if(array_key_exists($key,$mapping)) {
				$data[$row][$mapping[$key]] = (string)$value;
			}		
		}
		++$row;
	}

	$query = prepare_query_ml('fs_bga',$data);
	execute_query($query);
	just_print("Data loaded to fs_bga (" . (string)array_key_last($data)  . " rows).");
	unset($data);

	// bga mapping
	sleep(3);
	$bga_map = get_maping($save_id, 'fs_bga');

	$data = array();
	$row = 1;
	foreach($bgas as $bga) {
		$bga_id = $bga_map[(int)$bga->attributes()->id];
		foreach($bga->bga->children() as $subtag) {
			if($subtag->getName() == 'slot') {
				if((int)$subtag->attributes()->index == 1) {
					foreach($subtag->children() as $fill) {
						$data[$row]['bga_id'] = $bga_id;
						$data[$row]['type'] = 'input';
						$data[$row]['fill_type'] = (string)$fill->attributes()->fillType;
						$data[$row]['fill_level'] = (string)$fill->attributes()->fillLevel;
						++$row;
					}				
				}

				if((int)$subtag->attributes()->index == 2) {
					foreach($subtag->children() as $fill) {
						$data[$row]['bga_id'] = $bga_id;
						$data[$row]['type'] = 'output';
						$data[$row]['fill_type'] = (string)$fill->attributes()->fillType;
						$data[$row]['fill_level'] = (string)$fill->attributes()->fillLevel;
						++$row;
					}
				}
			}		
		}
	}

	if(!empty($data)) {
		$query = prepare_query_ml('fs_bga_product',$data);
		execute_query($query);
		just_print("Data loaded to fs_bga_product (" . (string)array_key_last($data)  . " rows).");
	}
	unset($data);

	unset($mapping);
	$mapping = array(
		"index" => "index_id",
		"state" => "state",
		"fillLevel" => "fill_level",
		"compactedFillLevel" => "compacted_level",
		"fermentingTime" => "fermenting_time",
		"openedAtFront" => "opened_front",
		"openedAtBack" => "opened_back"
	);

	$data = array();
	$row = 1;
	foreach($bgas as $bga) {
		$bga_id = $bga_map[(int)$bga->attributes()->id];
		foreach($bga->children() as $subtag) {
			if($subtag->getName() == 'bunkerSilo') {
				$data[$row]['bga_id'] = $bga_map[(int)$bga->attributes()->id];
				foreach($subtag->attributes() as $key => $value) {		
					if(array_key_exists($key,$mapping)) {
						$data[$row][$mapping[$key]] = (string)$value;
					}		
				}
				++$row;
			}		
		}
	}
	if(!empty($data)) {
		$query = prepare_query_ml('fs_bga_bunker',$data);
		execute_query($query);
		just_print("Data loaded to fs_bga_bunker (" . (string)array_key_last($data)  . " rows).");
	}
	unset($data);
	unset($bga_map);
}
unset($bgas);


//--- process buying stations ---------------------------------------------------------------------------------------------------------------------------
if(!empty($buy_stations)) {
	unset($mapping);
	$mapping = array(
		"className" => "class_name",
		"id" => "game_id",
		"modName" => "mod_name",
		"filename" => "filename",
		"position" => "position",
		"age" => "age",
		"price" => "price",
		"boughtWithFarmland" => "bought",
		"seasonsYears" => "seasons_years",
		"mapBoundId" => "map_bound_id"
	);

	$data = array();
	$row = 1;
	foreach($buy_stations as $item) {
		// <item className="BuyingStationPlaceable" id="351" modName="FS19_SlovakVillage" filename="$moddir$FS19_SlovakVillage/placeables/refillTankChemicals/refillTankChemicals_default.xml" position="-115.9000 87.0000 914.5000" rotation="0.0000 180.0000 0.0000" age="0" price="1.000000" farmId="0" mapBoundId="refillTankChemicals_default1" boughtWithFarmland="false" seasonsYears="0.000000"/>
		$data[$row]['save_id'] = $save_id; 
		$data[$row]['farm_id'] = find_farm_id((int)$item->attributes()->farmId,$farm_map);
		foreach ($item->attributes() as $key => $value) {
			if(array_key_exists($key,$mapping)) {
				if($key == 'filename') {
					$data[$row]['name'] = cut_string((string)$value, '/', '.');
				}
				$data[$row][$mapping[$key]] = (string)$value;
			}
		}
		++$row;
	}

	$query = prepare_query_ml('fs_buyst',$data);
	execute_query($query);
	just_print("Data loaded to fs_buyst (" . (string)array_key_last($data)  . " rows).");
	unset($data);
}
unset($buy_stations);

//--- process rest of the items ---------------------------------------------------------------------------------------------------------------------------
if(!empty($items)) {
	unset($mapping);
	$mapping = array(
		"className" => "class_name",
		"id" => "game_id",
		"modName" => "mod_name",
		"filename" => "filename",
		"position" => "position",
		"age" => "age",
		"price" => "price",
		"boughtWithFarmland" => "bought",
		"seasonsYears" => "seasons_years",
		"mapBoundId" => "map_bound_id"
	);

	$data = array();
	$row = 1;
	foreach($items as $item) {	
		$data[$row]['save_id'] = $save_id;
		$data[$row]['farm_id'] = find_farm_id((int)$item->attributes()->farmId,$farm_map);
		foreach ($item->attributes() as $key => $value) {
			if(array_key_exists($key,$mapping)) {
				if($key == 'filename') {
					$data[$row]['name'] = cut_string((string)$value, '/', '.');
				}
				$data[$row][$mapping[$key]] = (string)$value;
			}
		}
		++$row;
	}

	$query = prepare_query_ml('fs_item',$data);
	execute_query($query);
	just_print("Data loaded to fs_item (" . (string)array_key_last($data)  . " rows).");
	unset($data);
}
unset($items);
?>