<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
 
class Apiservicesas
{
		var $secretkey="323232";
		var $urldestination="http://localhost/simpedas/servicesas/getdata.php";
		var $urldestinationkuitansi = "http://localhost/simpedas/servicesas/getdatakuitansi.php";
		var $urldestinationspm = "http://localhost/simpedas/servicesas/getdataspm.php";
		var $urldestinationddspm = "http://localhost/simpedas/servicesas/getdatadspm.php";
		var $urldestinationspmid = "http://localhost/simpedas/servicesas/getdataspmid.php";
		var $urldestinationitem = "http://localhost/simpedas/servicesas/getdataitem.php";
		var $token = "";
	function Apiservicesas()
	{
	//	die('fdsgsf');
	}
 
 	private function searchdatadasar($parameters)
	{
		return $this->eventsearch($parameters,$this->urldestination);
	}
	private function searchdata($parameters)
	{
		return $this->eventsearch($parameters,$this->urldestination);
	}
	private function searchdatakuitansi($parameters)
	{
		return $this->eventsearch($parameters,$this->urldestinationkuitansi);
	}
	private function searchdataspm($parameters)
	{
		return $this->eventsearch($parameters,$this->urldestinationspm);
	}
	private function searchdatadspm($parameters)
	{
		return $this->eventsearch($parameters,$this->urldestinationddspm);
	}
	private function searchdataspmid($parameters)
	{
		return $this->eventsearch($parameters,$this->urldestinationspmid);
	}
	private function searchdataitem($parameters)
	{
		return $this->eventsearch($parameters,$this->urldestinationitem);
	}
	
	function  eventsearch($params,$url)
	{	 
		$ci =& get_instance();
		$ci->load->library('emulator');
		$emulator = new emulator();
		$cookie = "";
		$host = $url;
		$canonicalized_query = array();
	 
		foreach ($params as $param=>$value)
		{
			$param = str_replace("%7E", "~", rawurlencode($param));
			$value = str_replace("%7E", "~", rawurlencode($value));
			$canonicalized_query[] = $param."=".$value;
		}
		$canonicalized_query = implode("&", $canonicalized_query);
		$string_to_sign = $host."\n".$canonicalized_query;
		$request = $host."?".$canonicalized_query;
		//die($request);
		$xml_response = $emulator->getUrl($request, "", $cookie);
		
		/* If cURL doesn't work for you, then use the 'file_get_contents'
		   function as given below.
		*/
	 
		if ($xml_response[0] === False)
		{
			return False;
		}
		else
		{
			return $xml_response[0];
		}
	}
	 
	public function getdatadd_drpp_dt($user)
	{
		$parameters = array("user"=> $user,
										"format"        => "json");
		$json_response = $this->searchdata($parameters);
		return $json_response;
		
	}
	public function getdatadd_kuitansi($user)
	{
		$parameters = array("user"=> $user,
										"format"        => "json");
		$json_response = $this->searchdatakuitansi($parameters);
		return $json_response;
		
	}
	public function getdatadspm($user)
	{
		$parameters = array("user"=> $user,
										"format"        => "json");
		$json_response = $this->searchdataspm($parameters);
		return $json_response;
		
	}
	public function getdatadspmid($user)
	{
		$parameters = array("user"=> $user,
										"format"        => "json");
		$json_response = $this->searchdataspmid($parameters);
		return $json_response;
		
	}
	public function getdataditem($user)
	{
		$parameters = array("user"=> $user,
										"format"        => "json");
		$json_response = $this->searchdataitem($parameters);
		return $json_response;
		
	}
	public function getdatad_spm($user)
	{
		$parameters = array("user"=> $user,
										"format"        => "json");
		$json_response = $this->searchdatadspm($parameters);
		return $json_response;
		
	}
	
	public function getdatadasar($user,$module)
	{
		$parameters = array("user"=> $user,"mod"=> $module,
										"format"        => "json");
		$json_response = $this->searchdatadasar($parameters);
		return $json_response;
		
	}
	
}
?>