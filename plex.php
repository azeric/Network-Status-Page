<?php
include("assets/php/functions.php");
$image_url = $_GET['img'];
$plexAddress = $plex_server_ip;
$addressPosition = strpos($image_url, $plexAddress);
if($addressPosition !== false && $addressPosition == 0) {
	$image_src = $image_url . '?X-Plex-Token=' . getPlexToken();
	header('Content-type: image/jpeg');
	//header("Content-Length: " . filesize($image_src));
	readfile($image_src);
} else {
echo "Bad Plex Image Url";	
}
?>