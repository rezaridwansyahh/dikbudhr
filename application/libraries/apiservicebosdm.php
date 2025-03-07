<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
 
class Apiservicebosdm
{
		var $user="198707132009122003";
		var $secretkey="2003";
		var $urlgetdatapegawai ="https://bosdm.lipi.go.id/api/index.php/hris/pegawai/list";
		var $token = "170097934ebc8361daa42df0922eadd9";
	function Apiservicesas()
	{
	//	die('fdsgsf');
	}
 
 	private function searchdatasdm($parameters)
	{
		return $this->eventsearch($parameters,$this->urlgetdatapegawai);
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
	public function getdatapegawai($start,$limit)
	{
		$parameters = array("Username"=> $this->user,
						"password"=> $this->secretkey,
							"X-API-KEY"=> $this->token,
							"start"=> $start,
							"limit"=> $limit);
		$json_response = $this->searchdatasdm($parameters);
		return $json_response;
	}
	 
}
?>