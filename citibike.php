<?php 

// ERROR REPORTING ON
error_reporting(E_ALL);
ini_set('display_errors', '1');

function getResource($url){
	$ch = curl_init();
	// SET CURL OPTIONS
	$timeout = 30;
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	// SIMPLE HACK FOR HTTPS SUPPORT, IDEALLY, WOULD POINT CURL TO A *PEM FILE WITH CERTS.
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}

function writeFile($filename,$data) {
	file_put_contents($filename, $data);
}

$stationID = ‘STATIONIDHERE;
$url = 'http://www.citibikenyc.com/stations/json';

$filename = 'citibike/latest.json';

$rawData = json_decode(getResource($url), TRUE);

foreach($rawData['stationBeanList'] as $station) {
    //echo $station['id'], '<br>';
    if ($station['id'] == $stationID) {
    	$available = $station['availableBikes'];
    }
}

echo $available;
writeFile($filename, $available);

 ?>


