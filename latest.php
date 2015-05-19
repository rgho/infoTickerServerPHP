<?php

// ERROR REPORTING ON

error_reporting(E_ALL);
ini_set('display_errors', '1');

function getFile($filename){
	return file_get_contents($filename);
}

function latestMessage(){
	$dataFile = 'latest.json';

	// Get each of the latest data file contents.	
	$citiBikeData = getFile('citibike/' . $dataFile);
	$metroData = getFile('metro/' . $dataFile);
	$busData = getFile('bus/' . $dataFile);
	$weatherData = getFile('weather/' . $dataFile);
	$bitcoinData = getFile('bitcoin/' . $dataFile);

	// Get other data like current time and day

	$now = new DateTime('America/New_York');
	$date = $now->format('l, M d Y H:i:s');


	$msg = $date . '. ' . $weatherData .' '. $metroData .' '. $busData .' BTC: $' . $bitcoinData . '. Citibikes: ' . $citiBikeData . '.';
	return $msg;
}



//header("Content-type:application/json");
echo latestMessage();


?>