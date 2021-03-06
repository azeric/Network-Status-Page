<?php

	include('weather.php');
	include('weather_forecast.php');

	$config_path = "/var/config.ini"; //path to config file, recommend you place it outside of web root
	// Set the path for the Plex Token
	$plexTokenCache = '/var/www/html/assets/misc/plex_token.txt';
	
	Ini_Set( 'display_errors', true);
	$config = parse_ini_file($config_path, true);
	
	$local_pfsense_ip = $config['network']['local_pfsense_ip'];
	$local_server_ip = $config['network']['local_server_ip'];
	$pfsense_if_name = $config['network']['pfsense_if_name'];
	$wan_domain = $config['network']['wan_domain'];
	$plex_server_ip = $config['network']['plex_server_ip'];
	$ssh_username = $config['credentials']['ssh_username'];
	$ssh_password = $config['credentials']['ssh_password'];
	$ssh_key = $config['credentials']['ssh_key'];
	$plex_username = $config['credentials']['plex_username'];
	$plex_password = $config['credentials']['plex_password'];
	//$forecast_api = $config['api_keys']['forecast_api'];
	$weather_lat = $config['misc']['weather_lat'];
	$weather_long = $config['misc']['weather_long'];
	$plex_port = $config['network']['plex_port'];
	$filesystems = $config['filesystems'];

// Check to see if the plex token exists and is younger than one week
// if not grab it and write it to our caches folder
if (file_exists($plexTokenCache) && (filemtime($plexTokenCache) > (time() - 60 * 60 * 24 * 7))) {
	$plexToken = file_get_contents($plexTokenCache);
} else {
	file_put_contents($plexTokenCache, getPlexToken());
	$plexToken = file_get_contents($plexTokenCache);
}
	

if (strpos(strtolower(PHP_OS), "Darwin") === false)
	$loads = sys_getloadavg();
else
	$loads = Array(0.55,0.7,1);

function getCpuUsage()
{
	$top = shell_exec('top -n 0');
	$findme = 'idle';
	$cpuIdleStart = strpos($top, $findme);
	$cpuIdle = substr($top, ($cpuIdleStart - 7), 2);
	$cpuUsage = 100 - $cpuIdle;
	return $cpuUsage;
}
function makeCpuBars()
{
	printBar(getCpuUsage(), "Usage");
}	

function byteFormat($bytes, $unit = "", $decimals = 2) {
	$units = array('B' => 0, 'KB' => 1, 'MB' => 2, 'GB' => 3, 'TB' => 4, 'PB' => 5, 'EB' => 6, 'ZB' => 7, 'YB' => 8);
 
	$value = 0;
	if ($bytes > 0) {
		// Generate automatic prefix by bytes 
		// If wrong prefix given
		if (!array_key_exists($unit, $units)) {
			$pow = floor(log($bytes)/log(1024));
			$unit = array_search($pow, $units);
		}
 
		// Calculate byte value by prefix
		$value = ($bytes/pow(1024,floor($units[$unit])));
	}
 
	// If decimals is not numeric or decimals is less than 0 
	// then set default value
	if (!is_numeric($decimals) || $decimals < 0) {
		$decimals = 2;
	}
 
	// Format output
	return sprintf('%.' . $decimals . 'f '.$unit, $value);
  }

function makeDiskBars()
{
	global $filesystems;
	foreach ($filesystems as $fs_index => $fs_info){
		$fs = explode(",",$fs_info);
	
	printDiskBarGB(getDiskspace($fs[0]), $fs[1], getDiskspaceUsed($fs[0]), disk_total_space($fs[0]));
	}
}

function makeRamBars()
{
	printRamBar(getFreeRam()[0],getFreeRam()[1],getFreeRam()[2],getFreeRam()[3]);
}

function makeLoadBars()
{
	printBar(getLoad(0), "1 min");
	printBar(getLoad(1), "5 min");
	printBar(getLoad(2), "15 min");
}

function getFreeRam()
{
	$top = shell_exec('free -m');
	$output = preg_split('/[\s]/', $top);
		for ($i=count($output)-1; $i>=0; $i--) {
		if ($output[$i] == '') unset ($output[$i]);
		}
	$output = array_values($output);
	$totalRam = $output[7]/1000; // GB
	$freeRam = $output[16]/1000; // GB
	$usedRam = $totalRam - $freeRam;
	return array (sprintf('%.0f',($usedRam / $totalRam) * 100), 'Used Ram', $usedRam, $totalRam);
}

function getDiskspace($dir)
{
	$df = disk_free_space($dir);
	$dt = disk_total_space($dir);
	$du = $dt - $df;
	return sprintf('%.0f',($du / $dt) * 100);
}

function getDiskspaceUsed($dir)
{
	$df = disk_free_space($dir);
	$dt = disk_total_space($dir);
	$du = $dt - $df;
	return $du;
}

function getLoad($id)
{
	return 100 * ($GLOBALS['loads'][$id] / 8);
}

function printBar($value, $name = "")
{
	if ($name != "") echo '<!-- ' . $name . ' -->';
	echo '<div class="exolight">';
		if ($name != "")
			echo $name . ": ";
			echo number_format($value, 0) . "%";
		echo '<div class="progress">';
			echo '<div class="progress-bar" style="width: ' . $value . '%"></div>';
		echo '</div>';
	echo '</div>';
}

function printRamBar($percent, $name = "", $used, $total)
{
	if ($percent < 90)
	{
		$progress = "progress-bar";
	}
	else if (($percent >= 90) && ($percent < 95))
	{
		$progress = "progress-bar progress-bar-warning";
	}
	else
	{
		$progress = "progress-bar progress-bar-danger";
	}

	if ($name != "") echo '<!-- ' . $name . ' -->';
	echo '<div class="exolight">';
		if ($name != "")
			echo $name . ": ";
			echo number_format($percent, 0) . "%";
		echo '<div rel="tooltip" data-toggle="tooltip" data-placement="bottom" title="' . number_format($used, 2) . ' GB / ' . number_format($total, 0) . ' GB" class="progress">';
			echo '<div class="'. $progress .'" style="width: ' . $percent . '%"></div>';
		echo '</div>';
	echo '</div>';
}

function printDiskBarGB($dup, $name = "", $dsu, $dts)
{
	if ($dup < 90)
	{
		$progress = "progress-bar";
	}
	else if (($dup >= 90) && ($dup < 95))
	{
		$progress = "progress-bar progress-bar-warning";
	}
	else
	{
		$progress = "progress-bar progress-bar-danger";
	}

	if ($name != "") echo '<!-- ' . $name . ' -->';
	echo '<div class="exolight">';
		if ($name != "")
			echo $name . ": ";
			echo number_format($dup, 0) . "%";
		echo '<div rel="tooltip" data-toggle="tooltip" data-placement="bottom" title="' . byteFormat($dsu, "GB", 0) . ' / ' . byteFormat($dts, "GB", 0) . '" class="progress">';
			echo '<div class="'. $progress .'" style="width: ' . $dup . '%"></div>';
		echo '</div>';
	echo '</div>';
}

function getNetwork() //returns wan_domain if you are outside your network, and local_server_ip if you are within the network
{
	global $local_server_ip;
	global $local_pfsense_ip;
	global $wan_domain;
	$clientIP = get_client_ip();
	if(preg_match("/192.168.1.*/",$clientIP))
		$network='http://'.$local_server_ip;
	else
		$network=$wan_domain;
	return $network;
}

function get_client_ip() 
{
	if ( isset($_SERVER["REMOTE_ADDR"])) { 
		$ipaddress = $_SERVER["REMOTE_ADDR"];
	}else if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
		$ipaddress = $_SERVER["HTTP_X_FORWARDED_FOR"];
	}else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
		$ipaddress = $_SERVER["HTTP_CLIENT_IP"];
	} 
	return $ipaddress;
}

function makeRecenlyReleased()
{
	global $plex_port;
	global $plex_server_ip;
	global $plexToken ;	// You can get your Plex token using the getPlexToken() function. This will be automated once I find out how often the token has to be updated.
	$plexNewestXML = simplexml_load_file($plex_server_ip.'/library/sections/2/recentlyAdded?X-Plex-Token='.$plexToken);
	$clientIP = get_client_ip();
	$network = getNetwork();
	
	echo '<div class="col-md-12">';
	echo '<div class="thumbnail">';
	echo '<div id="carousel-example-generic" class=" carousel slide">';
	//echo '<!-- Indicators -->';
	//echo '<ol class="carousel-indicators">';
	//echo '<li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>';
	//echo '<li data-target="#carousel-example-generic" data-slide-to="1"></li>';
	//echo '<li data-target="#carousel-example-generic" data-slide-to="2"></li>';
	//echo '</ol>';
	echo '<!-- Wrapper for slides -->';
	echo '<div class="carousel-inner">';
	echo '<div class="item active">';
	$mediaKey = $plexNewestXML->Video[0]['key'];
	$mediaXML = simplexml_load_file($plex_server_ip.$mediaKey.'?X-Plex-Token='.$plexToken);
	$movieTitle = $mediaXML->Video['grandparentTitle'];
	$movieArt = $mediaXML->Video['parentThumb'];
	echo '<img src="plex.php?img='.urlencode($plex_server_ip.$movieArt) . '" alt="...">';
	echo '</div>'; // Close item div
	$i=1;
	for ( ; ; ) {
		if($i==15) break;
		$mediaKey = $plexNewestXML->Video[$i]['key'];
		$mediaXML = simplexml_load_file($plex_server_ip.$mediaKey.'?X-Plex-Token='.$plexToken);
		$movieTitle = $mediaXML->Video['grandparentTitle'];
		$movieArt = $mediaXML->Video['parentThumb'];
		$movieYear = $mediaXML->Video['year'];
		echo '<div class="item">';
		echo '<img src="plex.php?img=' . urlencode($plex_server_ip.$movieArt).'" alt="...">';
		echo '</div>'; // Close item div
		$i++;
	}
	echo '</div>'; // Close carousel-inner div

	echo '<!-- Controls -->';
	echo '<a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">';
	//echo '<span class="glyphicon glyphicon-chevron-left"></span>';
	echo '</a>';
	echo '<a class="right carousel-control" href="#carousel-example-generic" data-slide="next">';
	//echo '<span class="glyphicon glyphicon-chevron-right"></span>';
	echo '</a>';
	echo '</div>'; // Close carousel slide div
	echo '</div>'; // Close thumbnail div

	echo '</div>'; // Close column div
}

function staticmakeRecenlyReleased()
{
	global $plex_port;
	global $plex_server_ip;
	global $plexToken ;	// You can get your Plex token using the getPlexToken() function. This will be automated once I find out how often the token has to be updated.
	$plexNewestXML = simplexml_load_file($plex_server_ip.'/library/sections/2/recentlyAdded?X-Plex-Token='.$plexToken);
	//https://videos.01tools.com/library/sections/2/recentlyAdded?X-Plex-Token=sLpN2seSvSVzzLyzZvCV
	
	$i=0;
	for ( ; ; ) {
		if($i==9) break;
		//$mediaKey = $plexNewestXML->Video[$i]['key'];
		//$mediaXML = simplexml_load_file($plex_server_ip.$mediaKey.'?X-Plex-Token='.$plexToken);
		//$movieArt = $mediaXML->Video['parentThumb'];
		$movieArt = $plexNewestXML->Video[$i]['grandparentThumb'];
		echo '<div class="col-xs-4 col-xs-4">';
		echo '<img src="plex.php?img=' . urlencode($plex_server_ip.$movieArt).'"class="img-fluid" alt="...">';
		echo '</div>';
		$i++;
	}

}

function plexMovieStats()
{
	global $plex_server_ip;
	global $plexToken;	
	//To test manually:
	// curl -H "Content-Length: 0" -H "X-Plex-Client-Identifier: my-app" -u "erc_c@hotmail.com"":""password" -X POST https://my.plexapp.com/users/sign_in.xml
	$plexNewMoviesXML = simplexml_load_file($plex_server_ip.'/library/sections/1/all?X-Plex-Token='.$plexToken);
	$total_movies = count($plexNewMoviesXML -> Video);
	$plexNewTVXML = simplexml_load_file($plex_server_ip.'/library/sections/2/all?X-Plex-Token='.$plexToken);
	$total_tv = count($plexNewTVXML -> Directory);
	
	echo '<div class="exolight">';
	//echo '<h4 class="exoextralight">Token: '.getPlexToken().'</h4>';
	echo '<h4 class="exoextralight">New Movies: '.$total_movies.'</h4>';
	echo '<h4 class="exoextralight">New TV Shows: '.$total_tv.'</h4>';
	echo '</div>';
}

function makeBandwidthBars()
{
	$array = getBandwidth();
	$dPercent = sprintf('%.0f',($array[0] / 55) * 100);
	$uPercent = sprintf('%.0f',($array[1] / 5) * 100);
	printBandwidthBar($dPercent, 'Download', $array[0]);
	printBandwidthBar($uPercent, 'Upload', $array[1]);
}

function getBandwidth()
{
    global $local_pfsense_ip;
	global $ssh_username;
	global $ssh_password;
	global $ssh_key;
	global $pfsense_if_name;
	$ssh = new SSH2($local_pfsense_ip);
	$key = new RSA($ssh_key);
	if (!$ssh->login($ssh_username,$ssh_password)) { 
		exit('Login Failed');
	}

	$dump = shell_exec('vnstat -i '.$pfsense_if_name.' -tr');
	$output = preg_split('/[\.|\s]/', $dump);
	for ($i=count($output)-1; $i>=0; $i--) {
		if ($output[$i] == '') unset ($output[$i]);
	}
	$output = array_values($output);
	$rxRate = $output[52];
	$rxFormat = $output[54];
	$txRate = $output[58];
	$txFormat = $output[60];
	if ($rxFormat == 'kbit/s') {
		$rxRateMB = $rxRate / 1024;
	} else {
		$rxRateMB = $rxRate;
	}
	if ($txFormat == 'kbit/s') {
		$txRateMB = $txRate / 1024;
	} else {
		$txRateMB = $txRate;
	}
	$rxRateMB = floatval($rxRateMB);
	$txRateMB = floatval($txRateMB);

	return  array($rxRateMB, $txRateMB);
}

function printBandwidthBar($percent, $name = "", $Mbps)
{
	if ($name != "") echo '<!-- ' . $name . ' -->';
	echo '<div class="exolight">';
		if ($name != "")
			echo $name . ": ";
			echo number_format($Mbps,2) . " Mbps";
		echo '<div class="progress">';
			echo '<div class="progress-bar" style="width: ' . $percent . '%"></div>';
		echo '</div>';
	echo '</div>';
}

function getPlexToken()
{
    global $plex_username;
	global $plex_password;
	//To test manually:
	// curl -H "Content-Length: 0" -H "X-Plex-Client-Identifier: my-app" -u "erc_c@hotmail.com"":""password" -X POST https://my.plexapp.com/users/sign_in.xml

	$myPlex = shell_exec('curl -H "Content-Length: 0" -H "X-Plex-Client-Identifier: dashboard1" -H "X-Plex-Product: Dashboard" -u "'.$plex_username.'"":""'.$plex_password.'" -X POST https://my.plexapp.com/users/sign_in.xml 2> /dev/null');
	$myPlex_xml = simplexml_load_string($myPlex);
    $token = $myPlex_xml['authenticationToken'];
	return $token;
}

function makeWeatherSidebar()
{
    //global $weather_lat;
	//global $weather_long;
	//global $forecast_api;

	//$currentForecast = json_decode(file_get_contents('https://api.forecast.io/forecast/'.$forecast_api.'/'.$forecastLat.','.$forecastLong.$forecastExcludes));
	//NOAA Weather
	$weather = noaa_weather_grabber( 'KPOU', 'yes' );
	$weather_forecast = noaa_weather_grabber_forecast('ALY/67,12', 'yes');
	$currentTemp = $weather->temp;
	$currentSummary = $weather->condition;
	$currentSummaryIcon = $weather->imgCode;
	//echo $currentSummaryIcon;
	$minutelySummary = $weather_forecast->shortForecast;
	$hourlySummary = $weather_forecast->longForecast;
	
	// If there are alerts, make the alerts variables
	// if (isset($currentForecast->alerts)) {
	// 	$alertTitle = $currentForecast->alerts[0]->title;
	// 	$alertExpires = $currentForecast->alerts[0]->expires;
	// 	$alertDescription = $currentForecast->alerts[0]->description;
	// 	$alertUri = $currentForecast->alerts[0]->uri;
	// }
	// Make the array for weather icons https://www.alessioatzeni.com/meteocons/
	$weatherIcons = [
		'day/skc' => 'B',
		'night/skc' => '2',
		'day/rain' => 'R',
		'night/rain' => '8',
		'day/snow' => 'W',
		'night/snow' => '#',
		'day/sleet' => 'X',
		'night/sleet' => '$',
		'day/wind_skc' => 'F',
		'night/wind_skc' => 'F',
		'day/wind_skt' => 'F',
		'night/wind_skt' => 'F',
		'day/fog' => 'L',
		'day/ovc' => 'N',
		'day/sct' => 'H',
		'night/sct' => '3',
		'partly-cloudy-night' => 'I',
	];
	$weatherIcon = $weatherIcons[$currentSummaryIcon];
	// If there is a severe weather warning, display it
	//if (isset($currentForecast->alerts)) {
	//	echo '<div class="alert alert-warning alert-dismissable">';
	//	echo '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
	//	echo '<strong><a href="'.$alertUri.'" class="alert-link">'.$alertTitle.'</a></strong>';
	//	echo '</div>';
	//}
	echo '<ul class="list-inline" style="margin-bottom:-20px">';
	echo '<li><h1 data-icon="'.$weatherIcon.'" style="font-size:500%;margin:0px -10px 20px -5px"></h1></li>';
	echo '<li><ul class="list-unstyled">';
	echo '<li><h1 class="exoregular" style="margin:0px">'.$currentTemp.'°</h1></li>';
	echo '<li><h4 class="exoregular" style="margin:0px;padding-right:10px;width:80px">'.$currentSummary.'</h4></li>';
	echo '</ul></li>';
	echo '</ul>';
	echo '<h4 class="exoregular">Next Hour</h4>';
	echo '<h5 class="exoextralight" style="margin-top:10px">'.$minutelySummary.'</h5>';
	echo '<h4 class="exoregular">Next 24 Hours</h4>';
	echo '<h5 class="exoextralight" style="margin-top:10px">'.$hourlySummary.'</h5>';
	echo '<p class="text-right no-link-color" style="margin-bottom: 0px;"><small><a href="https://forecast.weather.gov/MapClick.php?lat=41.6181&lon=-73.7257">Weather.gov</a></small></p>';
	//echo '<p class="text-right no-link-color" style="margin-bottom: 0px;"><small><a href="https://api.weather.gov/gridpoints/ALY/67,12/forecast">Extended</a></small></p>';
}

function printGraphs()
{
	echo '<iframe src="https://dashboard.graphdat.com/embed/e.15f8a5e28a?d-w=3&d-h=1&d-pad=5&d-header=0&d-slider=0&d-legend=0&d-light=1&d-bg=000000&d-sg-cpu=0-0-1-1&d-sg-mem=1-0-1-1&d-sg-ni=2-0-1-1-t&d-sg-no=2-0-1-1-b" ></iframe>';
}

?>
