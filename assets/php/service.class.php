<?php
class service
{
	public $name;
	public $url;
	public $icon;
	public $status;
	
	function __construct($name, $url, $icon)
	{
		$this->name = $name;
		$this->url = $url;
		$this->icon = $icon;
		$this->status = $this->check_port();
	}
	
	function check_port()
	{
		$handle = curl_init($this->url);
		curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 3); 
		$response = curl_exec($handle);

		/* Check for 404 (file not found). */
		$httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
		if($httpCode == 404 | $httpCode == 502) {
			return false;
		}
		/* Check if timeout occured */
		if($httpCode == '')
		{
			return false;
		}
		return true;

		//curl_close($handle);
		$curl->close();
	}
	
	function makeButton()
	{
		$icon = '<i class="icon-' . ($this->status ? 'ok' : 'remove') . ' icon-white"></i>';
		$btn = $this->status ? 'success' : 'warning';
		$prefix = $this->url == "" ? '<button style="width:62px" class="btn btn-xs btn-' . $btn . ' disabled">' : '<a href="' . $this->url . '" style="width:62px" class="btn btn-xs btn-' . $btn . '" target="_blank">';
		$txt = $this->status ? 'Online' : 'Offline';
		$suffix = $this->url == "" ? '</button>' : '</a>';
		
		return $prefix . $icon . " " . $txt . $suffix;
	}
}
?>