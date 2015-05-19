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

$filename = 'metro/latest.json';
$url = 'https://maps.googleapis.com/maps/api/directions/json?origin=12%20Macon%20St.%20Brooklyn,%20NY,%2011216&destination=100%20Avenue%20of%20the%20americas&transit_mode=subway&key=APIKEYAPIKEY&mode=transit';
$rawData = json_decode(getResource($url), TRUE);

$steps = $rawData['routes'][0]['legs'][0]['steps'];
foreach ($steps as $step) {
	if ($step['travel_mode'] == 'TRANSIT') {
		$train = $step['transit_details']['line']['short_name'];
		$time = $step['transit_details']['departure_time']['text'];
	}
}


$msg = 'Subway: ' . $train . ' train departs ' . $time . '.';
echo $msg;
writeFile($filename, $msg);

 ?>

