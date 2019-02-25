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
	new service("Plex", "https://videos.01tools.com/web/index.html#!/dashboard", "/assets/img/plex.ico" ),
	new service("NextCloud", "https://cloud.01tools.com", "../img/plex.ico"),
	new service("Home Assistant", "https://iot.01tools.com", "../img/plex.ico"),
	new service("Motioneye", "https://camera.01tools.com", "../img/plex.ico"),
	new service("ruTorrent", "https://lw815.ultraseedbox.com/~azeric/rutorrent/", "../img/plex.ico"),
	new service("Sonarr","https://tv.01tools.com", "../img/plex.ico"),
	new service("Radarr", "https://movies.01tools.com", "../img/plex.ico"),
	new service("Jackett", "https://indexer.01tools.com", "../img/plex.ico"),
	new service("PiHole", "https://dns.01tools.com/admin", "../img/plex.ico"),
	new service("Traefik", "https://proxy.01tools.com:8081/", "../img/plex.ico"),
	new service("Grafana", "https://graphs.01tools.com/", "../img/plex.ico"),
	new service("Trakt", "https://trakt.tv/dashboard", "../img/plex.ico")	
);
?>
<table class ="center">
	<?php foreach($services as $service){ ?>
		<tr>
			<td style="text-align: right; padding-right:5px;" class="exoextralight"><img src="<?php echo $service->icon; ?>"/><?php echo $service->name; ?></td>
			<td style="text-align: left;"><?php echo $service->makeButton(); ?></td>
		</tr>
	<?php }?>
</table>
