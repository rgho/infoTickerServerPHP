
<?php 
// ERROR REPORTING ON

error_reporting(E_ALL);
ini_set('display_errors', '1');



function getResource($url){
	$ch = curl_init();
	// SET CURL OPTIONS
	$timeout = 5;
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


$latlong = '40.7127,-74.0059'; #Hard code new york. 
$APIkey = ‘APIKEYAPIKEY;
$url = 'https://api.forecast.io/forecast/' . $APIkey . '/' . $latlong;

$filename = 'weather/latest.json';

$rawData = json_decode(getResource($url), TRUE);
$summary = $rawData['hourly']['summary'];

$temp = intval($rawData['hourly']['data'][0]['temperature']);
$rainProb = intval($rawData['hourly']['data'][0]['precipProbability'] * 100);
$windSpeed = intval($rawData['hourly']['data'][0]['windSpeed']);

$message = $summary . ' ' . $temp . 'F, Rain: ' . $rainProb . '%, Wind: ' . $windSpeed . 'mph. ';
echo $message;
writeFile($filename, $message);

 ?>

