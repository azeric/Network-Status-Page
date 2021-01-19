<!DOCTYPE html>
<?php
	Error_Reporting( E_ALL | E_STRICT );
	Ini_Set( 'display_errors', true);

	include("assets/php/functions.php");
	include('assets/php/Mobile_Detect.php');

	$detect = new Mobile_Detect;
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
			img {
				width: 100%;
			}
			img.img-fluid {
				height: 219px;
			}
			
		</style>
		<link rel="shortcut icon" href="assets/ico/favicon.ico">
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
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title exoextralight">Goodreads</h4>
								</div>	
								<div>      <!-- Show static HTML/CSS as a placeholder in case js is not enabled - javascript include will override this if things work -->
      <style type="text/css" media="screen">
  .gr_custom_container_1611070899 {
    /* customize your Goodreads widget container here*/
    border: 1px solid gray;
    border-radius:10px;
    padding: 10px 5px 10px 5px;
    background-color: #FFFFFF;
    color: #000000;
    width: 300px
  }
  .gr_custom_header_1611070899 {
    /* customize your Goodreads header here*/
    border-bottom: 1px solid gray;
    width: 100%;
    margin-bottom: 5px;
    text-align: center;
    font-size: 120%
  }
  .gr_custom_each_container_1611070899 {
    /* customize each individual book container here */
    width: 100%;
    clear: both;
    margin-bottom: 10px;
    overflow: auto;
    padding-bottom: 4px;
    border-bottom: 1px solid #aaa;
  }
  .gr_custom_book_container_1611070899 {
    /* customize your book covers here */
    overflow: hidden;
    height: 60px;
      float: left;
      margin-right: 4px;
      width: 39px;
  }
  .gr_custom_author_1611070899 {
    /* customize your author names here */
    font-size: 10px;
  }
  .gr_custom_tags_1611070899 {
    /* customize your tags here */
    font-size: 10px;
    color: gray;
  }
  .gr_custom_rating_1611070899 {
    /* customize your rating stars here */
    float: right;
  }
</style>

      <div id="gr_custom_widget_1611070899">
          <div class="gr_custom_container_1611070899">
    <h2 class="gr_custom_header_1611070899">
    <a style="text-decoration: none;" rel="nofollow" href="https://www.goodreads.com/review/list/11098370-eric?shelf=currently-reading&amp;utm_medium=api&amp;utm_source=custom_widget">Eric&#39;s bookshelf: currently-reading</a>
    </h2>
      <div class="gr_custom_each_container_1611070899">
          <div class="gr_custom_book_container_1611070899">
            <a title="Central Station" rel="nofollow" href="https://www.goodreads.com/review/show/1626833162?utm_medium=api&amp;utm_source=custom_widget"><img alt="Central Station" border="0" src="https://i.gr-assets.com/images/S/compressed.photo.goodreads.com/books/1460506349l/25986774._SY75_.jpg" /></a>
          </div>
          <div class="gr_custom_rating_1611070899">
            <span class=" staticStars notranslate"><img src="https://www.goodreads.com/images/layout/gr_red_star_inactive.png" /><img alt="" src="https://www.goodreads.com/images/layout/gr_red_star_inactive.png" /><img alt="" src="https://www.goodreads.com/images/layout/gr_red_star_inactive.png" /><img alt="" src="https://www.goodreads.com/images/layout/gr_red_star_inactive.png" /><img alt="" src="https://www.goodreads.com/images/layout/gr_red_star_inactive.png" /></span>
          </div>
          <div class="gr_custom_title_1611070899">
            <a rel="nofollow" href="https://www.goodreads.com/review/show/1626833162?utm_medium=api&amp;utm_source=custom_widget">Central Station</a>
          </div>
          <div class="gr_custom_author_1611070899">
            by <a rel="nofollow" href="https://www.goodreads.com/author/show/572738.Lavie_Tidhar">Lavie Tidhar</a>
          </div>
          <div class="gr_custom_tags_1611070899">
            tagged:
            currently-reading
          </div>
      </div>
      <div class="gr_custom_each_container_1611070899">
          <div class="gr_custom_book_container_1611070899">
            <a title="Helping Your Child with Extreme Picky Eating: A Step-by-Step Guide for Overcoming Selective Eating, Food Aversion, and Feeding Disorders" rel="nofollow" href="https://www.goodreads.com/review/show/3394970286?utm_medium=api&amp;utm_source=custom_widget"><img alt="Helping Your Child with Extreme Picky Eating: A Step-by-Step Guide for Overcoming Selective Eating, Food Aversion, and Feeding Disorders" border="0" src="https://i.gr-assets.com/images/S/compressed.photo.goodreads.com/books/1413517246l/23129657._SX50_.jpg" /></a>
          </div>
          <div class="gr_custom_rating_1611070899">
            <span class=" staticStars notranslate"><img src="https://www.goodreads.com/images/layout/gr_red_star_inactive.png" /><img alt="" src="https://www.goodreads.com/images/layout/gr_red_star_inactive.png" /><img alt="" src="https://www.goodreads.com/images/layout/gr_red_star_inactive.png" /><img alt="" src="https://www.goodreads.com/images/layout/gr_red_star_inactive.png" /><img alt="" src="https://www.goodreads.com/images/layout/gr_red_star_inactive.png" /></span>
          </div>
          <div class="gr_custom_title_1611070899">
            <a rel="nofollow" href="https://www.goodreads.com/review/show/3394970286?utm_medium=api&amp;utm_source=custom_widget">Helping Your Child with Extreme Picky Eating: A Step-by-Step Guide for Overcoming Selective Eating, Food Aversion, and Feeding Disorders</a>
          </div>
          <div class="gr_custom_author_1611070899">
            by <a rel="nofollow" href="https://www.goodreads.com/author/show/6535908.Katja_Rowell">Katja Rowell</a>
          </div>
          <div class="gr_custom_tags_1611070899">
            tagged:
            currently-reading
          </div>
      </div>
      <div class="gr_custom_each_container_1611070899">
          <div class="gr_custom_book_container_1611070899">
            <a title="Sensational Kids: Hope and Help for Children with Sensory Processing Disorder" rel="nofollow" href="https://www.goodreads.com/review/show/3626156393?utm_medium=api&amp;utm_source=custom_widget"><img alt="Sensational Kids: Hope and Help for Children with Sensory Processing Disorder" border="0" src="https://i.gr-assets.com/images/S/compressed.photo.goodreads.com/books/1309281980l/880956._SY75_.jpg" /></a>
          </div>
          <div class="gr_custom_rating_1611070899">
            <span class=" staticStars notranslate"><img src="https://www.goodreads.com/images/layout/gr_red_star_inactive.png" /><img alt="" src="https://www.goodreads.com/images/layout/gr_red_star_inactive.png" /><img alt="" src="https://www.goodreads.com/images/layout/gr_red_star_inactive.png" /><img alt="" src="https://www.goodreads.com/images/layout/gr_red_star_inactive.png" /><img alt="" src="https://www.goodreads.com/images/layout/gr_red_star_inactive.png" /></span>
          </div>
          <div class="gr_custom_title_1611070899">
            <a rel="nofollow" href="https://www.goodreads.com/review/show/3626156393?utm_medium=api&amp;utm_source=custom_widget">Sensational Kids: Hope and Help for Children with Sensory Processing Disorder</a>
          </div>
          <div class="gr_custom_author_1611070899">
            by <a rel="nofollow" href="https://www.goodreads.com/author/show/453532.Lucy_Jane_Miller">Lucy Jane Miller</a>
          </div>
          <div class="gr_custom_tags_1611070899">
            tagged:
            currently-reading
          </div>
      </div>
      <div class="gr_custom_each_container_1611070899">
          <div class="gr_custom_book_container_1611070899">
            <a title="Hench" rel="nofollow" href="https://www.goodreads.com/review/show/3639374642?utm_medium=api&amp;utm_source=custom_widget"><img alt="Hench" border="0" src="https://i.gr-assets.com/images/S/compressed.photo.goodreads.com/books/1594616305l/49867430._SY75_.jpg" /></a>
          </div>
          <div class="gr_custom_rating_1611070899">
            <span class=" staticStars notranslate"><img src="https://www.goodreads.com/images/layout/gr_red_star_inactive.png" /><img alt="" src="https://www.goodreads.com/images/layout/gr_red_star_inactive.png" /><img alt="" src="https://www.goodreads.com/images/layout/gr_red_star_inactive.png" /><img alt="" src="https://www.goodreads.com/images/layout/gr_red_star_inactive.png" /><img alt="" src="https://www.goodreads.com/images/layout/gr_red_star_inactive.png" /></span>
          </div>
          <div class="gr_custom_title_1611070899">
            <a rel="nofollow" href="https://www.goodreads.com/review/show/3639374642?utm_medium=api&amp;utm_source=custom_widget">Hench</a>
          </div>
          <div class="gr_custom_author_1611070899">
            by <a rel="nofollow" href="https://www.goodreads.com/author/show/20563409.Natalie_Zina_Walschots">Natalie Zina Walschots</a>
          </div>
          <div class="gr_custom_tags_1611070899">
            tagged:
            currently-reading
          </div>
      </div>
  <br style="clear: both"/>
  <center>
    <a rel="nofollow" href="https://www.goodreads.com/"><img alt="goodreads.com" style="border:0" src="https://www.goodreads.com/images/widget/widget_logo.gif" /></a>
  </center>
  <noscript>
    Share <a rel="nofollow" href="https://www.goodreads.com/">book reviews</a> and ratings with Eric, and even join a <a rel="nofollow" href="https://www.goodreads.com/group">book club</a> on Goodreads.
  </noscript>
  </div>

      </div>
      <script src="https://www.goodreads.com/review/custom_widget/11098370.Eric's%20bookshelf:%20currently-reading?cover_position=left&cover_size=small&num_books=5&order=a&shelf=currently-reading&show_author=1&show_cover=1&show_rating=1&show_review=1&show_tags=1&show_title=1&sort=date_added&widget_bg_color=FFFFFF&widget_bg_transparent=&widget_border_width=1&widget_id=1611070899&widget_text_color=000000&widget_title_size=medium&widget_width=medium" type="text/javascript" charset="utf-8"></script></div>
							</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Invisible php div-->
		<!--<div id="plex_check"></div>-->
		</body>
</html>
