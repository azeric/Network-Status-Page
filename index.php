<!DOCTYPE html>
<?php
	Error_Reporting( E_ALL | E_STRICT );
	Ini_Set( 'display_errors', true);

	include("assets/php/functions.php");
	include('assets/php/Mobile_Detect.php');
	//include('assets/php/weather.php');
	//include('assets/php/weather_forecast.php');

	$detect = new Mobile_Detect;
	$weather = noaa_weather_grabber( 'KPOU', 'yes' );
?>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>01Tools</title>
		<meta name="author" content="dash">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<!-- Le styles -->
		<link href="assets/fonts/stylesheet.css" rel="stylesheet">
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0-wip/css/bootstrap.min.css">
		<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.0/css/font-awesome.css" rel="stylesheet">
		<style type="text/css">
			body {
				text-align: center;
			}
			.center {
				margin-left:auto;
				margin-right:auto;
			}
			.no-link-color 
				a {
					color:#999999;
				}
				a:hover {
					color:#999999;	
				}
			
			.exoextralight {
				font-family:"exoextralight";
			}
			.exolight {
				font-family:"exolight";
			}
			[data-icon]:before {
				font-family: 'MeteoconsRegular';
				content: attr(data-icon);
			}
			.exoregular {
				font-family:"exoregular";
			}
			img.img-fluid {
				height: 219px;
			}
			
		</style>
		<link rel="shortcut icon" href="assets/img/favicon.ico">
		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="//code.jquery.com/jquery.js"></script>
		<!-- Latest compiled and minified JavaScript -->
		<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0-wip/js/bootstrap.min.js"></script>
		<script>
		// Enable bootstrap tooltips
		$(function () { 
			$("[rel=tooltip]").tooltip();
			$("[rel=popover]").popover();
			}); 
		// Auto refresh things
		(function($) {
			$(document).ready(function() {
				$.ajaxSetup({
		            		cache: false,
		            		beforeSend: function() {
		            			$('#left_column_top').show();
		            			$('#services').show();
								$('#system_load').show();
								$('#disk_space').show();
								$('#now_playing_title').show();
								$('#now_playing').show();
								$('#system_ram').show();
								$('#plex_movie_stats').show();
		            		},
				            complete: function() {
				            	$('#left_column_top').show();
				            	$('#services').show();
								$('#system_load').show();
								$('#disk_space').show();
								$('#now_playing_title').show();
								$('#now_playing').show();
								$('#system_ram').show();
								$('#plex_movie_stats').show();
				            },
				            success: function() {
				            	$('#left_column_top').show();
				            	$('#services').show();
								$('#system_load').show();
								$('#disk_space').show();
								$('#now_playing_title').show();
								$('#now_playing').show();
								$('#system_ram').show();
								$('#plex_movie_stats').show();
				            }
				});

				// Assign varibles to DOM sections
				//var $plex_check_refresh = $('#plex_check');
				var $left_column_top_refresh = $('#left_column_top');
				var $services_refresh = $('#services');
				var $system_load_refresh = $('#system_load');
				var $disk_space_refresh = $('#disk_space');
				var $now_playing_title_refresh = $('#now_playing_title');
				var $now_playing_refresh = $('#now_playing');
				var $system_ram_refresh = $('#system_ram');
				var $plex_movie_stats_refresh = $('#plex_movie_stats');

				// Load external php files & assign variables
				//$plex_check_refresh.load('assets/php/plex_check_ajax.php');
				$left_column_top_refresh.load('assets/php/left_column_top_ajax.php');
				$services_refresh.load("assets/php/services_ajax.php");
				$system_load_refresh.load("assets/php/system_load_ajax.php");
				$disk_space_refresh.load("assets/php/disk_space_ajax.php");
				$now_playing_title_refresh.load("assets/php/now_playing_title_ajax.php");
				$now_playing_refresh.load("assets/php/now_playing_ajax.php");
				$system_ram_refresh.load("assets/php/system_ram_ajax.php");
				$plex_movie_stats_refresh.load("assets/php/plex_movie_stats_ajax.php");
			        
				var refreshIdfastest = setInterval(function(){
			        		//$plex_check_refresh.load('assets/php/plex_check_ajax.php');
			    }, 10000); // at 3, 5 seconds python was crashing.

				var refreshId30 = setInterval(function(){
					$services_refresh.load("assets/php/services_ajax.php");
				}, 30000); // 30 seconds

				var refreshId60 = setInterval(function(){
					$system_load_refresh.load('assets/php/system_load_ajax.php');
				}, 60000); // 60 seconds

				var refreshIdslow = setInterval(function(){
					$disk_space_refresh.load('assets/php/disk_space_ajax.php');
					$system_ram_refresh.load('assets/php/system_ram_ajax.php');
					//$plex_movie_stats_refresh.load("assets/php/plex_movie_stats_ajax.php")
				}, 300000); // 5 minutes

				var refreshtopleft = setInterval(function(){
					$left_column_top_refresh.load('assets/php/left_column_top_ajax.php');
				}, 300000); // 5 minutes

				var refreshlongest = setInterval(function(){
					$plex_movie_stats_refresh.load("assets/php/plex_movie_stats_ajax.php");
				}, 3600000); // 1 hour

				// Load these sections only if Plex has changed states
				var theResource = "assets/misc/plexcheckfile2.txt";

				var refreshconditional = setInterval(function(){
					if(localStorage["resourcemodified"]) {
						$.ajax({
							url:theResource,
							type:"head",
							success:function(res,code,xhr) {
									console.log("Checking Plex XML "+ localStorage["resourcemodified"] + " to "+ xhr.getResponseHeader("Last-Modified"))
									if(localStorage["resourcemodified"] != xhr.getResponseHeader("Last-Modified")) getResource();
							}
						})
				 
				    } else getResource();
				 
				function getResource() {
					$.ajax({
						url:theResource,
						type:"get",
						cache:false,
						success:function(res,code,xhr) {
								localStorage["resourcemodified"] = xhr.getResponseHeader("Last-Modified");
								$left_column_top_refresh.load('assets/php/left_column_top_ajax.php');
								$now_playing_title_refresh.load("assets/php/now_playing_title_ajax.php");
								$now_playing_refresh.load("assets/php/now_playing_ajax.php");
						}                    
					})
				}
			}, 30000); // 5 seconds

			// Change the size of the now playing div to match the client size every time it's resized
			function doResizeNowPlaying() {
				var height = 0;
				var body = window.document.body;
				if (window.innerHeight) {
					height = window.innerHeight;
				} else if (body.parentElement.clientHeight) {
					height = body.parentElement.clientHeight;
				} else if (body && body.clientHeight) {
					height = body.clientHeight;
				}
				now_playing.style.height = ((height - now_playing.offsetTop) + "px");
				console.log("Div resize complete. New size is: " + height);
			};

			if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
				// some code..
			} else {
				var resizeTimer;
				$(window).resize(function() {
					clearTimeout(resizeTimer);
					resizeTimer = setTimeout(doResizeNowPlaying, 100);
				});

				$(function(){
					clearTimeout(resizeTimer);
					resizeTimer = setTimeout(doResizeNowPlaying, 100);
				});
			}
			});
		})(jQuery);
		</script>
	</head>
	<body>
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="row">
						<!-- Left sidebar -->
						<div class="col-md-3" style="padding-top: 20px;">
							<!-- Weather-->
							<div class="panel panel-default">
							<div class="panel-heading">
									<h4 class="panel-title exoextralight">
										Weather
									</h4>
							</div>		
								<div class="panel-body">	
									<div id="left_column_top"></div>
								</div>
							</div>
							<!-- Services -->
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title exoextralight">
										Services
									</h4>
								</div>
								<div id="services" class="panel-body">
								</div>
							</div>
						</div>
						<!-- Center Area -->
						<div class="col-md-6">
							<div id="now_playing_title"></div>
							
							<?php	echo '<div id="now_playing" style="overflow:auto;">';
								echo '</div>';?>

							<hr class="visible-xs">
							
							<!--<hr>-->
						</div>
						<!-- Right sidebar -->
						<?php echo '<div class="col-md-3"';
						// Only apply padding on top of this column if its not on a mobile device
						if ( $detect->isMobile() ):
							echo '>';
						else:
							echo ' style="padding-top: 20px;">';
						endif;?>
							<!-- Server info -->
						<div class="panel panel-default">
							<div class="panel-heading">
									<h4 class="panel-title exoextralight">
										Server Info
									</h4>
									</div>
								<div class="panel-body">
									<h4 class="exoextralight">Load</h4>
									<div id="system_load"></div>
									<hr>
									<h4 class="exoextralight">Memory</h4>
									<div id="system_ram" style="height:40px"></div>
									<hr>
									<h4 class="exoextralight">Disk space</h4>
									<div id="disk_space"></div>
									<hr>
								</div>
							</div>
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title exoextralight">Plex library</h4>
								</div>	
								<div id="plex_movie_stats"></div>
							</div>
							<!--*************Backups***************-->
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title exoextralight">Backup Status</h4>
								</div>	
								
								<div>
									<h3 style="margin: 4px 0 10px; font-weight: normal; text-align: center">
									<!--<h5 class="exoextralight">Main Backup</h5>-->
									<?php
										$file = fopen("/media/datadrive/configs/mainBackupStatus.txt","r");
										$rawdate = fgets($file);
										date_default_timezone_set('America/New_York');
										$currdate = date("m-d-Y");
										if(date("m-d-Y") > $rawdate)
											echo "<h4 style='background-color: red'>LATE</h4>";
										echo "<h5 class='exoextralight'>Last Main Backup: $rawdate </h5>";
										echo "<h5 class='exoextralight'>Last Portable Backup:  </h5>";
										echo "<h5 class='exoextralight'>Current Date:  $currdate</h5>";
										
									?>
								</div>										
							</div>
							<!--***********End Backups*********************-->


						</div>
				</div>
			</div>
		</div>
	</body>
</html>
