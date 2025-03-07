<?php 

// Note: the GenericProvider requires the `urlAuthorize` option, even though
// it's not used in the OAuth 2.0 client credentials grant type.
include "vendor/autoload.php";

class Api_skp {
	public function __construct(){

		 
	}
	public function getnilai_skp($nip = "",$tahun = ""){		
		$base_url = "http://skp.sdm.kemdikbud.go.id/skp/";
		try {
			
			$curl = curl_init();

			curl_setopt_array($curl, array(
			  CURLOPT_URL => $base_url."api/nilai_prestasi_tahun/{$nip}/".$tahun,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",
			  CURLOPT_HTTPHEADER => array(
			    "Authorization: Basic Ymlyb3VtdW06Qmlyb1VtdW0xMjM=",
			    "Cache-Control: no-cache",
			    "Postman-Token: c745e9ec-3490-a2df-3273-73a8d237c30f"
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
				return $err;
			}  
		return $response;
		curl_close($curl);
		}
		 catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
			// Failed to get the access token
			exit($e->getMessage());
		}
	}
	 
	 
}


