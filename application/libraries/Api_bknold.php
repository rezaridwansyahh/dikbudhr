<?php 

// Note: the GenericProvider requires the `urlAuthorize` option, even though
// it's not used in the OAuth 2.0 client credentials grant type.
include "vendor/autoload.php";

class Api_bkn {
	public function __construct(){

		$this->provider = new \League\OAuth2\Client\Provider\GenericProvider([
			'clientId'                => 'bkntraining',    // The client ID assigned to you by the provider
			'clientSecret'            => 'd1kbudp4ssw0rd_1',    // The client password assigned to you by the provider
			//'redirectUri'             => 'http://my.example.com/your-redirect-url/',
			'urlAuthorize'            => '',
			'urlAccessToken'          => 'https://wstraining.bkn.go.id/oauth/token',
			'urlResourceOwnerDetails' => ''
		]);
	}
	public function getToken(){
		//unset($_SESSION['accessToken']);
		//session_start();
		if(!isset($_SESSION['accessToken'])){
			$curl = curl_init();
				  curl_setopt_array($curl, array(
				  CURLOPT_URL => "https://wsrv-auth.bkn.go.id/oauth/token",
				  CURLOPT_RETURNTRANSFER => true,
				  CURLOPT_ENCODING => "",
				  CURLOPT_MAXREDIRS => 10,
				  CURLOPT_TIMEOUT => 60,
				  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				  CURLOPT_CUSTOMREQUEST => "POST",
				  CURLOPT_POSTFIELDS => "client_id=sdmdikbudclient&grant_type=client_credentials",
				  // CURLOPT_SSL_CIPHER_LIST => "CDHE-RSA-AES256-GCM-SHA384",
				  // CURLOPT_TLS13_CIPHERS => "TLS_AES_256_GCM_SHA384:TLS_CHACHA20_POLY1305_SHA256:TLS_AES_128_GCM_SHA256:TLS_AES_128_CCM_8_SHA256:TLS_AES_128_CCM_SHA256",
				  CURLOPT_SSLVERSION => "CURL_SSLVERSION_TLSv1_3",
				  CURLOPT_HTTPHEADER => array(
				    "Cache-Control: no-cache",
				    "Content-Type: application/x-www-form-urlencoded",
				    "X-Forwarded-For: 118.98.234.198",
				    "Postman-Token: 899aed95-7547-3ab8-91b8-302857d79143"
				  ),
				));

				$accessToken = curl_exec($curl);
				$accessToken = json_decode($accessToken);
				$err = curl_error($curl);
				print_r($err);
				// curl_close($curl);
				$_SESSION['accessToken'] = $accessToken;
		}else{
			$accessToken = $_SESSION['accessToken'];
		}
		return $accessToken->access_token;
	}
	public function getAccessToken(){
		 
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://wsrv-auth.bkn.go.id/oauth/token",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => "client_id=sdmdikbudclient&grant_type=client_credentials",
		  CURLOPT_HTTPHEADER => array(
		    "Cache-Control: no-cache",
		    "Content-Type: application/x-www-form-urlencoded",
		    "X-Forwarded-For: 118.98.234.198",
		    "Postman-Token: 899aed95-7547-3ab8-91b8-302857d79143"
		  ),
		));

		$accessToken = curl_exec($curl);
		$accessToken = json_decode($accessToken);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  return "";
		} else {
		  return $accessToken->access_token;
		}
		
	}
	public function getData($module,$code){		
		try {
			$accessToken = $this->getToken();
			$curl = curl_init();

			curl_setopt_array($curl, array(
			  CURLOPT_URL => "https://wsrv.bkn.go.id/api/pns/".$module."/".$code,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",
			  CURLOPT_HTTPHEADER => array(
			    "Authorization: Bearer ".$accessToken,
			    "Cache-Control: no-cache",
			    "origin: http://localhost:20000"
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);
			curl_close($curl);

			if ($err) {
			  return null;
			} else {
			  return json_encode($response);
			}
		}
		 catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
			// Failed to get the access token
			exit($e->getMessage());
		}
	}
	public function data_utama($code){		
		// try {
			$accessToken = $this->getToken();
			// echo $accessToken."\n";
			$curl = curl_init();

			curl_setopt_array($curl, array(
			  CURLOPT_URL => "https://wsrv.bkn.go.id/api/pns/data-utama/".$code,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",
			  CURLOPT_HTTPHEADER => array(
			    "Authorization: Bearer ".$accessToken,
			    "Cache-Control: no-cache",
			    "X-Forwarded-For: 118.98.234.198",
			    "origin: http://localhost:20000"
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
			  return null;
			} else {
			  return json_encode($response);
			}
		// }
		//  catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
		// 	// Failed to get the access token
		// 	exit($e->getMessage());
		// }
	}
	public function data_rwt_golongan_bkn($code){		
		try {
			$accessToken = $this->getToken();
			//die($accessToken);
			$curl = curl_init();
			//https://wstraining.bkn.go.id/bkn-resources-server/api/pns/rw- golongan/198505312008121001
			curl_setopt_array($curl, array(
			  CURLOPT_URL => "https://wsrv.bkn.go.id/api/pns/rw-golongan/".$code,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",
			  CURLOPT_HTTPHEADER => array(
			    "Authorization: Bearer ".$accessToken,
			    "Cache-Control: no-cache",
			    "X-Forwarded-For: 118.98.234.198",
			    "origin: http://localhost:20000"
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
			  return null;
			} else {
			  return json_encode($response);
			}
		}
		 catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
			// Failed to get the access token
			exit($e->getMessage());
		}
	}
	public function data_dp3($code,$accessToken = ""){		
		try {
			if($accessToken == "")
				$accessToken = $this->getToken();
			//die($accessToken);
			$curl = curl_init();
			//https://wstraining.bkn.go.id/bkn-resources-server/api/pns/rw- golongan/198505312008121001
			curl_setopt_array($curl, array(
			  CURLOPT_URL => "https://wsrv.bkn.go.id/api/pns/rw-dp3/".$code,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",
			  CURLOPT_HTTPHEADER => array(
			    "Authorization: Bearer ".$accessToken,
			    "Cache-Control: no-cache",
			    "X-Forwarded-For: 118.98.234.198",
			    "origin: http://localhost:20000"
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
			  return null;
			} else {
			  return json_encode($response);
			}
		}
		 catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
			// Failed to get the access token
			exit($e->getMessage());
		}
	}
	public function get_data_pasangan($code,$accessToken = ""){		
		try {
			if($accessToken == "")
				$accessToken = $this->getToken();
			//die($accessToken);
			$curl = curl_init();
			//https://wstraining.bkn.go.id/bkn-resources-server/api/pns/rw- golongan/198505312008121001
			curl_setopt_array($curl, array(
			  CURLOPT_URL => "https://wsrv.bkn.go.id/api/pns/data-pasangan/".$code,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",
			  CURLOPT_HTTPHEADER => array(
			    "Authorization: Bearer ".$accessToken,
			    "Cache-Control: no-cache",
			    "X-Forwarded-For: 118.98.234.198",
			    "origin: http://localhost:20000"
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
			  return null;
			} else {
			  return json_encode($response);
			}
		}
		 catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
			// Failed to get the access token
			exit($e->getMessage());
		}
	}
	public function get_data_ortu($code,$accessToken = ""){		
		try {
			if($accessToken == "")
				$accessToken = $this->getToken();
			//die($accessToken);
			$curl = curl_init();
			//https://wstraining.bkn.go.id/bkn-resources-server/api/pns/rw- golongan/198505312008121001
			curl_setopt_array($curl, array(
			  CURLOPT_URL => "https://wsrv.bkn.go.id/api/pns/data-ortu/".$code,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",
			  CURLOPT_HTTPHEADER => array(
			    "Authorization: Bearer ".$accessToken,
			    "Cache-Control: no-cache",
			    "X-Forwarded-For: 118.98.234.198",
			    "origin: http://localhost:20000"
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
			  return null;
			} else {
			  return json_encode($response);
			}
		}
		 catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
			// Failed to get the access token
			exit($e->getMessage());
		}
	}
	public function get_data_anak($code,$accessToken = ""){		
		try {
			if($accessToken == "")
				$accessToken = $this->getToken();
			//die($accessToken);
			$curl = curl_init();
			//https://wstraining.bkn.go.id/bkn-resources-server/api/pns/rw- golongan/198505312008121001
			curl_setopt_array($curl, array(
			  CURLOPT_URL => "https://wsrv.bkn.go.id/api/pns/data-anak/".$code,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",
			  CURLOPT_HTTPHEADER => array(
			    "Authorization: Bearer ".$accessToken,
			    "Cache-Control: no-cache",
			    "X-Forwarded-For: 118.98.234.198",
			    "origin: http://localhost:20000"
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
			  return null;
			} else {
			  return json_encode($response);
			}
		}
		 catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
			// Failed to get the access token
			exit($e->getMessage());
		}
	}
	public function get_data_kursus($code,$accessToken = ""){		
		try {
			if($accessToken == "")
				$accessToken = $this->getToken();
			//die($accessToken);
			$curl = curl_init();
			//https://wstraining.bkn.go.id/bkn-resources-server/api/pns/rw- golongan/198505312008121001
			curl_setopt_array($curl, array(
			  CURLOPT_URL => "https://wsrv.bkn.go.id/api/pns/rw-kursus/".$code,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",
			  CURLOPT_HTTPHEADER => array(
			    "Authorization: Bearer ".$accessToken,
			    "Cache-Control: no-cache",
			    "X-Forwarded-For: 118.98.234.198",
			    "origin: http://localhost:20000"
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
			  return null;
			} else {
			  return json_encode($response);
			}
		}
		 catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
			// Failed to get the access token
			exit($e->getMessage());
		}
	}
	public function get_data_penghargaan($code,$accessToken = ""){		
		try {
			if($accessToken == "")
				$accessToken = $this->getToken();
			//die($accessToken);
			$curl = curl_init();
			//https://wstraining.bkn.go.id/bkn-resources-server/api/pns/rw- golongan/198505312008121001
			curl_setopt_array($curl, array(
			  CURLOPT_URL => "https://wsrv.bkn.go.id/api/pns/rw-penghargaan/".$code,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",
			  CURLOPT_HTTPHEADER => array(
			    "Authorization: Bearer ".$accessToken,
			    "Cache-Control: no-cache",
			    "X-Forwarded-For: 118.98.234.198",
			    "origin: http://localhost:20000"
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
			  return null;
			} else {
			  return json_encode($response);
			}
		}
		 catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
			// Failed to get the access token
			exit($e->getMessage());
		}
	}
	public function get_data_pindah_instansi($code,$accessToken = ""){		
		try {
			if($accessToken == "")
				$accessToken = $this->getToken();
			//die($accessToken);
			$curl = curl_init();
			//https://wstraining.bkn.go.id/bkn-resources-server/api/pns/rw- golongan/198505312008121001
			curl_setopt_array($curl, array(
			  CURLOPT_URL => "https://wsrv.bkn.go.id/api/pns/rw-pindahinstansi/".$code,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",
			  CURLOPT_HTTPHEADER => array(
			    "Authorization: Bearer ".$accessToken,
			    "Cache-Control: no-cache",
			    "X-Forwarded-For: 118.98.234.198",
			    "origin: http://localhost:20000"
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
			  return null;
			} else {
			  return json_encode($response);
			}
		}
		 catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
			// Failed to get the access token
			exit($e->getMessage());
		}
	}
	public function get_data_rwt_skp($code,$accessToken = ""){		
		try {
			if($accessToken == "")
				$accessToken = $this->getToken();
			//die($accessToken);
			$curl = curl_init();
			//https://wstraining.bkn.go.id/bkn-resources-server/api/pns/rw- golongan/198505312008121001
			curl_setopt_array($curl, array(
			  CURLOPT_URL => "https://wsrv.bkn.go.id/api/pns/rw-skp/".$code,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",
			  CURLOPT_HTTPHEADER => array(
			    "Authorization: Bearer ".$accessToken,
			    "Cache-Control: no-cache",
			    "X-Forwarded-For: 118.98.234.198",
			    "origin: http://localhost:20000"
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
			  return null;
			} else {
			  return json_encode($response);
			}
		}
		 catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
			// Failed to get the access token
			exit($e->getMessage());
		}
	}
	public function get_data_rwt_cltn($code,$accessToken = ""){		
		try {
			if($accessToken == "")
				$accessToken = $this->getToken();
			//die($accessToken);
			$curl = curl_init();
			curl_setopt_array($curl, array(
			  CURLOPT_URL => "https://wsrv.bkn.go.id/api/pns/rw-cltn/".$code,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",
			  CURLOPT_HTTPHEADER => array(
			    "Authorization: Bearer ".$accessToken,
			    "Cache-Control: no-cache",
			    "X-Forwarded-For: 118.98.234.198",
			    "origin: http://localhost:20000"
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
			  return null;
			} else {
			  return json_encode($response);
			}
		}
		 catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
			// Failed to get the access token
			exit($e->getMessage());
		}
	}
	public function get_data_rwt_pemberhentian($code,$accessToken = ""){		
		try {
			if($accessToken == "")
				$accessToken = $this->getToken();
			//die($accessToken);
			$curl = curl_init();
			curl_setopt_array($curl, array(
			  CURLOPT_URL => "https://wsrv.bkn.go.id/api/pns/rw-pemberhentian/".$code,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",
			  CURLOPT_HTTPHEADER => array(
			    "Authorization: Bearer ".$accessToken,
			    "Cache-Control: no-cache",
			    "X-Forwarded-For: 118.98.234.198",
			    "origin: http://localhost:20000"
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
			  return null;
			} else {
			  return json_encode($response);
			}
		}
		 catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
			// Failed to get the access token
			exit($e->getMessage());
		}
	}
	public function get_data_rwt_ak($code,$accessToken = ""){		
		try {
			if($accessToken == "")
				$accessToken = $this->getToken();
			//die($accessToken);
			$curl = curl_init();
			curl_setopt_array($curl, array(
			  CURLOPT_URL => "https://wsrv.bkn.go.id/api/pns/rw-angkakredit/".$code,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",
			  CURLOPT_HTTPHEADER => array(
			    "Authorization: Bearer ".$accessToken,
			    "Cache-Control: no-cache",
			    "X-Forwarded-For: 118.98.234.198",
			    "origin: http://localhost:20000"
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
			  return null;
			} else {
			  return json_encode($response);
			}
		}
		 catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
			// Failed to get the access token
			exit($e->getMessage());
		}
	}
	public function get_data_rwt_pnsunor($code,$accessToken = ""){		
		try {
			if($accessToken == "")
				$accessToken = $this->getToken();
			//die($accessToken);
			$curl = curl_init();
			curl_setopt_array($curl, array(
			  CURLOPT_URL => "https://wsrv.bkn.go.id/api/pns/rw-pnsunor/".$code,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",
			  CURLOPT_HTTPHEADER => array(
			    "Authorization: Bearer ".$accessToken,
			    "Cache-Control: no-cache",
			    "X-Forwarded-For: 118.98.234.198",
			    "origin: http://localhost:20000"
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
			  return null;
			} else {
			  return json_encode($response);
			}
		}
		 catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
			// Failed to get the access token
			exit($e->getMessage());
		}
	}
	public function get_data_rwt_hukdis($code,$accessToken = ""){		
		try {
			if($accessToken == "")
				$accessToken = $this->getToken();
			//die($accessToken);
			$curl = curl_init();
			curl_setopt_array($curl, array(
			  CURLOPT_URL => "https://wsrv.bkn.go.id/api/pns/rw-hukdis/".$code,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",
			  CURLOPT_HTTPHEADER => array(
			    "Authorization: Bearer ".$accessToken,
			    "Cache-Control: no-cache",
			    "X-Forwarded-For: 118.98.234.198",
			    "origin: http://localhost:20000"
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
			  return null;
			} else {
			  return json_encode($response);
			}
		}
		 catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
			// Failed to get the access token
			exit($e->getMessage());
		}
	}
	public function get_data_kpo_sk($code,$accessToken = ""){		
		try {
			if($accessToken == "")
				$accessToken = $this->getToken();
			//die($accessToken);
			$curl = curl_init();
			curl_setopt_array($curl, array(
			  CURLOPT_URL => "https://wsrv.bkn.go.id/api/kpo/sk/".$code,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",
			  CURLOPT_HTTPHEADER => array(
			    "Authorization: Bearer ".$accessToken,
			    "Cache-Control: no-cache",
			    "X-Forwarded-For: 118.98.234.198",
			    "origin: http://localhost:20000"
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
			  return null;
			} else {
			  return json_encode($response);
			}
		}
		 catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
			// Failed to get the access token
			exit($e->getMessage());
		}
	}
	public function get_data_kpo_sk_hist($code,$accessToken = ""){		
		try {
			if($accessToken == "")
				$accessToken = $this->getToken();
			//die($accessToken);
			$curl = curl_init();
			curl_setopt_array($curl, array(
			  CURLOPT_URL => "https://wsrv.bkn.go.id/api/kpo/sk/hist/".$code,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",
			  CURLOPT_HTTPHEADER => array(
			    "Authorization: Bearer ".$accessToken,
			    "Cache-Control: no-cache",
			    "X-Forwarded-For: 118.98.234.198",
			    "origin: http://localhost:20000"
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
			  return null;
			} else {
			  return json_encode($response);
			}
		}
		 catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
			// Failed to get the access token
			exit($e->getMessage());
		}
	}
	public function get_data_ppo_sk($code,$accessToken = ""){		
		try {
			if($accessToken == "")
				$accessToken = $this->getToken();
			//die($accessToken);
			$curl = curl_init();
			curl_setopt_array($curl, array(
			  CURLOPT_URL => "https://wsrv.bkn.go.id/api/ppo/sk/".$code,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",
			  CURLOPT_HTTPHEADER => array(
			    "Authorization: Bearer ".$accessToken,
			    "Cache-Control: no-cache",
			    "X-Forwarded-For: 118.98.234.198",
			    "origin: http://localhost:20000"
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
			  return null;
			} else {
			  return json_encode($response);
			}
		}
		 catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
			// Failed to get the access token
			exit($e->getMessage());
		}
	}
	public function get_data_ppo_sk_hist($code,$accessToken = ""){		
		try {
			if($accessToken == "")
				$accessToken = $this->getToken();
			//die($accessToken);
			$curl = curl_init();
			curl_setopt_array($curl, array(
			  CURLOPT_URL => "https://wsrv.bkn.go.id/api/ppo/sk/hist/".$code,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",
			  CURLOPT_HTTPHEADER => array(
			    "Authorization: Bearer ".$accessToken,
			    "Cache-Control: no-cache",
			    "X-Forwarded-For: 118.98.234.198",
			    "origin: http://localhost:20000"
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
			  return null;
			} else {
			  return json_encode($response);
			}
		}
		 catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
			// Failed to get the access token
			exit($e->getMessage());
		}
	}
	public function get_data_rwt_pendidikan($code,$accessToken = ""){		
		try {
			if($accessToken == "")
				$accessToken = $this->getToken();
			//die($accessToken);
			$curl = curl_init();
			curl_setopt_array($curl, array(
			  CURLOPT_URL => "https://wsrv.bkn.go.id/api/pns/rw-pendidikan/".$code,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",
			  CURLOPT_HTTPHEADER => array(
			    "Authorization: Bearer ".$accessToken,
			    "Cache-Control: no-cache",
			    "X-Forwarded-For: 118.98.234.198",
			    "origin: http://localhost:20000"
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
			  return null;
			} else {
			  return json_encode($response);
			}
		}
		 catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
			// Failed to get the access token
			exit($e->getMessage());
		}
	}
	public function get_data_rwt_jabatan($code,$accessToken = ""){		
		try {
			if($accessToken == "")
				$accessToken = $this->getToken();
			//die($accessToken);
			$curl = curl_init();
			curl_setopt_array($curl, array(
			  CURLOPT_URL => "https://wsrv.bkn.go.id/api/pns/rw-jabatan/".$code,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",
			  CURLOPT_HTTPHEADER => array(
			    "Authorization: Bearer ".$accessToken,
			    "Cache-Control: no-cache",
			    "X-Forwarded-For: 118.98.234.198",
			    "origin: http://localhost:20000"
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
			  return null;
			} else {
			  return json_encode($response);
			}
		}
		 catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
			// Failed to get the access token
			exit($e->getMessage());
		}
	}
	public function get_data_rwt_diklat($code,$accessToken = ""){		
		try {
			if($accessToken == "")
				$accessToken = $this->getToken();
			//die($accessToken);
			$curl = curl_init();
			curl_setopt_array($curl, array(
			  CURLOPT_URL => "https://wsrv.bkn.go.id/api/pns/rw-diklat/".$code,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",
			  CURLOPT_HTTPHEADER => array(
			    "Authorization: Bearer ".$accessToken,
			    "Cache-Control: no-cache",
			    "X-Forwarded-For: 118.98.234.198",
			    "origin: http://localhost:20000"
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
			  return null;
			} else {
			  return json_encode($response);
			}
		}
		 catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
			// Failed to get the access token
			exit($e->getMessage());
		}
	}
	public function get_data_rwt_masakerja($code,$accessToken = ""){		
		try {
			if($accessToken == "")
				$accessToken = $this->getToken();
			//die($accessToken);
			$curl = curl_init();
			curl_setopt_array($curl, array(
			  CURLOPT_URL => "https://wsrv.bkn.go.id/api/pns/rw-masakerja/".$code,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",
			  CURLOPT_HTTPHEADER => array(
			    "Authorization: Bearer ".$accessToken,
			    "Cache-Control: no-cache",
			    "X-Forwarded-For: 118.98.234.198",
			    "origin: http://localhost:20000"
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
			  return null;
			} else {
			  return json_encode($response);
			}
		}
		 catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
			// Failed to get the access token
			exit($e->getMessage());
		}
	}
	public function get_data_rwt_pwk($code,$accessToken = ""){		
		try {
			if($accessToken == "")
				$accessToken = $this->getToken();
			//die($accessToken);
			$curl = curl_init();
			curl_setopt_array($curl, array(
			  CURLOPT_URL => "https://wsrv.bkn.go.id/api/pns/rw-pwk/".$code,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",
			  CURLOPT_HTTPHEADER => array(
			    "Authorization: Bearer ".$accessToken,
			    "Cache-Control: no-cache",
			    "X-Forwarded-For: 118.98.234.198",
			    "origin: http://localhost:20000"
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
			  return null;
			} else {
			  return json_encode($response);
			}
		}
		 catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
			// Failed to get the access token
			exit($e->getMessage());
		}
	}
	public function get_data_usul_wafat($code,$accessToken = ""){		
		try {
			if($accessToken == "")
				$accessToken = $this->getToken();
			//die($accessToken);
			$curl = curl_init();
			curl_setopt_array($curl, array(
			  CURLOPT_URL => "https://wsrv.bkn.go.id/api/ppo/usul/wafat/".$code,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",
			  CURLOPT_HTTPHEADER => array(
			    "Authorization: Bearer ".$accessToken,
			    "Cache-Control: no-cache",
			    "X-Forwarded-For: 118.98.234.198",
			    "origin: http://localhost:20000"
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
			  return null;
			} else {
			  return json_encode($response);
			}
		}
		 catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
			// Failed to get the access token
			exit($e->getMessage());
		}
	}
	public function get_data_usul_wafat_hist($code,$accessToken = ""){		
		try {
			if($accessToken == "")
				$accessToken = $this->getToken();
			//die($accessToken);
			$curl = curl_init();
			curl_setopt_array($curl, array(
			  CURLOPT_URL => "https://wsrv.bkn.go.id/api/ppo/usul/wafat/hist/".$code,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",
			  CURLOPT_HTTPHEADER => array(
			    "Authorization: Bearer ".$accessToken,
			    "Cache-Control: no-cache",
			    "X-Forwarded-For: 118.98.234.198",
			    "origin: http://localhost:20000"
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
			  return null;
			} else {
			  return json_encode($response);
			}
		}
		 catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
			// Failed to get the access token
			exit($e->getMessage());
		}
	}
	public function get_data_update_pns($code,$accessToken = ""){		
		try {
			if($accessToken == "")
				$accessToken = $this->getToken();
			//die($accessToken);
			$curl = curl_init();
			curl_setopt_array($curl, array(
			  CURLOPT_URL => "https://wsrv.bkn.go.id/api/updated/pns/".$code,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",
			  CURLOPT_HTTPHEADER => array(
			    "Authorization: Bearer ".$accessToken,
			    "Cache-Control: no-cache",
			    "X-Forwarded-For: 118.98.234.198",
			    "origin: http://localhost:20000"
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
			  return null;
			} else {
			  return json_encode($response);
			}
		}
		 catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
			// Failed to get the access token
			exit($e->getMessage());
		}
	}
	public function get_data_update_pns_hist($code,$accessToken = ""){		
		try {
			if($accessToken == "")
				$accessToken = $this->getToken();
			//die($accessToken);
			$curl = curl_init();
			curl_setopt_array($curl, array(
			  CURLOPT_URL => "https://wsrv.bkn.go.id/api/updated/hist/".$code,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",
			  CURLOPT_HTTPHEADER => array(
			    "Authorization: Bearer ".$accessToken,
			    "Cache-Control: no-cache",
			    "X-Forwarded-For: 118.98.234.198",
			    "origin: http://localhost:20000"
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
			  return null;
			} else {
			  return json_encode($response);
			}
		}
		 catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
			// Failed to get the access token
			exit($e->getMessage());
		}
	}
}