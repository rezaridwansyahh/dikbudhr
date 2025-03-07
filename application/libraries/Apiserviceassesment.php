<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
 
class Apiserviceassesment
{
		var $user="userapi";
		var $secretkey="Data.12345";
		var $urlassesment = "http://118.98.230.91/hcdp/integrate";
	function Apiserviceassesment()
	{
	//	die('fdsgsf');
	}	 
	function  eventsearch($params,$url)
	{	 
		$ci =& get_instance();
		$ci->load->library('emulator');
		$emulator = new emu();
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
		
		$response = $emulator->getUrl($request, $this->token, $this->user,$this->secretkey);
		//print_r($response);
		//die($request);
		/* If cURL doesn't work for you, then use the 'file_get_contents'
		   function as given below.
		*/
		if ($response[0] === False)
		{
			return False;
		}
		else
		{
			return $response[0];
		}
	}
	private function searchassesment($parameters)
	{
		return $this->eventsearch($parameters,$this->urlassesment);
	}
	public function getdataassesment($nip)
	{
		$parameters = array("Username"=> $this->user,
						"password"=> $this->secretkey,
							"nip"=> $nip);
		$json_response = $this->searchassesment($parameters);
		return $json_response;
	}
	public function getdataassesmentTahun($year)
	{
		$parameters = array("Username"=> $this->user,
						"password"=> $this->secretkey,
							"year"=> $year);
		$json_response = $this->searchassesment($parameters);
		return $json_response;
	}
	 
}
?>