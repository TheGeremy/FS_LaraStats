<?php

$xml_file = load_xml_file(base_path() . "/fs_config/savegame/seasons.xml");

/*
    <environment>
        <currentDayOffset>0</currentDayOffset>
        <daysPerSeason>12</daysPerSeason>
        <snow height="0.180000" mode="3"/>
    </environment>
    <weather timeSinceLastRain="2444">
        <soilTemp>0.623006</soilTemp>
        <soilTempMax>21.680159</soilTempMax>
        <highTempPrev>2.212587</highTempPrev>
        <cropMoistureContent>23.106888</cropMoistureContent>
        <soilWaterContent>0.462663</soilWaterContent>
        <lowAirTemp>-6.812395</lowAirTemp>
        <snowDepth>0.145002</snowDepth>
        <rotDryFactor>9.471240</rotDryFactor>
        <averageSoilWaterContent>0.213415</averageSoilWaterContent>
        <moistureEnabled>true</moistureEnabled>
    <vehicle>
        <snowTracksEnabled>true</snowTracksEnabled>
    </vehicle>
*/

$data = array();
$data[1]['save_id'] = $save_id;
$data[1]['day_offset'] = (string)$xml_file->environment->currentDayOffset;
$data[1]['days_per_season'] = (string)$xml_file->environment->daysPerSeason;
$data[1]['soil_temp'] = (string)$xml_file->weather->soilTemp;
$data[1]['soil_temp_max'] = (string)$xml_file->weather->soilTempMax;
$data[1]['high_temp_prev'] = (string)$xml_file->weather->highTempPrev;
$data[1]['crop_moisture'] = (string)$xml_file->weather->cropMoistureContent;
$data[1]['water_in_soil'] = (string)$xml_file->weather->soilWaterContent;
$data[1]['air_temp_low'] = (string)$xml_file->weather->lowAirTemp;
$data[1]['snow_height'] = (string)$xml_file->environment->snow->attributes()->height;
$data[1]['snow_depth'] = (string)$xml_file->weather->snowDepth;
$data[1]['rot_dry_factor'] = (string)$xml_file->weather->rotDryFactor;
$data[1]['avg_water_in_soil'] = (string)$xml_file->weather->averageSoilWaterContent;
$data[1]['moisture'] = (string)$xml_file->weather->moistureEnabled;
$data[1]['snow_tracks'] = (string)$xml_file->vehicle->snowTracksEnabled;

$query = prepare_query_ml('fs_seasons',$data);
// just_print($query);
execute_query($query);
just_print("Data loaded to fs_seasons (" . (string)array_key_last($data)  . " rows).");
unset($data);

$forecast = $xml_file->xpath('//seasons/weather/forecast/item');
$prices = $xml_file->xpath('//seasons/economy/history/fill');
unset($xml_file);
/*
        <forecast>
            <item day="41" season="3" averagePeriodTemp="-0.487419" p="0.574301" startTimeIndication="9.807017" windSpeed="1.433181" windType="0" forecastType="1" cloudCover="0.860766" lowTemp="-4.174486" highTemp="2.212587" tempUncertainty="-0.250920" windUncertainty="-0.055437" precipitationUncertainty="-0.135068" weatherTypeUncertainty="0.539829"/>
            <item day="42" season="3" averagePeriodTemp="-0.487419" p="0.037824" startTimeIndication="4.923806" windSpeed="10.164658" windType="2" forecastType="7" cloudCover="1.000000" lowTemp="-6.070395" highTemp="-2.416718" tempUncertainty="0.117749" windUncertainty="0.037282" precipitationUncertainty="-0.577550" weatherTypeUncertainty="0.117796"/>
            <item day="43" season="3" averagePeriodTemp="-0.487419" p="0.435139" startTimeIndication="9.595631" windSpeed="8.117679" windType="2" forecastType="7" cloudCover="1.000000" lowTemp="-5.756706" highTemp="-4.731585" tempUncertainty="0.003845" windUncertainty="0.005391" precipitationUncertainty="-0.260584" weatherTypeUncertainty="-0.177112"/>
            <item day="44" season="3" averagePeriodTemp="-0.487419" p="0.532757" startTimeIndication="10.960085" windSpeed="3.590235" windType="0" forecastType="1" cloudCover="0.967289" lowTemp="-6.463239" highTemp="1.382704" tempUncertainty="-0.077162" windUncertainty="-0.055405" precipitationUncertainty="-0.310185" weatherTypeUncertainty="-0.232302"/>
            <item day="45" season="3" averagePeriodTemp="4.031646" p="0.511898" startTimeIndication="11.650201" windSpeed="1.630668" windType="0" forecastType="1" cloudCover="0.854358" lowTemp="0.177088" highTemp="2.190889" tempUncertainty="-0.133261" windUncertainty="0.090406" precipitationUncertainty="-0.637758" weatherTypeUncertainty="-0.268597"/>
            <item day="46" season="3" averagePeriodTemp="4.031646" p="0.749168" startTimeIndication="19.206284" windSpeed="5.735129" windType="1" forecastType="1" cloudCover="0.296076" lowTemp="-2.781811" highTemp="5.198221" tempUncertainty="-0.251456" windUncertainty="-0.191361" precipitationUncertainty="-0.555753" weatherTypeUncertainty="0.036731"/>
            <item day="47" season="3" averagePeriodTemp="4.031646" p="0.190167" startTimeIndication="11.110949" windSpeed="10.306916" windType="2" forecastType="4" cloudCover="1.000000" lowTemp="0.921861" highTemp="2.810566" tempUncertainty="0.135765" windUncertainty="-0.086310" precipitationUncertainty="-0.143703" weatherTypeUncertainty="-0.161265"/>
            <item day="48" season="3" averagePeriodTemp="4.031646" p="0.908743" startTimeIndication="9.469773" windSpeed="13.143684" windType="2" forecastType="0" cloudCover="0.000000" lowTemp="-4.191818" highTemp="2.573804" tempUncertainty="0.232919" windUncertainty="-0.099770" precipitationUncertainty="0.434209" weatherTypeUncertainty="0.491272"/>
        </forecast>

*/

// mapping for fs_season_forecast
$mapping = array(
    "day" => "day",
    "season" => "season",
    "averagePeriodTemp" => "average_temp",
    "p" => "p",
    "startTimeIndication" => "start_time",
    "windSpeed" => "wind_speed",
    "windType" => "wind_type",
    "forecastType" => "type",
    "cloudCover" => "cloud_cover",
    "lowTemp" => "low_temp",
    "highTemp" => "high_temp",
    "tempUncertainty" => "temp_uncertainty",
    "windUncertainty" => "wind_uncertainty",
    "precipitationUncertainty" => "rain_uncertainty",
    "weatherTypeUncertainty" => "type_uncertainty"
);

// get database seasons id from fs_season table
$seasons_id = get_seasons_id($save_id);
just_print("Seasons id is $seasons_id");

$data = array();
$row = 1;
foreach ($forecast as $day) {
    $data[$row]['seasons_id'] = $seasons_id;
    foreach ($day->attributes() as $key => $value) {
        if(array_key_exists($key,$mapping)) {
            $data[$row][$mapping[$key]] = (string)$value;
        }
    }
    ++$row;
}

$query = prepare_query_ml('fs_seasons_forecast',$data);
// just_print($query);
execute_query($query);
just_print("Data loaded to fs_seasons_forecast (" . (string)array_key_last($data)  . " rows).");
unset($data);
unset($forecast);

/*
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
*/

/*
    <fill fillType="STRAWPELLETS">
        <values>450;445.49463615954;450.61028177913;446.4489273978;451.18802847308;452.55083680485;451.05904198666;443.24441480921;450.23096065261;449.15428394401;457.03163288659;440.40949220277;453.70987700763;455.16300977778;448.73390741294;452.92191670403;462.33686437988;448.74265497002;445.83249211192;461.56376205719;428.2472787404;449.85670161495;439.82628974231;444.79076388781;449.5809760919;451.3698717032;461.21829764526;465.65971476485;448.09454770801;466.90338978788;451.38089951956;452.05879249117;450.67143022612;450.95535035915;443.93420724633;439.63350624258;439.52574569572;447.00480777845;434.65482342355;467.86847780178;429.19587818012;450;450;450;450;450;450;450</values>
    </fill>
*/

// $query = prepare_query_ml('fs_savegame_forestry',$data);
// just_print($query);
// execute_query($query);
// just_print("Data loaded to fs_savegame_npc (" . (string)array_key_last($data)  . " rows).");
// unset($data);
// unset($mapping);
// unset($trees);
?>