<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
 
class Api_digital_bssn
{
		var $usercert="30042019"; // nik
		var $passphrase="1234qwer";
		var $url_domain = "http://118.98.229.142/";
	function Api_digital_bssn()
	{
	//	die('fdsgsf');
	}	 
	
	public function signpdf($nik,$pass,$filepdf){
		//session_start();
		$base64 = "";
		$ch = curl_init();
		$params = array(
            'nik' => $nik,
            'passphrase' => $pass,
            'tampilan' => 'invisible',
            'page' => '1',
            'image' => 'true',
            'xAxis' => '43.63',
            'yAxis' => '28.71',
            'width' => '550.78',
            'height' => '130.88',            
            'reason' => 'ditandatangani secara elektronik BOSDM',
            'location' => 'Pusdatin' 
            
        );

        $canonicalized_query = array();
		foreach ($params as $param=>$value)
		{
			$param = str_replace("%7E", "~", rawurlencode($param));
			$value = str_replace("%7E", "~", $value);
			$canonicalized_query[] = $param."=".$value;
		}
		$canonicalized_query = implode("&", $canonicalized_query);
		$request = $this->url_domain."api/sign/pdf"."?".$canonicalized_query;
		//$request = $this->url_domain."api/sign/pdf?nik=30042019&passphrase=1234qwer&tampilan=invisible&page=1&image=true&xAxis=43.63&yAxis=28.71&width=550.78&height=130.88&reason=Dokumen ditandatangani secara elektronik BOSDM&location=pusdatin";
		//$request = $this->url_domain."api/sign/pdf?nik=30042019&passphrase=1234qwer&tampilan=invisible&page=1&image=true&xAxis=43.63&yAxis=28.71&width=550.78&height=130.88&reason=ditandatangani secara elektronik BOSDM&location=Pusdatin";
		curl_setopt($ch, CURLOPT_URL,$request);
		curl_setopt($ch, CURLOPT_POST, 1);

		$file = new CURLFile($filepdf.'.pdf','application/pdf','pdffile');
		
        $field = array(
            'file' => $file,
            'imageTTD' => ""
        );

		//print_r($params);
		curl_setopt($ch, CURLOPT_HEADER, true);

		curl_setopt($ch, CURLOPT_POSTFIELDS,$field);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$token));

		//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:  multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW'));

		// In real life you should use something like:
		// curl_setopt($ch, CURLOPT_POSTFIELDS, 
		//          http_build_query(array('postvar1' => 'value1')));

		// Receive server response ...
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$response = curl_exec($ch);
		$err = curl_error($ch);

		// extract header
            $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $header = substr($body, 0, $headerSize);
            $header = getHeaders($header);
            
		curl_close($ch);
		$return = json_decode($response);
		return $return;
	}
	
	 
}
?>