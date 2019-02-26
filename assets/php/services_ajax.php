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
	new service("NextCloud", "https://cloud.01tools.com", "/assets/img/nextcloud.png"),
	new service("Home Assistant", "https://iot.01tools.com", "/assets/img/homeassistant.png"),
	new service("Motioneye", "https://camera.01tools.com", "/assets/img/motioneye.png"),
	new service("ruTorrent", "https://lw815.ultraseedbox.com/~azeric/rutorrent/", "/assets/img/rutorrent.png"),
	new service("Sonarr","https://tv.01tools.com", "/assets/img/sonarr.ico"),
	new service("Radarr", "https://movies.01tools.com", "/assets/img/radarr.png"),
	new service("Jackett", "https://indexer.01tools.com", "/assets/img/jackett.png"),
	new service("PiHole", "https://dns.01tools.com/admin", "/assets/img/pihole.png"),
	new service("Traefik", "https://proxy.01tools.com:8081/", "/assets/img/traefik.png"),
	new service("Grafana", "https://graphs.01tools.com/", "/assets/img/grafana.png")
);
?>
<table class ="center">
	<?php foreach($services as $service){ ?>
		<tr>
			<td style="text-align: right; padding-right:5px;" class="exoextralight"><?php echo $service->name; ?><img src="<?php echo $service->icon; ?>" style="width:14px" /></td>
			<td style="text-align: left;"><?php echo $service->makeButton(); ?></td>
		</tr>
	<?php }?>
</table>
