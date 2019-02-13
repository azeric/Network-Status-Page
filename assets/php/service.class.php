<?php
class service
{
	public $name;
	public $port;
	public $url;
	public $host;
	public $status;
	
	function __construct($name, $url = "", $host = "localhost")
	{
		$this->name = $name;
		$this->url = $url;
		$this->host = $host;
		
		$this->status = $this->check_port();
	}
	
	function check_port()
	{
		//if (!$fp = curl_init($this->url)) return false;
		$headers=get_headers($this->url, 1);
   		if ($headers[0]!='HTTP/1.1 200 OK') return true; else return false;
	}
	
	function makeButton()
	{
		$icon = '<i class="icon-' . ($this->status ? 'ok' : 'remove') . ' icon-white"></i>';
		$btn = $this->status ? 'success' : 'warning';
		$prefix = $this->url == "" ? '<button style="width:62px" class="btn btn-xs btn-' . $btn . ' disabled">' : '<a href="' . $this->url . '" style="width:62px" class="btn btn-xs btn-' . $btn . '">';
		$txt = $this->status ? 'Online' : 'Offline';
		$suffix = $this->url == "" ? '</button>' : '</a>';
		
		return $prefix . $icon . " " . $txt . $suffix;
	}

}
?>