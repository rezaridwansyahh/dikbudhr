<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class API_PDDIKTI{

    function API_PDDIKTI()
	{
	//	die('fdsgsf');
	}	 

    public function get_data_pddikti($nip){
        $token = $this->get_token();
        $access_token = $token['access_token'];
        return $this->get_data($nip,$access_token);
        
    }

    public function get_token(){
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.kemdikbud.go.id:8243/token',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => 'grant_type=client_credentials',
        CURLOPT_HTTPHEADER => array(
            ': ',
            'Authorization: Basic RHFmMUtxRXhsbnhScEljMV9FWDk4QzdldEZBYTpVTkxFV2pKUW1sQ29WUkNPSEZrMDUzTWk0VGNh',
            'Content-Type: application/x-www-form-urlencoded'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response,true);
    }

    public function get_data($nip,$access_token){
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.kemdikbud.go.id:8243/pddikti/1.2/dosen?nip=$nip",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer $access_token",
            'Content-Type: application/x-www-form-urlencoded'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response,true);

    }


}

