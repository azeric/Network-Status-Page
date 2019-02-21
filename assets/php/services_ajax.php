<!DOCTYPE html>
<?php
	Ini_Set( 'display_errors', true );
	include("functions.php");
	include("service.class.php");

	//$config = parse_ini_file($config_path, true);
	//$plex_server_ip = $config['network']['plex_server_ip'];
?>
<html lang="en">
<?php 
$services = array(
	new service("Plex", "videos.01tools.com/web/index.html#!/dashboard"),
	new service("NextCloud", "cloud.01tools.com"),
	new service("Home Assistant", "iot.01tools.com"),
	new service("Motioneye", "camera.01tools.com"),
	new service("ruTorrent", "lw815.ultraseedbox.com/~azeric/rutorrent/"),
	new service("Sonarr","tv.01tools.com"),
	new service("Radarr", "movies.01tools.com"),
	new service("Jackett", "indexer.01tools.com"),
	new service("PiHole", "dns.01tools.com/admin"),
	new service("Traefik", "proxy.01tools.com:8081/"),
	new service("Grafana", "dashboard.01tools.com/"),
	new service("Trakt", "trakt.tv/")	
);
?>
<table class ="center">
	<?php foreach($services as $service){ ?>
		<tr>
			<td style="text-align: right; padding-right:5px;" class="exoextralight"><?php echo $service->name; ?></td>
			<td style="text-align: left;"><?php echo $service->makeButton(); ?></td>
		</tr>
	<?php }?>
</table>
