<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
 
class Api_digital
{
		var $user="demo";
		var $secretkey="demo@2019!!";

		var $usercert="democert2";
		var $passphrase="12345678";
		var $url_domain = "https://godam.govca.id/";
	function Api_digital()
	{
	//	die('fdsgsf');
	}	 
	
	public function getTokenBarier($username,$password){
		//session_start();
		$token = "";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$this->url_domain."login");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,
		            "username=".$username."&password=".$password);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
		// In real life you should use something like:
		// curl_setopt($ch, CURLOPT_POSTFIELDS, 
		//          http_build_query(array('postvar1' => 'value1')));
		// Receive server response ...
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		$err = curl_error($ch);
		curl_close($ch);
		//$return = json_decode($response);
		return $response;
	}
	public function getTokenLogin(){
		//session_start();
		$token = "";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$this->url_domain."login");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,
		            "username=".$this->user."&password=".$this->secretkey);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));

		// In real life you should use something like:
		// curl_setopt($ch, CURLOPT_POSTFIELDS, 
		//          http_build_query(array('postvar1' => 'value1')));

		// Receive server response ...
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$response = curl_exec($ch);
		$err = curl_error($ch);

		curl_close($ch);

		$return = json_decode($response);
		if($return->status == "200"){
			$token = $return->data->token;
		}else{
			print_r($return);
		}
		 
		return $token;
	}
	public function getTokenAkses($token){
		//session_start();
		$token2 = "";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$this->url_domain."getToken");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,
		            "username=".$this->usercert);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$token));
		//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:  multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW'));

		// In real life you should use something like:
		// curl_setopt($ch, CURLOPT_POSTFIELDS, 
		//          http_build_query(array('postvar1' => 'value1')));

		// Receive server response ...
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$response = curl_exec($ch);
		$err = curl_error($ch);

		curl_close($ch);

		 
		$return = json_decode($response);
		if($return->code == "200"){
			$token2s = explode(",",$return->message);
			$token2 = $token2s[0]; 
		}else{
			print_r($return);
		}
		return $token2;
	}
	private function getTokenAksesUserCert($token,$userCert){
		//session_start();
		$token2 = "";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$this->url_domain."getToken");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,
		            "username=".$userCert);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$token));
		 
		// Receive server response ...
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$response = curl_exec($ch);
		$err = curl_error($ch);

		curl_close($ch);
		$return = json_decode($response);
		return $return;
	}
	public function getListCert($token){
		//session_start();
		$cert = "";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$this->url_domain."listCert");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,
		            "username=".$this->usercert);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$token));
		//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:  multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW'));

		// In real life you should use something like:
		// curl_setopt($ch, CURLOPT_POSTFIELDS, 
		//          http_build_query(array('postvar1' => 'value1')));

		// Receive server response ...
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$response = curl_exec($ch);
		$err = curl_error($ch);

		curl_close($ch);

		 
		$return = json_decode($response);
		//print_r($return);
		if($return->code == "200"){
			$message = $return->message;
			$cert = $return->data[0]->id;
		} else{
			print_r($return);
		}
		 
		return $cert;
	}
	public function getListCert_login($token,$usercert){
		//session_start();
		$cert = "";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$this->url_domain."listCert");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,
		            "username=".$usercert);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$token));
		//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:  multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW'));

		// In real life you should use something like:
		// curl_setopt($ch, CURLOPT_POSTFIELDS, 
		//          http_build_query(array('postvar1' => 'value1')));

		// Receive server response ...
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$response = curl_exec($ch);
		$err = curl_error($ch);

		curl_close($ch);

		 
		$return = json_decode($response);
		
		return $return;
	}
	public function signPDF($token,$token2,$cert,$filepdf){
		//session_start();
		$base64 = "";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$this->url_domain."signPDF");
		curl_setopt($ch, CURLOPT_POST, 1);

		$file = new CURLFile($filepdf.'.pdf','application/pdf','pdffile');
		$params = array(
                'username' => $this->usercert,
                'urx' => '519',
                'ury' => '128',
                'imageSign' => 'field3text',
                'passphrase' => $this->passphrase,
                
                'pdf' => $file,
                'idkeystore' => $cert,
                'page' => '1',
                'llx' => '387',
                'lly' => "232",
                'reason' => "Tanda Tangan Digital",
                'location' => "Jakarta",
                'token' => $token2,

            );
		//print_r($params);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$params);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$token));

		//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:  multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW'));

		// In real life you should use something like:
		// curl_setopt($ch, CURLOPT_POSTFIELDS, 
		//          http_build_query(array('postvar1' => 'value1')));

		// Receive server response ...
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$response = curl_exec($ch);
		$err = curl_error($ch);

		curl_close($ch);

		 
		$return = json_decode($response);
		if($return->status == "200"){
			$message = $return->message;
			$base64 = $return->data;
		}else{
			print_r($return);
		}
		 
		return $base64;
	}
	public function sign_sk($token,$token2,$cert,$usercert,$passphrase,$filepdf){
		//session_start();
		$base64 = "";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$this->url_domain."signPDF");
		curl_setopt($ch, CURLOPT_POST, 1);

		$file = new CURLFile($filepdf.'.pdf','application/pdf','pdffile');
		$params = array(
                'username' => $usercert,
                'urx' => '519',
                'ury' => '128',
                'imageSign' => 'field3text',
                'passphrase' => $passphrase,
                
                'pdf' => $file,
                'idkeystore' => $cert,
                'page' => '1',
                'llx' => '387',
                'lly' => "232",
                'reason' => "Tanda Tangan Digital",
                'location' => "Jakarta",
                'token' => $token2,

            );
		//print_r($params);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$params);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$token));

		//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:  multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW'));

		// In real life you should use something like:
		// curl_setopt($ch, CURLOPT_POSTFIELDS, 
		//          http_build_query(array('postvar1' => 'value1')));

		// Receive server response ...
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$response = curl_exec($ch);
		$err = curl_error($ch);

		curl_close($ch);

		 
		$return = json_decode($response);
		return $return;
	}
	public function getbarier($username,$password)
	{
		$Token = $this->getTokenBarier($username,$password);
		 
		return $Token;
	}
	public function getTokenFromUserCert($barier,$usercert)
	{
		$status = false;
		$Token2 = "";
		$idCert = "";
		$message = "";

		if($barier !="" and $usercert !=""){
			$status = true;
			$ReturnToken2 = $this->getTokenAksesUserCert($barier,$usercert); // berlaku satu jam

			if($ReturnToken2->code == "200"){
				$token2s = explode(",",$ReturnToken2->message);
				$message = $ReturnToken2->message;
				$Token2 = $token2s[0]; 
			}else{
				$status = false;
				$message = "Token tidak ditemukan";
			}
		}
		 
		$response ['status']= $status;
        $response ['msg']= $message;
        $response ['token']= $Token2;
		return json_encode($response);
	}
	public function signsk($filepdf)
	{
		$Token = $this->getTokenLogin();
		$Token2 = $this->getTokenAkses($Token); // berlaku satu jam
		$idCert = $this->getListCert($Token);
		$base_64 = $this->signPDF($Token,$Token2,$idCert,$filepdf);
		return $base_64;
	}
	public function signsk_all($barier,$usercert,$passphrase,$filepdf,$token = "")
	{
		$status = false;
		$Token2 = "";
		$idCert = "";
		$message = "";
		$base64 = "";

		if($barier !="" and $usercert !="" and $token == ""){
			$status = true;
			$ReturnToken2 = $this->getTokenAksesUserCert($barier,$usercert); // berlaku satu jam

			if($ReturnToken2->code == "200"){
				$token2s = explode(",",$ReturnToken2->message);
				$Token2 = $token2s[0]; 
			}else{
				$status = false;
				$message = "Token tidak ditemukan";
			}
		}
		if($token != ""){
			$Token2 = $token;
		}
		$returnidCert = $this->getListCert_login($barier,$usercert);

		if($returnidCert->code == "200"){
			$status = true;
			$message = $returnidCert->message;
			$idCert = $returnidCert->data[0]->id;
		} else{
			$status = false;
			$message = $returnidCert->message;
		}
		if($barier != "" && $Token2 != "" && $idCert != "" && $usercert != "" && $passphrase != "" && $filepdf != ""){
			$status = true;
			$return = $this->sign_sk($barier,$Token2,$idCert,$usercert,$passphrase,$filepdf);	
			
			//print_r($return);
			if($return->status == "200"){
				$message = $return->message;
				$base64 = $return->data;
			}else{
				$status = false;
				$message = $return->message;
			}
		}else{
			$status = false;
			if($barier == "")
				$message = "Lengkapi data Barier";
			if($Token2 == "")
				$message = "Lengkapi data Token";
			if($idCert == "")
				$message = "idCert Tidak ditemukan";
			if($usercert == "")
				$message = "Lengkapi data usercert";
			if($passphrase == "")
				$message = "Lengkapi data passphrase";
			if($filepdf == "")
				$message = "Lengkapi data filepdf";
		}
			
		$response ['status']= $status;
        $response ['msg']= $message;
        $response ['base_64']= $base64;
		return json_encode($response);
	}
	
	 
}
?>