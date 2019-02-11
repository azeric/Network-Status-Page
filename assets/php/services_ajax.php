<!DOCTYPE html>
<?php
	Ini_Set( 'display_errors', true );
	include("functions.php");
	include("service.class.php");
	include("serviceSAB.class.php");

	$config = parse_ini_file($config_path, true);

	$wan_domain = $config['network']['wan_domain'];
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
// $sabnzbdXML = simplexml_load_file('http://192.168.1.3:8080/api?mode=qstatus&output=xml&apikey='.$sabnzbd_api);

// if (($sabnzbdXML->state) == 'Downloading'):
// 	$timeleft = $sabnzbdXML->timeleft;
// 	$sabTitle = 'SABnzbd ('.$timeleft.')';
// else:
// 	$sabTitle = 'SABnzbd';
// endif;

$services = array(
	new service("Plex", $plex_server_ip."/web/index.html#!/dashboard"),
	#new service("pfSense", 80, "http://192.168.1.1", "192.168.1.1"),
	//new serviceSAB($sabTitle, 8080, "http://coruscant:8080", "127.0.0.1:8080"),
	//new service("SickBeard", 8081, "http://coruscant:8085"),
	new service("NextCloud", "https://cloud.01tools.com"),
	#new service("Transmission", 9091, "http://d4rk.co:9091"),
	#new service("Subsonic",4040, "http://dashbad.com:4040")
	
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
