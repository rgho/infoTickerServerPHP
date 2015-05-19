

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



$data = json_decode(getResource('http://api.coindesk.com/v1/bpi/currentprice.json'),'TRUE');
$rawData = intval($data['bpi']['USD']['rate']);

$filename = 'bitcoin/latest.json';

echo $rawData;
writeFile($filename, $rawData);

 ?>

