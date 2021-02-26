<?php
// Set the timezone
date_default_timezone_set( 'America/New_York' );

// Build the weather.gov api URL
function noaa_weather_grabber_weather_url_forecast( $gridpoints ) {
	$weather_url = 'https://api.weather.gov/gridpoints/' . $gridpoints . '/forecast';
	return $weather_url;
}

// Set Cache data path
function noaa_weather_grabber_cache_file_forecast( $gridpoints ) {
	$cachedata_file = CACHEDATA_FILE_PATH.'weather_data.json';
	return $cachedata_file;
}

// Sanitize the weather information and add it to a variable
function noaa_weather_grabber_get_extended_forecast( $raw_weather, $gridpoints ) {
	$weather = new stdClass();
	$weather->okay = "yes";
	$weather->shortForecast	= htmlentities( $raw_weather->properties->periods[0]->shortForecast, ENT_QUOTES );
	$weather->longForecast = htmlentities( $raw_weather->properties->periods[2]->detailedForecast, ENT_QUOTES );
	$weather->feedUpdatedAt	= htmlentities( $raw_weather->properties->updateTime, ENT_QUOTES );
	$weather->feedCachedAt	= date( 'c' );

	return $weather;
}

/**
 * Caching function
 * Returns an array of weather data and saves the data
 * to a cache file for later use.
 **/
function noaa_weather_grabber_make_new_cachedata_forecast( $gridpoints, $use_cache, $data_problems ) {

	// Define variables
	$weather_url = noaa_weather_grabber_weather_url_forecast( $gridpoints );
	$cachedata_file = noaa_weather_grabber_cache_file_forecast( $gridpoints );
	$continue = "yes";

	// Don't get weather data if the cache is on, the currently saved file indicates there was an error getting data and the file was saved less than 15 minutes ago.
	if (( $data_problems == "yes" ) && ( file_exists( $cachedata_file )) && ( date( 'YmdHis', filemtime( $cachedata_file )) > date( 'YmdHis', strtotime( 'Now - 900 seconds' )))) {
		$continue = "no";
		$use_cache = "no";
	}

	// Get the feed
	if ( $continue == "yes" ) {
		$raw_weather = noaa_weather_grabber_get_feed( $weather_url );
		if ( $raw_weather == FALSE ) {
			$continue = "no";
		}
	}

	// Sanatize the weather information and add it to a variable
	if ( $continue == "yes" ) {
		$weather = noaa_weather_grabber_get_extended_forecast( $raw_weather, $gridpoints );
	}

	// If there was an error, produce error message
	if ( $continue == "no" ) {
		$weather = new stdClass();
		$weather->okay = "no";
	}

	// Write the weather to file
	if ( $use_cache !== "no" ) {
		noaa_weather_grabber_write_to_file( $weather, $cachedata_file );
	}

	// Return the newly grabbed content
	return( $weather );
}
/**
 * Main function
 * Returns either previously cached data or newly fetched data
 * depending on whether or not it exists and whether or not the
 * cache time has expired.
 **/
function noaa_weather_grabber_forecast( $gridpoints = NULL, $use_cache = "yes" ) {

	// Make sure $stationId is capitalized
	$gridpoints = strtoupper( $gridpoints );

	// Get cache file location
	$cachedata_file = noaa_weather_grabber_cache_file_forecast( $gridpoints );

	// Set continue variable
	$continue = "yes";

	// See if cached data is available and usable
	if (( $use_cache == "no" ) || ( $use_cache == "update" )) {$continue = "cacheOff";}
	if ( $gridpoints == NULL ) {$continue = "stationIDError";}
	if ( $continue == "yes" ) {
		if (( file_exists( $cachedata_file )) && ( date( 'YmdHis', filemtime( $cachedata_file )) > date( 'YmdHis', strtotime( 'Now -'.WEATHER_CACHE_DURATION.' seconds' )))) {}
		else {$continue = "outdated";}
	}

	// Provide the cached data or get new data if data problems
	if ( $continue == "yes" ) {
		$raw_weather = file_get_contents( $cachedata_file ) or die( 'Cache file open failed.' );
		$raw_weather = json_decode( $raw_weather );
		if ( $raw_weather->okay == "yes" ) {

			// Sanitize weather in a new variable
			$weather = new stdClass();
			$weather->okay			= htmlentities( $raw_weather->okay, ENT_QUOTES );
			$weather->shortForecast	= htmlentities( $raw_weather->shortForecast, ENT_QUOTES );
			$weather->longForecast	= htmlentities( $raw_weather->longForecast, ENT_QUOTES );
			$weather->feedUpdatedAt	= htmlentities( $raw_weather->feedUpdatedAt, ENT_QUOTES );
			$weather->feedCachedAt	= htmlentities( $raw_weather->feedCachedAt, ENT_QUOTES );

			return( $weather );
		}
		else {
			return noaa_weather_grabber_make_new_cachedata_forecast( $gridpoints, $use_cache, "yes" );
		}
	}
	elseif ( $continue == "stationIDError" ) {
		$weather = new stdClass();
		$weather->okay = "no";
		return( $weather );
	}
	else {
		return noaa_weather_grabber_make_new_cachedata_forecast( $gridpoints, $use_cache, "no" );
	}
}
?>
