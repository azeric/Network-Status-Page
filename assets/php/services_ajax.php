<!DOCTYPE html>
<?php
	Ini_Set( 'display_errors', true );
	include("functions.php");
	include("service.class.php");
?>
<html lang="en">
<?php 
$services = array(
	new service("Plex ", "https://videos.01tools.com/web/index.html#!/dashboard", "/assets/img/plex.ico" ),
	new service("Sonarr ","https://tv.01tools.com", "/assets/img/sonarr.ico"),
	new service("Radarr ", "https://movies.01tools.com", "/assets/img/radarr.png"),
	new service("Deluge ", "https://azeric.lw815.usbx.me/deluge", "/assets/img/deluge.png"),
	new service("Home Assistant ", "https://iot.01tools.com", "/assets/img/homeassistant.png"),
	new service("Motioneye ", "https://camera.01tools.com", "/assets/img/motioneye.png"),
	new service("Node Red ", "https://automate.01tools.com", "/assets/img/node-red.png"),
	new service("NextCloud ", "https://cloud.01tools.com", "/assets/img/nextcloud.png"),	
	new service("Bitwarden ", "https://pass.01tools.com/", "/assets/img/bitwarden.png"),
	new service("Authelia ", "https://login.01tools.com/", "/assets/img/authelia.png"),
	new service("Jackett ", "https://indexer.01tools.com", "/assets/img/jackett.png"),
	new service("PiHole ", "https://dns.01tools.com/admin", "/assets/img/pihole.png"),
	new service("Traefik ", "https://proxy.01tools.com/", "/assets/img/traefik.png"),
	new service("Unifi ", "https://network.01tools.com/", "/assets/img/unifi.png"),
	new service("ProxMox ", "https://vm.01tools.com", "/assets/img/proxmox.png"),
	new service("Barn Router ", "https://174.44.96.250:8443/Main_Login.asp", "/assets/img/asus.jpg")	
	#https://barn.asuscomm.com:8443/main_login.asp  	
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
