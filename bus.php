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

$filename = 'bus/latest.json';
$stations = ['302380','302415'];

$stopsAwayNextBus['B25'] = 1000;
$stopsAwayNextBus['B44'] = 1000;


foreach ($stations as $station) {
	$url = 'http://bustime.mta.info/api/siri/stop-monitoring.json?key=APIKEYAPIKEY&OperatorRef=MTA&MonitoringRef=' . $station;
	$rawData = json_decode(getResource($url), TRUE);

	$outerLoop = count($rawData['Siri']['ServiceDelivery']['StopMonitoringDelivery']);
	for ($i=0; $i < $outerLoop ; $i++) { 
		$innerLoop = count($rawData['Siri']['ServiceDelivery']['StopMonitoringDelivery'][$i]['MonitoredStopVisit']);
		for ($j=0; $j < $innerLoop; $j++) { 
			$bus = $rawData['Siri']['ServiceDelivery']['StopMonitoringDelivery'][$i]['MonitoredStopVisit'][$j]['MonitoredVehicleJourney']['PublishedLineName'];
			$presentableDist = $rawData['Siri']['ServiceDelivery']['StopMonitoringDelivery'][$i]['MonitoredStopVisit'][$j]['MonitoredVehicleJourney']['MonitoredCall']['Extensions']['Distances']['PresentableDistance'];
			$stopsAway = $rawData['Siri']['ServiceDelivery']['StopMonitoringDelivery'][$i]['MonitoredStopVisit'][$j]['MonitoredVehicleJourney']['MonitoredCall']['Extensions']['Distances']['StopsFromCall'];
		
			if ($bus == 'B25' or $bus == 'B44') {

				if (($stopsAwayNextBus[$bus] > intval($stopsAway)) and (intval($stopsAway) > 0)) {
					$stopsAwayNextBus[$bus] = intval($stopsAway);
				}
			}
		}
	}
}

$msg =  'Bus (Stops Away) B25: ' . $stopsAwayNextBus['B25'] . ' B44: ' . $stopsAwayNextBus['B44'] . '.';

echo $msg;
writeFile($filename, $msg);

 ?>

