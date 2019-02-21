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
		// $handle = curl_init($this->url);
		// curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);

		// $response = curl_exec($handle);

		// /* Check for 404 (file not found). */
		// $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
		// if($httpCode == 404) {
		// 	return false;
		// }
		// return true;

		// curl_close($handle);

		$headers=@get_headers($this->url, 1);
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