<?php

/* 
eve/temperature
Description: grab temperature data from a data corpus.
Usage: upload to server, send sentence to ?q=queryhere
Temperature expressed in degrees Celcius, Fahrenheit, or no explicit unit.
Set the living room to 68°F => {"temperature": 68, "unit": "F"}
Set the temperature to 22 => {"temperature": 22, "unit": "degrees"}
*/

$query = $_REQUEST['q'];
$result = array();
$output_array = array();
preg_match_all("/^(.*)(?:(?<=\b0x)|\b)\d+\b(.*)/i", $query, $output);
$output_array[] = $output;
$i = 0;
while(preg_match("/^(.*)(?:(?<=\b0x)|\b)\d+\b(.*)/i", $output_array[$i][1][0])){
	preg_match_all("/^(.*)(?:(?<=\b0x)|\b)\d+\b(.*)/i", $output_array[$i][1][0], $output);
	$output_array[] = $output;
	$i++;
}
foreach($output_array as $output){
	if(preg_match("/^(.*)(F|farenheight|celcius|c|degrees|degrees (c|f|celsius|farenheight))(.*)/i", $output[2][0]))
		preg_match_all("/^(.*)(F|farenheight|celcius|c|degrees|degrees (c|f|celsius|farenheight))(.*)/i", $output[2][0], $scale);
	else
		preg_match_all("/^(.*)(F|farenheight|celcius|c|degrees|degrees (c|f|celsius|farenheight))(.*)/i", $output[1][0], $scale);
	preg_match_all("/^".$output[1][0]."(.*)".$output[2][0]."/i", $output[0][0], $output2);
	$result[] = array("temperature" => $output2[1][0], "unit" => strtolower($scale[2][0]));
}
echo json_encode($result);
