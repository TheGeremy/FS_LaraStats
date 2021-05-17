<?php
function just_print($message, $output = 1, $restart = 0) {
	// file_put_contents ( string $filename , mixed $data , int $flags = 0 , resource $context = ? ) : int
	// If filename does not exist, the file is created. Otherwise, the existing file is overwritten, unless the FILE_APPEND flag is set.
	// This function returns the number of bytes that were written to the file, or false on failure.
	
	// $output >> 1 - web page, 2 - file
	// $restart >> 1 - create new file, 2 - append to existing lof file

	$message = $message . "\n";
	// log file
	$log_file = base_path() . "/logs/savegame_load.log";
	
	if($output == 1) {
		print_r($message);
	}

	if($output == 2 && $restart == 1) {
		// Write the contents back to the file
		file_put_contents($log_file, $message);
	}

	if($output == 2 && $restart == 0) {
		// add line to log
		file_put_contents($log_file, $message, FILE_APPEND | LOCK_EX);
	}
}
function print_heading($message, $output = 1) {
	$message_length = 100;
	$message = "--- " . $message . " " . strftime("%d.%m.%Y (%H:%M:%S)",time()) . " -----------------------------------------------------------------------------------------------------------------------------";
	if(strlen($message) > $message_length) {
		$message = substr($message,0,$message_length);
	}
	just_print($message, $output, 0);
}
function load_xml_file($path_to_file) {
	if(file_exists($path_to_file))  {
		$xml_file = simplexml_load_file($path_to_file);
		return $xml_file;
	} else {
		exit("Unable to load required file: $path_to_file");
	}
}
function execute_query($query) {
	DB::statement($query);
}
function convert_string($str) {
	// function to convert string from - to:
	// "Some String" - "some_string"
	// "SomeString" - "some_string"
	// "someString" - "some_string"
	// "   some    String    " - "some_string"
	
	// split string to characters array
	// replace all upper case characters with _ + lower case character
	foreach(str_split($str) as $char) {
	   	if (ctype_upper($char)) {
       		// if char is upper case
       		$str = str_replace($char,'_' . strtolower($char), $str);
    	}
    }

    // replace more spaces with just one underscore
    $str = preg_replace('!\s+!', '_', $str);
    // replace more underscores with just one underscore
	$str = preg_replace('!_+!', '_', $str);

	// if first character is underscore, remove it
	// triple = = = because NULL when not find, zero when first character
    if(strpos($str,'_') === 0) {
	    $str = substr($str,1,strlen($str)-1);
    }
    
    // if last character is underscore, remove it
    if(substr($str,strlen($str)-1,1) == '_') {
        $str = substr($str,0,strlen($str)-1);
    }
    
    return $str;
}
function game_to_farm_id($game_id) {
	// change game farm_id stored as game_id in fs_farm to internal database farm_id which is id from fs_farm
	$query = "select game_to_farm_id('" . $game_id . "') as farm_id;";
	$result = DB::select($query);
	return $result[0]->farm_id; // database return 0 if no map find
}
function get_map_id($map_title) {
	// get id of map in map dimension table
	$query = "select get_map_id('" . $map_title . "') as map_id;";
	$result = DB::select($query);
	return $result[0]->map_id; // database return 0 if no map find
}
function get_save_id() {
	// get id of last savegame loaded 
	$query = "select get_save_id() as id;";
	$result = DB::select($query);
	return $result[0]->id; // database return 0 if no savegame
}
function get_farm_id() {
	// get id of last savegame loaded 
	$query = "select get_farm_id() as farm_id;";
	$result = DB::select($query);
	return $result[0]->farm_id; // database return 0 if no savegame
}
function get_current_day() {
	// get current day of last savegame loaded
	$query = "SELECT get_current_day() AS current_day;";
	$result = DB::select($query);
	return $result[0]->current_day; // if no save (current day) then database return 0
}
function check_seasons() {
	$query = "SELECT check_seasons() as seasons;";
	$result = DB::select($query);
	return $result[0]->seasons; // sql return true / false based on if last loaded savegame has seasons mod enabled
}
function find_last_char($str, $char) {
	$i = 0;
	$pos = -1;
	$arr = str_split($str);
	foreach ($arr as $str_char) {
		if ($str_char == $char) {
			if($pos < $i) {
				$pos = $i;
			}
		}
		++$i;
	}

	return $pos;
}
function cut_string($str,$start_ch,$end_ch) {
	$start_ch_pos = find_last_char($str, $start_ch);
	$str_start = $start_ch_pos + 1;
	$end_ch_pos = find_last_char($str, $end_ch);
	$str_end = $end_ch_pos - strlen($str);
	if($start_ch_pos < 0 or $end_ch_pos < 0) {
		return $str;
	} else {
		return substr($str,$str_start,$str_end);
	}
}
function cut_filename($str,$start_ch,$end_ch) {
	$start_ch_pos = find_last_char($str, $start_ch);
	$str_start = $start_ch_pos + 1;
	$end_ch_pos = strpos($str, $end_ch);
	$str_end = $end_ch_pos - strlen($str);
	if($start_ch_pos < 0 or $end_ch_pos < 0) {
		return $str;
	} else {
		return substr($str,$str_start,$str_end);
	}
}
function is_var_empty($var)
{
    if (empty($var) === true)
    {
        if (($var === 0) || ($var === '0'))
        {
            return false;
        }

        return true;
    }

    return false;
}
function prepare_query_ml($tableName, $data) {
	// prepare multiline query
	// data parameter is multidimensional array of associative arrays as rows 
	/*
	Array
	(
	    [1] => Array / Row
		        (
		            [saveId] => 7
		            [missionNum] => 1
		            [type] => cultivate
		            [reward] => 3611
		            [status] => 0
		            [success] => false
		            [fieldId] => 25
		            [vehicleUseCost] => 1422.739502
		            [fruitTypeName] => BARLEY
		        )

	    [2] => Array / Row
		        (
		            [saveId] => 7
		            [missionNum] => 2
		            [type] => plow
		            [reward] => 729
		            [status] => 0
		            [success] => false
		            [fieldId] => 19
		            [vehicleUseCost] => 86.794998
		            [fruitTypeName] => GRASS
		        )
	)
	 */
	
	$query = "insert into " . $tableName . "\n(";
	$columns = array(); // ensure same order of columns and values

	// add columns list to query
	foreach($data as $row) {
		foreach(array_keys($row) as $_ => $column) {
			if(!in_array($column, $columns)) {
				$query .= "`" . $column. "`, ";
				array_push($columns,$column);
			}
		}
	}		

	$query = substr($query,0,-2) . ")\nvalues\n";

	// add values to query
	foreach($data as $row) {
		$query .= "(";
	  	foreach($columns as $column) {	  		
	  		$value = (array_key_exists($column,$row)) ? $row[$column] : 'NULL';
	  		$value = ($value == NULL) ? 'NULL' : $value;
	  		if(is_numeric($value) or $value == 'true' or $value == 'false' or $value == 'NULL') {
				$query .= $value . ", ";
			} else {
				$query .= "'" . str_replace("'","\'",$value) . "', ";
			}	  		
	  	}
	  	$query = substr($query,0,-2) . "),\n";
	}

	return substr($query,0,-2) . ";";
}
function prepare_update_query($table_name, $data, $key_column, $key_value) {
	$columns_list = array(); // ensure same order of columns and values
	$query = "update " . $table_name .  " set\n";

	// add values to update
	foreach($data as $column => $value) {
		if(!(is_numeric($value) or $value == 'true' or $value == 'false' or $value == 'NULL')) {
			$value = "'" . $value . "'";
	  	}
	  	$query .= "`" . $column . "` = ". $value .  ",\n";	  	
	}

	if(!(is_numeric($key_value) or $key_value == 'true' or $key_value == 'false' or $key_value == 'NULL')) {
			$key_value = "'" . $key_value . "'";
	}

	$query = substr($query,0,-2) . "\nwhere `" . $key_column . "` = " . $key_value . ";";	
	return $query;
}
?>