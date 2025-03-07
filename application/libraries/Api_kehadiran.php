<?php 

// Note: the GenericProvider requires the `urlAuthorize` option, even though
// it's not used in the OAuth 2.0 client credentials grant type.
include "vendor/autoload.php";

class Api_kehadiran {
	public function __construct(){

		 
	}
	public function getToken(){
		//session_start();
		// if(!isset($_SESSION['accessToken'])){
		$field = array('username' => 'service', 'password' => 'service@@123');

			$curl = curl_init();
				  curl_setopt_array($curl, array(
				  CURLOPT_URL => "http://kehadiran.sdm.kemdikbud.go.id/api/auth",
				  CURLOPT_RETURNTRANSFER => true,
				  CURLOPT_ENCODING => "",
				  CURLOPT_MAXREDIRS => 10,
				  CURLOPT_TIMEOUT => 30,
				  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				  CURLOPT_CUSTOMREQUEST => "POST",
				  CURLOPT_POSTFIELDS => "username=service&password=service%40%40123",
				  CURLOPT_HTTPHEADER => array(
    					"Cache-Control: no-cache",
					    "Content-Type: application/x-www-form-urlencoded",
					    "Postman-Token: 78032c44-c88a-9a3b-9858-b6a518897816"
					  ),
				));

				$accessToken = curl_exec($curl);
				$accessToken = json_decode($accessToken);
				$err = curl_error($curl);
				curl_close($curl);
				$_SESSION['accessToken'] = $accessToken;
		// }else{
		// 	$accessToken = $_SESSION['accessToken'];
		// }
		return $accessToken->token;
	}
	 
	public function getabsen($nip,$start_date = "",$end_date = ""){		

		try {
			$accessToken = $this->getToken();
			$fields = array('token' => $accessToken,'userid' => $nip, 'start_date' => $start_date, 'end_date' => $end_date, 'limit' => 10);
			$fields_string = "";
			foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
			rtrim($fields_string, '&');
			
			$curl = curl_init();
			//echo $fields_string;
		curl_setopt_array($curl, array(
			CURLOPT_URL => "http://kehadiran.sdm.kemdikbud.go.id/api/service/getattendance",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => $fields_string,
			//CURLOPT_POSTFIELDS => "token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6InNlcnZpY2UifQ.SBexMWrfbaj2EX1hS8IyOzwrWACFPO2xZbA54JcqOp8&userid=198608302010121005&start_date=2019-01-01&end_date=2019-01-25&limit=10",
			CURLOPT_HTTPHEADER => array(
			"Cache-Control: no-cache",
			"Content-Type: application/x-www-form-urlencoded",
			"Postman-Token: 68b5f8ec-d22a-be98-37ac-ce6f0f0fd3b4"
			),
		));


		$response = curl_exec($curl);
		
		$err = curl_error($curl);
		return $response;
		curl_close($curl);
		}
		 catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
			// Failed to get the access token
			exit($e->getMessage());
		}
	}
	public function sendabsen($nip,$tanggal = "",$tanggal_out = "",$check_in = "",$check_out = "",$terlambat = "0",$pulang_cepat = "0",$ot_before = "0",$ot_after = "0",$workinonholiday = "0",$status_hadir = 'AT_WFH'){		
		if($tanggal_out == ""){
			$tanggal_out = $tanggal;
		}
		try {
			$accessToken = $this->getToken();
			$fields = array(
				'token' => $accessToken,
				'userid' => $nip, 
				'tanggal_shift' => $tanggal, 
				'shift_in' => '07:30:00', 
				'shift_out' => '16:30:00', 
				'tanggal_in' => $tanggal, 
				'check_in' => $check_in, 
				'check_in' => $check_in, 
				'tanggal_out' => $tanggal_out, 
				'check_out' => $check_out, 
				'terlambat' 		=> $terlambat, 
				'pulang_cepat' 		=> $pulang_cepat, 
				'ot_before' 		=> $ot_before, 
				'ot_after' 			=> $ot_after, 
				'workinonholiday' 	=> $workinonholiday, 
				'status_hadir' 		=> $status_hadir  
			);
			$fields_string = "";
			foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
			rtrim($fields_string, '&');
			
			$curl = curl_init();
			//echo $fields_string;
			curl_setopt_array($curl, array(
			CURLOPT_URL => "http://kehadiran.sdm.kemdikbud.go.id/api/service/sendattendance",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => $fields_string,
			//CURLOPT_POSTFIELDS => "token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6InNlcnZpY2UifQ.SBexMWrfbaj2EX1hS8IyOzwrWACFPO2xZbA54JcqOp8&userid=198608302010121005&start_date=2019-01-01&end_date=2019-01-25&limit=10",
			CURLOPT_HTTPHEADER => array(
			"Cache-Control: no-cache",
			"Content-Type: application/x-www-form-urlencoded",
			"Postman-Token: 68b5f8ec-d22a-be98-37ac-ce6f0f0fd3b4"
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);
		return $response;
		curl_close($curl);
		}
		 catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
			// Failed to get the access token
			exit($e->getMessage());
		}
	}
	public function del_absen($nip,$start_date = "",$end_date = ""){		

		try {
			$accessToken = $this->getToken();
			$fields = array(
				'token' => $accessToken,
				'userid' => $nip, 
				'start_date' => $start_date, 
				'end_date' => $end_date
			);
			$fields_string = "";
			foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
			rtrim($fields_string, '&');
			
			$curl = curl_init();
			//echo $fields_string;
		curl_setopt_array($curl, array(
			CURLOPT_URL => "http://kehadiran.sdm.kemdikbud.go.id/api/service/deletetendance",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => $fields_string,
			//CURLOPT_POSTFIELDS => "token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6InNlcnZpY2UifQ.SBexMWrfbaj2EX1hS8IyOzwrWACFPO2xZbA54JcqOp8&userid=198608302010121005&start_date=2019-01-01&end_date=2019-01-25&limit=10",
			CURLOPT_HTTPHEADER => array(
			"Cache-Control: no-cache",
			"Content-Type: application/x-www-form-urlencoded",
			"Postman-Token: 68b5f8ec-d22a-be98-37ac-ce6f0f0fd3b4"
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);
		return $response;
		curl_close($curl);
		}
		 catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
			// Failed to get the access token
			exit($e->getMessage());
		}
	}
	// kirim log absen
	public function get_log_absen($nip,$start_date = "",$end_date = ""){		

		try {
			$accessToken = $this->getToken();
			$fields = array('token' => $accessToken,'userid' => $nip, 'start_date' => $start_date, 'end_date' => $end_date, 'limit' => 30);
			$fields_string = "";
			foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
			rtrim($fields_string, '&');
			
			$curl = curl_init();
			//echo $fields_string;
		curl_setopt_array($curl, array(
			CURLOPT_URL => "http://kehadiran.sdm.kemdikbud.go.id/api/service/getlog",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => $fields_string,
			//CURLOPT_POSTFIELDS => "token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6InNlcnZpY2UifQ.SBexMWrfbaj2EX1hS8IyOzwrWACFPO2xZbA54JcqOp8&userid=198608302010121005&start_date=2019-01-01&end_date=2019-01-25&limit=10",
			CURLOPT_HTTPHEADER => array(
			"Cache-Control: no-cache",
			"Content-Type: application/x-www-form-urlencoded",
			"Postman-Token: 68b5f8ec-d22a-be98-37ac-ce6f0f0fd3b4"
			),
		));


		$response = curl_exec($curl);
		return $response;
		$err = curl_error($curl);

		curl_close($curl);
		}
		 catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
			// Failed to get the access token
			exit($e->getMessage());
		}
	}
	public function send_log_absen($nip,$tanggal = "",$jam = "",$jenis = "0"){		

		try {
			$accessToken = $this->getToken();
			$fields = array(
				'token' => $accessToken,
				'userid' => $nip, 
				'tanggal' => $tanggal, 
				'jam' => $jam, 
				'jenis' => $jenis,
				'sn_mesin' => "-"
			);
			$fields_string = "";
			foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
			rtrim($fields_string, '&');
			
			$curl = curl_init();
			//echo $fields_string;
		curl_setopt_array($curl, array(
			CURLOPT_URL => "http://kehadiran.sdm.kemdikbud.go.id/api/service/sendlog",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => $fields_string,
			//CURLOPT_POSTFIELDS => "token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6InNlcnZpY2UifQ.SBexMWrfbaj2EX1hS8IyOzwrWACFPO2xZbA54JcqOp8&userid=198608302010121005&start_date=2019-01-01&end_date=2019-01-25&limit=10",
			CURLOPT_HTTPHEADER => array(
			"Cache-Control: no-cache",
			"Content-Type: application/x-www-form-urlencoded",
			"Postman-Token: 68b5f8ec-d22a-be98-37ac-ce6f0f0fd3b4"
			),
		));
		$response = curl_exec($curl);
		//print_r($response);
		$err = curl_error($curl);
		return $response;
		curl_close($curl);
		}
		 catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
			// Failed to get the access token
			exit($e->getMessage());
		}
	}
	public function getHarilibur($start_date = "",$end_date = ""){		

		try {
			$accessToken = $this->getToken();
			$fields = array('token' => $accessToken, 'start_date' => $start_date, 'end_date' => $end_date);
			$fields_string = "";
			foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
			rtrim($fields_string, '&');
			
			$curl = curl_init();
			//echo $fields_string;
		curl_setopt_array($curl, array(
			CURLOPT_URL => "http://kehadiran.sdm.kemdikbud.go.id/api/service/getholiday",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => $fields_string,
			//CURLOPT_POSTFIELDS => "token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6InNlcnZpY2UifQ.SBexMWrfbaj2EX1hS8IyOzwrWACFPO2xZbA54JcqOp8&userid=198608302010121005&start_date=2019-01-01&end_date=2019-01-25&limit=10",
			CURLOPT_HTTPHEADER => array(
			"Cache-Control: no-cache",
			"Content-Type: application/x-www-form-urlencoded",
			"Postman-Token: 68b5f8ec-d22a-be98-37ac-ce6f0f0fd3b4"
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);
		return $response;
		curl_close($curl);
		}
		 catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
		 	return $e;
			// Failed to get the access token
			exit($e->getMessage());
		}
	}
	 
}


