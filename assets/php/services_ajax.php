<!DOCTYPE html>
<?php
	Ini_Set( 'display_errors', true );
	include("functions.php");
	include("service.class.php");

	$config = parse_ini_file($config_path, true);
	$plex_server_ip = $config['network']['plex_server_ip'];
?>
<html lang="en">
	<script>
	// Enable bootstrap tooltips
	$(function ()
	        { $("[rel=tooltip]").tooltip();
	        });
	</script>
<?php 

$services = array(
	new service("Plex", $plex_server_ip."/web/index.html#!/dashboard"),
	new service("NextCloud", "https://cloud.01tools.com"),
	new service("Home Assistant", "https://iot.01tools.com"),
	new service("Motioneye", "https://camera.01tools.com"),
	new service("ruTorrent", "https://lw815.ultraseedbox.com/~azeric/rutorrent/"),
	new service("Sonarr","https://tv.01tools.com"),
	new service("Radarr", "https://movies.01tools.com"),
	new service("Jackett", "https://indexer.01tools.com"),
	new service("PiHole", "https://dns.01tools.com/admin"),
	new service("Traefik", "https://proxy.01tools.com:8081/"),
	new service("Grafana", "https://dashboard.01tools.com/"),
	new service("Trakt", "https://trakt.tv/")

	
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
