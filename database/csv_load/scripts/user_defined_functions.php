<?php
function just_print($message) {
	print_r($message . "\n");
}
function print_heading($message) {
	$message_length = 100;
	$message = "--- " . $message . " " . strftime("%d.%m.%Y (%H:%M:%S)",time()) . " -----------------------------------------------------------------------------------------------------------------------------";
	if(strlen($message) > $message_length) {
		$message = substr($message,0,$message_length);
	}
	print_r($message . "\n");
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
function push_attributes($key, $value) {
	array_push($GLOBALS['columns'],$key);
	array_push($GLOBALS['values'],$value);
}
function get_map_id($map_title, $connection) {
	// get id of map in map dimension table
	$query = "select get_map_id('" . $map_title . "') as map_id;";
	$result = execute_query($connection, $query);
	$row = $result->fetch_array(MYSQLI_ASSOC);
	return $row["map_id"]; // database return 0 if no map find
}
function get_savegame_id($connection) {
	// get id of last savegame loaded 
	$query = "select get_save_id() as id;";
	$result = execute_query($connection, $query);
	$row = $result->fetch_array(MYSQLI_ASSOC);
	return $row["id"]; // database return 0 if no savegame
}
function get_current_day($connection) {
	$query = "SELECT get_current_day() AS currentDay;";
	$result = execute_query($connection, $query);
	$row = $result->fetch_array(MYSQLI_ASSOC);

	return $row["currentDay"]; // if no save (current day) then database return 0
}
function console_log($msg, $redirect = 3) {
    // redirect => if 1 print to console log of chrome, if 2 print to javascript log ???, 3 echo to web page
	if ($redirect == 1) {    
	    $js_code = 'console.log(' . json_encode($msg, JSON_HEX_TAG) . ');';
	    echo $js_code;
	} elseif ($redirect == 2) {
		$js_code = '<script>' . $js_code . '</script>';
		echo $js_code;
	} else {
		echo($msg ."\n");
	}
}
function print_before() {
	echo"<html><head><title>Load XML to DB</title></head><body style=\"color:#E7D1B0;background-color:#191919;\"><p><pre>";
}
function print_after() {
	echo "</pre></p></body></html>";
}
function mariadb_connect() {
	// redirect => if 1 print to console log of chrome, if 2 print to command line
	$redirect = 2;	
	// definde parameters
	$server = "nuba.synology.me";
	$database = "fs_lara_stats";
	$user = "fs19webstats";
	$pass = "159-Fs19-951";
	// connect to database
	$mysqli = new mysqli($server, $user, $pass, $database, 3307);
	if ($mysqli->connect_errno) {
   	console_log("Failed to connect to MySQL:\n(" . $mysqli->connect_errno . ") " . $mysqli->connect_error, $redirect);
   	return FALSE;
	} else {
		console_log("Connection to db opened.", 3);

		return $mysqli;
	}

}
function execute_query($connection, $query) {
	// console_log("==> Query: " . $query, 3);
	// execute query
	$result = $connection->query($query);
	if (!$result) {
   		console_log("!!! Query: error\n(" . $connection->errno . ") " . $connection->error, 3);
	} else {
		//console_log("*** Query: success", 3);
		return $result;
	}
}
function mariadb_disconnect($connection) {
	// close connection
	// $connection->close();
	mysqli_close($connection);

	console_log("Connection to db closed.", 3);
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
function check_seasons($connection) {
	$query = "SELECT `rowId` FROM `fs19_mod` WHERE `saveId` = (SELECT MAX(`id`) FROM `fs19_savegame`) AND LOWER(`title`) = 'seasons';";
	$result = execute_query($connection, $query);
	$row = $result->fetch_array(MYSQLI_ASSOC);
	if($row["rowId"] > 0) {
		return true;
	} else {
		return false;
	}
}
function prepare_query($tableName,$columns,$values) {
	$col_list = "";
	$val_list = "";
	// $columns is array of columns names
	// $values is array of values
	foreach($columns as $column) {
		$col_list .= "`" . $column . "`, ";	
	}

	foreach($values as $value) {
		$value = (is_var_empty($value)) ? 'NULL' : $value;
		if(is_numeric($value) or $value == 'true' or $value == 'false' or $value == 'NULL') {
			$val_list .= $value . ", ";
		} else {
			$val_list .= "'" . $value . "', ";
		}
	}


	$query = "insert into " . $tableName ."\n(" . substr($col_list,0,-2) . ")\nvalues\n(" . substr($val_list,0,-2) . ");";
	return $query;
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
	  		if(is_numeric($value) or $value == 'true' or $value == 'false' or $value == 'NULL') {
				$query .= $value . ", ";
			} else {
				$query .= "'" . $value . "', ";
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