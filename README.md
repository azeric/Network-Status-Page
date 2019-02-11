This is copied from Ryan Christensen's original project for his OSX server



I have made a  number of changes:
- Modification of functions to support Linux shell commands
- Removed minecraft functionality
- Removed services I don't have on my server and added one (Subsonic)
- Modified the plex token function to pull this automatically from myplex (this does slow down page loading a fair bit)
- Moved user credentials to a config.ini you can place outside of your web root. Just make sure it is correctly included in the relevant functions that require it
- Amended IP Addresses to reflect my LAN

You will need php 5.5.4 and I'm running it on Apache

Network Status Page - 0.2.7
===================
Dashboard for home servers running a small suite of media and other applications

[Example Site][ls]

[ls]: https://nicko88.com/


###Features
---------------
* Responsive web design viewable on desktop, tablet and mobile web browsers 

* Designed using [Bootstrap 3][bs]

* Uses jQuery to provide near real time feedback

* Optimized for Ubuntu Servers  `Tested on 18.04`

* Displays the following:
	* currently playing items from Plex Media Server
	* current network bandwidth from pfSense
	* current ping to ip of your choosing, e.g. Google DNS
	* online / offline status for custom services
	* minute by minute weather forecast from forecast.io
	* server load
	* total disk space for all hard drives

* Now Playing section adjusts scrollable height on the fly depending on browser window height

[bs]: http://getbootstrap.com

###Requirements
---------------
* [Plex Media Server][pms] (v0.9.8+) and a [myPlex][pp] account `These are both free.`
* The weather sidebar requires a [forecast.io API key][fcAPI] `Free up to 1000 calls/day.`
* Web server that supports php (apache, nginx, XAMPP, WampServer, EasyPHP, lighttpd, etc)
* PHP 5.4

[pms]: https://plex.tv
[pp]: https://plex.tv/subscription/about
[fcAPI]: https://developer.forecast.io


###Optional
---------------
* A few functions are written to be used with the following software but they are optional:
	* [SABnzbd+][sab]
	* [pfSense][pfs]

[sab]: http://sabnzbd.org
[pfs]: http://www.pfsense.org
