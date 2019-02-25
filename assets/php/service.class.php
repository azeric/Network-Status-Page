<?php
class service
{
	public $name;
	public $url;
	public $status;
	
	function __construct($name, $url)
	{
		$this->name = $name;
		$this->url = $url;
		
		$this->status = $this->check_port();
	}
	
	function check_port()
	{
		// $conn = @fsockopen($this->url, 443, $errno, $errstr, 0.5);
		// if ($conn) 
		// {
		// 	fclose($conn);
		// 	return true;
		// }
		// else
		// 	return false;
		$handle = curl_init($this->url);
		curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);

		$response = curl_exec($handle);

		/* Check for 404 (file not found). */
		$httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
		if($httpCode == 404) {
			return false;
		}
		return true;

		curl_close($handle);

		// $headers=get_headers($this->url, 1);
   		// if ($headers[0]!='HTTP/1.1 200 OK') return true; else return false;
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