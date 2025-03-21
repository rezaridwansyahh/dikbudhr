<?php
require "vendor/autoload.php";
class Api_bkn
{

    // $ssoToken ="eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICJBUWNPM0V3MVBmQV9MQ0FtY2J6YnRLUEhtcWhLS1dRbnZ1VDl0RUs3akc4In0.eyJleHAiOjE3MzE5NTUzODQsImlhdCI6MTczMTkxMjE4NCwianRpIjoiZmNkYWIzZWMtYmU5ZC00NDdhLTk2NGEtOWQ2MTc0ZWVhZmZjIiwiaXNzIjoiaHR0cHM6Ly9zc28tc2lhc24uYmtuLmdvLmlkL2F1dGgvcmVhbG1zL3B1YmxpYy1zaWFzbiIsImF1ZCI6ImFjY291bnQiLCJzdWIiOiI5ZTZiZDg5NC01NzNmLTRiOWEtYmNmYS04M2NkYzRhNGNiNTciLCJ0eXAiOiJCZWFyZXIiLCJhenAiOiJzZG1kaWtidWRjbGllbnQiLCJzZXNzaW9uX3N0YXRlIjoiMzkzNjI1ZTUtZTc2Yy00N2FjLWJhNTEtN2I4YzNhMjUxNjAyIiwiYWNyIjoiMSIsInJlYWxtX2FjY2VzcyI6eyJyb2xlcyI6WyJyb2xlOnNpYXNuLWluc3RhbnNpOnBlcmVtYWphYW46b3BlcmF0b3IiLCJyb2xlOnNpYXNuLWluc3RhbnNpOnNrazpvcGVyYXRvciIsInJvbGU6c2lhc24taW5zdGFuc2k6cGVyZW1hamFhbjphcHByb3ZhbCIsInJvbGU6c2lhc24taW5zdGFuc2k6a3A6b3BlcmF0b3IiLCJyb2xlOm1hbmFqZW1lbi13czpkZXZlbG9wZXIiLCJvZmZsaW5lX2FjY2VzcyIsInVtYV9hdXRob3JpemF0aW9uIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwcm9maWxhc246dmlld3Byb2ZpbCJdfSwicmVzb3VyY2VfYWNjZXNzIjp7ImFjY291bnQiOnsicm9sZXMiOlsibWFuYWdlLWFjY291bnQiLCJtYW5hZ2UtYWNjb3VudC1saW5rcyIsInZpZXctcHJvZmlsZSJdfX0sInNjb3BlIjoiZW1haWwgcHJvZmlsZSIsImVtYWlsX3ZlcmlmaWVkIjpmYWxzZSwibmFtZSI6IlJFWkEgUklEV0FOU1lBSCIsInByZWZlcnJlZF91c2VybmFtZSI6IjE5OTUwNjEwMjAyMDEyMTAxNSIsImdpdmVuX25hbWUiOiJSRVpBIiwiZmFtaWx5X25hbWUiOiJSSURXQU5TWUFIIiwiZW1haWwiOiJyZXphcmlkd2Fuc3lhaDEwQGdtYWlsLmNvbSJ9.ZhoTc_790VwFE2BC8zZcUR_QeJ15OMStq4Muq6Q1VU3Zz1RxdK7TDWDLqzNrflduNgMopQstDzhKScexrWQbt4LzJFK7p7k81FTUvTKBWJSykLiGGlUSwu9xycfYk2XmIZSDHF5ucMDnvwMvs2NMlb352TXO-G__YSLbiYvwK1iqr3wiYW2lc2mO00UJLAYoKjXzQPf6jLsORvcVNgelVj8RC_Jfu0rZy45tpUCf878XP5z_SVBbLA359p65bKEYY9p-dxHS1gf-bR8TlrkoOrqjJfZ4cjDB-yS2NVJLiWpQYZPKJ1VeVbySQlmu66P5OumuXjUWrFdYFGKEhNaD9A";
    // $ssoToken = "eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICJBUWNPM0V3MVBmQV9MQ0FtY2J6YnRLUEhtcWhLS1dRbnZ1VDl0RUs3akc4In0.eyJleHAiOjE3MzE5NTUzODQsImlhdCI6MTczMTkxMjE4NCwianRpIjoiZmNkYWIzZWMtYmU5ZC00NDdhLTk2NGEtOWQ2MTc0ZWVhZmZjIiwiaXNzIjoiaHR0cHM6Ly9zc28tc2lhc24uYmtuLmdvLmlkL2F1dGgvcmVhbG1zL3B1YmxpYy1zaWFzbiIsImF1ZCI6ImFjY291bnQiLCJzdWIiOiI5ZTZiZDg5NC01NzNmLTRiOWEtYmNmYS04M2NkYzRhNGNiNTciLCJ0eXAiOiJCZWFyZXIiLCJhenAiOiJzZG1kaWtidWRjbGllbnQiLCJzZXNzaW9uX3N0YXRlIjoiMzkzNjI1ZTUtZTc2Yy00N2FjLWJhNTEtN2I4YzNhMjUxNjAyIiwiYWNyIjoiMSIsInJlYWxtX2FjY2VzcyI6eyJyb2xlcyI6WyJyb2xlOnNpYXNuLWluc3RhbnNpOnBlcmVtYWphYW46b3BlcmF0b3IiLCJyb2xlOnNpYXNuLWluc3RhbnNpOnNrazpvcGVyYXRvciIsInJvbGU6c2lhc24taW5zdGFuc2k6cGVyZW1hamFhbjphcHByb3ZhbCIsInJvbGU6c2lhc24taW5zdGFuc2k6a3A6b3BlcmF0b3IiLCJyb2xlOm1hbmFqZW1lbi13czpkZXZlbG9wZXIiLCJvZmZsaW5lX2FjY2VzcyIsInVtYV9hdXRob3JpemF0aW9uIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwcm9maWxhc246dmlld3Byb2ZpbCJdfSwicmVzb3VyY2VfYWNjZXNzIjp7ImFjY291bnQiOnsicm9sZXMiOlsibWFuYWdlLWFjY291bnQiLCJtYW5hZ2UtYWNjb3VudC1saW5rcyIsInZpZXctcHJvZmlsZSJdfX0sInNjb3BlIjoiZW1haWwgcHJvZmlsZSIsImVtYWlsX3ZlcmlmaWVkIjpmYWxzZSwibmFtZSI6IlJFWkEgUklEV0FOU1lBSCIsInByZWZlcnJlZF91c2VybmFtZSI6IjE5OTUwNjEwMjAyMDEyMTAxNSIsImdpdmVuX25hbWUiOiJSRVpBIiwiZmFtaWx5X25hbWUiOiJSSURXQU5TWUFIIiwiZW1haWwiOiJyZXphcmlkd2Fuc3lhaDEwQGdtYWlsLmNvbSJ9.ZhoTc_790VwFE2BC8zZcUR_QeJ15OMStq4Muq6Q1VU3Zz1RxdK7TDWDLqzNrflduNgMopQstDzhKScexrWQbt4LzJFK7p7k81FTUvTKBWJSykLiGGlUSwu9xycfYk2XmIZSDHF5ucMDnvwMvs2NMlb352TXO-G__YSLbiYvwK1iqr3wiYW2lc2mO00UJLAYoKjXzQPf6jLsORvcVNgelVj8RC_Jfu0rZy45tpUCf878XP5z_SVBbLA359p65bKEYY9p-dxHS1gf-bR8TlrkoOrqjJfZ4cjDB-yS2NVJLiWpQYZPKJ1VeVbySQlmu66P5OumuXjUWrFdYFGKEhNaD9A";
    public function __construct()
    {
        $this->ci = &get_instance();
        $this->apim_provider = new \League\OAuth2\Client\Provider\GenericProvider([
            'clientSecret'          => 'KpDd4kaLhw36pQYS4b1DSvbu6UAa',
            'clientId'              => 'Zds_bXon3d4Fgw3BLo3cYhl2QfQa',
            'urlAccessToken'        => 'https://apimws.bkn.go.id/oauth2/token',
            'urlAuthorize'          => '',
            'urlResourceOwnerDetails' => '',
        ]);

        $this->sso_provider = new \League\OAuth2\Client\Provider\GenericProvider([
            'clientId'              => 'sdmdikbudclient',
            'username'              => '199506102020121015',
            'password'              => 'Rahasia123!',
            'urlAccessToken'        => 'https://sso-siasn.bkn.go.id/auth/realms/public-siasn/protocol/openid-connect/token',
            'urlAuthorize'          => '',
            'urlData'                 => 'https://apimws.bkn.go.id:8243/apisiasn/1.0/',
            'urlResourceOwnerDetails' => '',
            'accessTokenMethod' => 'password'
        ]);
    }
    public function getAPIMProvider()
    {
        return $this->apim_provider;
    }

    public function getSSOProvider()
    {
        return $this->sso_provider;
    }

    private function getApimToken()
    {
        if (isset($_SESSION['apimToken'])) {
            $accessToken = unserialize($_SESSION['apimToken']);
            if (isset($accessToken) && $accessToken->hasExpired()) {
                $accessToken = $this->apim_provider->getAccessToken('client_credentials');
                $_SESSION['apimToken'] = serialize($accessToken);
            }
        } else {
            $accessToken = $this->apim_provider->getAccessToken('client_credentials');
            $_SESSION['apimToken'] = serialize($accessToken);
        }
        return $accessToken->getToken();
    }


    public function getSsoToken()
    {
        if (isset($_SESSION['ssoToken'])) {
            $accessToken = unserialize($_SESSION['ssoToken']);
            if (!is_object($_SESSION['ssoToken'])) {
            }
            // print_r($accessToken);
            if (isset($accessToken) && $accessToken->hasExpired()) {
                $accessToken = $this->siasn_token();
                $_SESSION['ssoToken'] = serialize($accessToken);
            }
        } else {
            $accessToken = $this->siasn_token();
            if (!empty($accessToken))
                $_SESSION['ssoToken'] = serialize($accessToken);
        }
        return $accessToken->getToken();
    }

    private function siasn_token()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://sso-siasn.bkn.go.id/auth/realms/public-siasn/protocol/openid-connect/token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'client_id=sdmdikbudclient&grant_type=password&username=199506102020121015&password=Rahasia123!',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response  = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        if ($err) {
            return "";
        } else {
            $response = json_decode($response, true);
            $token = new \League\OAuth2\Client\Token\AccessToken($response);
            return (object) $token;
        }
    }

    public function data_utama($code){		
		try {
			// $accessToken = $this->getToken();
			//die($accessToken);
			//$ssoToken = "eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICJBUWNPM0V3MVBmQV9MQ0FtY2J6YnRLUEhtcWhLS1dRbnZ1VDl0RUs3akc4In0.eyJleHAiOjE3MzE5NTUzODQsImlhdCI6MTczMTkxMjE4NCwianRpIjoiZmNkYWIzZWMtYmU5ZC00NDdhLTk2NGEtOWQ2MTc0ZWVhZmZjIiwiaXNzIjoiaHR0cHM6Ly9zc28tc2lhc24uYmtuLmdvLmlkL2F1dGgvcmVhbG1zL3B1YmxpYy1zaWFzbiIsImF1ZCI6ImFjY291bnQiLCJzdWIiOiI5ZTZiZDg5NC01NzNmLTRiOWEtYmNmYS04M2NkYzRhNGNiNTciLCJ0eXAiOiJCZWFyZXIiLCJhenAiOiJzZG1kaWtidWRjbGllbnQiLCJzZXNzaW9uX3N0YXRlIjoiMzkzNjI1ZTUtZTc2Yy00N2FjLWJhNTEtN2I4YzNhMjUxNjAyIiwiYWNyIjoiMSIsInJlYWxtX2FjY2VzcyI6eyJyb2xlcyI6WyJyb2xlOnNpYXNuLWluc3RhbnNpOnBlcmVtYWphYW46b3BlcmF0b3IiLCJyb2xlOnNpYXNuLWluc3RhbnNpOnNrazpvcGVyYXRvciIsInJvbGU6c2lhc24taW5zdGFuc2k6cGVyZW1hamFhbjphcHByb3ZhbCIsInJvbGU6c2lhc24taW5zdGFuc2k6a3A6b3BlcmF0b3IiLCJyb2xlOm1hbmFqZW1lbi13czpkZXZlbG9wZXIiLCJvZmZsaW5lX2FjY2VzcyIsInVtYV9hdXRob3JpemF0aW9uIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwcm9maWxhc246dmlld3Byb2ZpbCJdfSwicmVzb3VyY2VfYWNjZXNzIjp7ImFjY291bnQiOnsicm9sZXMiOlsibWFuYWdlLWFjY291bnQiLCJtYW5hZ2UtYWNjb3VudC1saW5rcyIsInZpZXctcHJvZmlsZSJdfX0sInNjb3BlIjoiZW1haWwgcHJvZmlsZSIsImVtYWlsX3ZlcmlmaWVkIjpmYWxzZSwibmFtZSI6IlJFWkEgUklEV0FOU1lBSCIsInByZWZlcnJlZF91c2VybmFtZSI6IjE5OTUwNjEwMjAyMDEyMTAxNSIsImdpdmVuX25hbWUiOiJSRVpBIiwiZmFtaWx5X25hbWUiOiJSSURXQU5TWUFIIiwiZW1haWwiOiJyZXphcmlkd2Fuc3lhaDEwQGdtYWlsLmNvbSJ9.ZhoTc_790VwFE2BC8zZcUR_QeJ15OMStq4Muq6Q1VU3Zz1RxdK7TDWDLqzNrflduNgMopQstDzhKScexrWQbt4LzJFK7p7k81FTUvTKBWJSykLiGGlUSwu9xycfYk2XmIZSDHF5ucMDnvwMvs2NMlb352TXO-G__YSLbiYvwK1iqr3wiYW2lc2mO00UJLAYoKjXzQPf6jLsORvcVNgelVj8RC_Jfu0rZy45tpUCf878XP5z_SVBbLA359p65bKEYY9p-dxHS1gf-bR8TlrkoOrqjJfZ4cjDB-yS2NVJLiWpQYZPKJ1VeVbySQlmu66P5OumuXjUWrFdYFGKEhNaD9A"; $this->getSsoToken()
			$ssoToken = "eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICJBUWNPM0V3MVBmQV9MQ0FtY2J6YnRLUEhtcWhLS1dRbnZ1VDl0RUs3akc4In0.eyJleHAiOjE3MzE5NTUzODQsImlhdCI6MTczMTkxMjE4NCwianRpIjoiZmNkYWIzZWMtYmU5ZC00NDdhLTk2NGEtOWQ2MTc0ZWVhZmZjIiwiaXNzIjoiaHR0cHM6Ly9zc28tc2lhc24uYmtuLmdvLmlkL2F1dGgvcmVhbG1zL3B1YmxpYy1zaWFzbiIsImF1ZCI6ImFjY291bnQiLCJzdWIiOiI5ZTZiZDg5NC01NzNmLTRiOWEtYmNmYS04M2NkYzRhNGNiNTciLCJ0eXAiOiJCZWFyZXIiLCJhenAiOiJzZG1kaWtidWRjbGllbnQiLCJzZXNzaW9uX3N0YXRlIjoiMzkzNjI1ZTUtZTc2Yy00N2FjLWJhNTEtN2I4YzNhMjUxNjAyIiwiYWNyIjoiMSIsInJlYWxtX2FjY2VzcyI6eyJyb2xlcyI6WyJyb2xlOnNpYXNuLWluc3RhbnNpOnBlcmVtYWphYW46b3BlcmF0b3IiLCJyb2xlOnNpYXNuLWluc3RhbnNpOnNrazpvcGVyYXRvciIsInJvbGU6c2lhc24taW5zdGFuc2k6cGVyZW1hamFhbjphcHByb3ZhbCIsInJvbGU6c2lhc24taW5zdGFuc2k6a3A6b3BlcmF0b3IiLCJyb2xlOm1hbmFqZW1lbi13czpkZXZlbG9wZXIiLCJvZmZsaW5lX2FjY2VzcyIsInVtYV9hdXRob3JpemF0aW9uIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwcm9maWxhc246dmlld3Byb2ZpbCJdfSwicmVzb3VyY2VfYWNjZXNzIjp7ImFjY291bnQiOnsicm9sZXMiOlsibWFuYWdlLWFjY291bnQiLCJtYW5hZ2UtYWNjb3VudC1saW5rcyIsInZpZXctcHJvZmlsZSJdfX0sInNjb3BlIjoiZW1haWwgcHJvZmlsZSIsImVtYWlsX3ZlcmlmaWVkIjpmYWxzZSwibmFtZSI6IlJFWkEgUklEV0FOU1lBSCIsInByZWZlcnJlZF91c2VybmFtZSI6IjE5OTUwNjEwMjAyMDEyMTAxNSIsImdpdmVuX25hbWUiOiJSRVpBIiwiZmFtaWx5X25hbWUiOiJSSURXQU5TWUFIIiwiZW1haWwiOiJyZXphcmlkd2Fuc3lhaDEwQGdtYWlsLmNvbSJ9.ZhoTc_790VwFE2BC8zZcUR_QeJ15OMStq4Muq6Q1VU3Zz1RxdK7TDWDLqzNrflduNgMopQstDzhKScexrWQbt4LzJFK7p7k81FTUvTKBWJSykLiGGlUSwu9xycfYk2XmIZSDHF5ucMDnvwMvs2NMlb352TXO-G__YSLbiYvwK1iqr3wiYW2lc2mO00UJLAYoKjXzQPf6jLsORvcVNgelVj8RC_Jfu0rZy45tpUCf878XP5z_SVBbLA359p65bKEYY9p-dxHS1gf-bR8TlrkoOrqjJfZ4cjDB-yS2NVJLiWpQYZPKJ1VeVbySQlmu66P5OumuXjUWrFdYFGKEhNaD9A";
                      // eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICJBUWNPM0V3MVBmQV9MQ0FtY2J6YnRLUEhtcWhLS1dRbnZ1VDl0RUs3akc4In0.eyJleHAiOjE3MzE5NTUzODQsImlhdCI6MTczMTkxMjE4NCwianRpIjoiZmNkYWIzZWMtYmU5ZC00NDdhLTk2NGEtOWQ2MTc0ZWVhZmZjIiwiaXNzIjoiaHR0cHM6Ly9zc28tc2lhc24uYmtuLmdvLmlkL2F1dGgvcmVhbG1zL3B1YmxpYy1zaWFzbiIsImF1ZCI6ImFjY291bnQiLCJzdWIiOiI5ZTZiZDg5NC01NzNmLTRiOWEtYmNmYS04M2NkYzRhNGNiNTciLCJ0eXAiOiJCZWFyZXIiLCJhenAiOiJzZG1kaWtidWRjbGllbnQiLCJzZXNzaW9uX3N0YXRlIjoiMzkzNjI1ZTUtZTc2Yy00N2FjLWJhNTEtN2I4YzNhMjUxNjAyIiwiYWNyIjoiMSIsInJlYWxtX2FjY2VzcyI6eyJyb2xlcyI6WyJyb2xlOnNpYXNuLWluc3RhbnNpOnBlcmVtYWphYW46b3BlcmF0b3IiLCJyb2xlOnNpYXNuLWluc3RhbnNpOnNrazpvcGVyYXRvciIsInJvbGU6c2lhc24taW5zdGFuc2k6cGVyZW1hamFhbjphcHByb3ZhbCIsInJvbGU6c2lhc24taW5zdGFuc2k6a3A6b3BlcmF0b3IiLCJyb2xlOm1hbmFqZW1lbi13czpkZXZlbG9wZXIiLCJvZmZsaW5lX2FjY2VzcyIsInVtYV9hdXRob3JpemF0aW9uIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwcm9maWxhc246dmlld3Byb2ZpbCJdfSwicmVzb3VyY2VfYWNjZXNzIjp7ImFjY291bnQiOnsicm9sZXMiOlsibWFuYWdlLWFjY291bnQiLCJtYW5hZ2UtYWNjb3VudC1saW5rcyIsInZpZXctcHJvZmlsZSJdfX0sInNjb3BlIjoiZW1haWwgcHJvZmlsZSIsImVtYWlsX3ZlcmlmaWVkIjpmYWxzZSwibmFtZSI6IlJFWkEgUklEV0FOU1lBSCIsInByZWZlcnJlZF91c2VybmFtZSI6IjE5OTUwNjEwMjAyMDEyMTAxNSIsImdpdmVuX25hbWUiOiJSRVpBIiwiZmFtaWx5X25hbWUiOiJSSURXQU5TWUFIIiwiZW1haWwiOiJyZXphcmlkd2Fuc3lhaDEwQGdtYWlsLmNvbSJ9.ZhoTc_790VwFE2BC8zZcUR_QeJ15OMStq4Muq6Q1VU3Zz1RxdK7TDWDLqzNrflduNgMopQstDzhKScexrWQbt4LzJFK7p7k81FTUvTKBWJSykLiGGlUSwu9xycfYk2XmIZSDHF5ucMDnvwMvs2NMlb352TXO-G__YSLbiYvwK1iqr3wiYW2lc2mO00UJLAYoKjXzQPf6jLsORvcVNgelVj8RC_Jfu0rZy45tpUCf878XP5z_SVBbLA359p65bKEYY9p-dxHS1gf-bR8TlrkoOrqjJfZ4cjDB-yS2NVJLiWpQYZPKJ1VeVbySQlmu66P5OumuXjUWrFdYFGKEhNaD9A
            $apimwsToken = $this->getApimwsToken();
			$curl = curl_init();

			curl_setopt_array($curl, array(
			  CURLOPT_URL => "https://apimws.bkn.go.id:8243/apisiasn/1.0/pns/data-utama/".$code,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",
			  CURLOPT_HTTPHEADER => array(
				'accept: application/json',
				'Auth: bearer '.$ssoToken,
				'Authorization: Bearer '.$apimwsToken, 
				'Cookie: ff8d625df24f2272ecde05bd53b814bc=d857a1a88c247a5c1125d8adcb448fa5; pdns=1091068938.58148.0000'
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


    function genericPOST($data,$url){
        try {  
            $apimToken = $this->getApimToken();
            $ssoToken = "eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICJBUWNPM0V3MVBmQV9MQ0FtY2J6YnRLUEhtcWhLS1dRbnZ1VDl0RUs3akc4In0.eyJleHAiOjE3MzE5NTUzODQsImlhdCI6MTczMTkxMjE4NCwianRpIjoiZmNkYWIzZWMtYmU5ZC00NDdhLTk2NGEtOWQ2MTc0ZWVhZmZjIiwiaXNzIjoiaHR0cHM6Ly9zc28tc2lhc24uYmtuLmdvLmlkL2F1dGgvcmVhbG1zL3B1YmxpYy1zaWFzbiIsImF1ZCI6ImFjY291bnQiLCJzdWIiOiI5ZTZiZDg5NC01NzNmLTRiOWEtYmNmYS04M2NkYzRhNGNiNTciLCJ0eXAiOiJCZWFyZXIiLCJhenAiOiJzZG1kaWtidWRjbGllbnQiLCJzZXNzaW9uX3N0YXRlIjoiMzkzNjI1ZTUtZTc2Yy00N2FjLWJhNTEtN2I4YzNhMjUxNjAyIiwiYWNyIjoiMSIsInJlYWxtX2FjY2VzcyI6eyJyb2xlcyI6WyJyb2xlOnNpYXNuLWluc3RhbnNpOnBlcmVtYWphYW46b3BlcmF0b3IiLCJyb2xlOnNpYXNuLWluc3RhbnNpOnNrazpvcGVyYXRvciIsInJvbGU6c2lhc24taW5zdGFuc2k6cGVyZW1hamFhbjphcHByb3ZhbCIsInJvbGU6c2lhc24taW5zdGFuc2k6a3A6b3BlcmF0b3IiLCJyb2xlOm1hbmFqZW1lbi13czpkZXZlbG9wZXIiLCJvZmZsaW5lX2FjY2VzcyIsInVtYV9hdXRob3JpemF0aW9uIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwcm9maWxhc246dmlld3Byb2ZpbCJdfSwicmVzb3VyY2VfYWNjZXNzIjp7ImFjY291bnQiOnsicm9sZXMiOlsibWFuYWdlLWFjY291bnQiLCJtYW5hZ2UtYWNjb3VudC1saW5rcyIsInZpZXctcHJvZmlsZSJdfX0sInNjb3BlIjoiZW1haWwgcHJvZmlsZSIsImVtYWlsX3ZlcmlmaWVkIjpmYWxzZSwibmFtZSI6IlJFWkEgUklEV0FOU1lBSCIsInByZWZlcnJlZF91c2VybmFtZSI6IjE5OTUwNjEwMjAyMDEyMTAxNSIsImdpdmVuX25hbWUiOiJSRVpBIiwiZmFtaWx5X25hbWUiOiJSSURXQU5TWUFIIiwiZW1haWwiOiJyZXphcmlkd2Fuc3lhaDEwQGdtYWlsLmNvbSJ9.ZhoTc_790VwFE2BC8zZcUR_QeJ15OMStq4Muq6Q1VU3Zz1RxdK7TDWDLqzNrflduNgMopQstDzhKScexrWQbt4LzJFK7p7k81FTUvTKBWJSykLiGGlUSwu9xycfYk2XmIZSDHF5ucMDnvwMvs2NMlb352TXO-G__YSLbiYvwK1iqr3wiYW2lc2mO00UJLAYoKjXzQPf6jLsORvcVNgelVj8RC_Jfu0rZy45tpUCf878XP5z_SVBbLA359p65bKEYY9p-dxHS1gf-bR8TlrkoOrqjJfZ4cjDB-yS2NVJLiWpQYZPKJ1VeVbySQlmu66P5OumuXjUWrFdYFGKEhNaD9A";
                        //  eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICJBUWNPM0V3MVBmQV9MQ0FtY2J6YnRLUEhtcWhLS1dRbnZ1VDl0RUs3akc4In0.eyJleHAiOjE3MzE5NTUzODQsImlhdCI6MTczMTkxMjE4NCwianRpIjoiZmNkYWIzZWMtYmU5ZC00NDdhLTk2NGEtOWQ2MTc0ZWVhZmZjIiwiaXNzIjoiaHR0cHM6Ly9zc28tc2lhc24uYmtuLmdvLmlkL2F1dGgvcmVhbG1zL3B1YmxpYy1zaWFzbiIsImF1ZCI6ImFjY291bnQiLCJzdWIiOiI5ZTZiZDg5NC01NzNmLTRiOWEtYmNmYS04M2NkYzRhNGNiNTciLCJ0eXAiOiJCZWFyZXIiLCJhenAiOiJzZG1kaWtidWRjbGllbnQiLCJzZXNzaW9uX3N0YXRlIjoiMzkzNjI1ZTUtZTc2Yy00N2FjLWJhNTEtN2I4YzNhMjUxNjAyIiwiYWNyIjoiMSIsInJlYWxtX2FjY2VzcyI6eyJyb2xlcyI6WyJyb2xlOnNpYXNuLWluc3RhbnNpOnBlcmVtYWphYW46b3BlcmF0b3IiLCJyb2xlOnNpYXNuLWluc3RhbnNpOnNrazpvcGVyYXRvciIsInJvbGU6c2lhc24taW5zdGFuc2k6cGVyZW1hamFhbjphcHByb3ZhbCIsInJvbGU6c2lhc24taW5zdGFuc2k6a3A6b3BlcmF0b3IiLCJyb2xlOm1hbmFqZW1lbi13czpkZXZlbG9wZXIiLCJvZmZsaW5lX2FjY2VzcyIsInVtYV9hdXRob3JpemF0aW9uIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwcm9maWxhc246dmlld3Byb2ZpbCJdfSwicmVzb3VyY2VfYWNjZXNzIjp7ImFjY291bnQiOnsicm9sZXMiOlsibWFuYWdlLWFjY291bnQiLCJtYW5hZ2UtYWNjb3VudC1saW5rcyIsInZpZXctcHJvZmlsZSJdfX0sInNjb3BlIjoiZW1haWwgcHJvZmlsZSIsImVtYWlsX3ZlcmlmaWVkIjpmYWxzZSwibmFtZSI6IlJFWkEgUklEV0FOU1lBSCIsInByZWZlcnJlZF91c2VybmFtZSI6IjE5OTUwNjEwMjAyMDEyMTAxNSIsImdpdmVuX25hbWUiOiJSRVpBIiwiZmFtaWx5X25hbWUiOiJSSURXQU5TWUFIIiwiZW1haWwiOiJyZXphcmlkd2Fuc3lhaDEwQGdtYWlsLmNvbSJ9.ZhoTc_790VwFE2BC8zZcUR_QeJ15OMStq4Muq6Q1VU3Zz1RxdK7TDWDLqzNrflduNgMopQstDzhKScexrWQbt4LzJFK7p7k81FTUvTKBWJSykLiGGlUSwu9xycfYk2XmIZSDHF5ucMDnvwMvs2NMlb352TXO-G__YSLbiYvwK1iqr3wiYW2lc2mO00UJLAYoKjXzQPf6jLsORvcVNgelVj8RC_Jfu0rZy45tpUCf878XP5z_SVBbLA359p65bKEYY9p-dxHS1gf-bR8TlrkoOrqjJfZ4cjDB-yS2NVJLiWpQYZPKJ1VeVbySQlmu66P5OumuXjUWrFdYFGKEhNaD9A
            $curl = curl_init();
        
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>json_encode($data),
                CURLOPT_HTTPHEADER => array(
                    'accept: application/json',
                    "Auth: bearer $ssoToken",
                    "Authorization: Bearer $apimToken",
                    'Content-Type: application/json',
                    'Cookie: ff8d625df24f2272ecde05bd53b814bc=7ab5140822120a0161e7962bfbab8c66; pdns=1091068938.58148.0000'
                ),
            ));
        
            $response = curl_exec($curl);
        
            curl_close($curl);
            return json_decode($response,true);
        } catch (\Throwable $th) {
            //throw $th;
            return array("status"=>false,"message"=>"failed obtaining connection with BKN");
        }
    }

    function genericDelete($id,$url){
        try {  
            $apimToken = $this->getApimToken();
            $ssoToken = "eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICJBUWNPM0V3MVBmQV9MQ0FtY2J6YnRLUEhtcWhLS1dRbnZ1VDl0RUs3akc4In0.eyJleHAiOjE3MzE5NTUzODQsImlhdCI6MTczMTkxMjE4NCwianRpIjoiZmNkYWIzZWMtYmU5ZC00NDdhLTk2NGEtOWQ2MTc0ZWVhZmZjIiwiaXNzIjoiaHR0cHM6Ly9zc28tc2lhc24uYmtuLmdvLmlkL2F1dGgvcmVhbG1zL3B1YmxpYy1zaWFzbiIsImF1ZCI6ImFjY291bnQiLCJzdWIiOiI5ZTZiZDg5NC01NzNmLTRiOWEtYmNmYS04M2NkYzRhNGNiNTciLCJ0eXAiOiJCZWFyZXIiLCJhenAiOiJzZG1kaWtidWRjbGllbnQiLCJzZXNzaW9uX3N0YXRlIjoiMzkzNjI1ZTUtZTc2Yy00N2FjLWJhNTEtN2I4YzNhMjUxNjAyIiwiYWNyIjoiMSIsInJlYWxtX2FjY2VzcyI6eyJyb2xlcyI6WyJyb2xlOnNpYXNuLWluc3RhbnNpOnBlcmVtYWphYW46b3BlcmF0b3IiLCJyb2xlOnNpYXNuLWluc3RhbnNpOnNrazpvcGVyYXRvciIsInJvbGU6c2lhc24taW5zdGFuc2k6cGVyZW1hamFhbjphcHByb3ZhbCIsInJvbGU6c2lhc24taW5zdGFuc2k6a3A6b3BlcmF0b3IiLCJyb2xlOm1hbmFqZW1lbi13czpkZXZlbG9wZXIiLCJvZmZsaW5lX2FjY2VzcyIsInVtYV9hdXRob3JpemF0aW9uIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwcm9maWxhc246dmlld3Byb2ZpbCJdfSwicmVzb3VyY2VfYWNjZXNzIjp7ImFjY291bnQiOnsicm9sZXMiOlsibWFuYWdlLWFjY291bnQiLCJtYW5hZ2UtYWNjb3VudC1saW5rcyIsInZpZXctcHJvZmlsZSJdfX0sInNjb3BlIjoiZW1haWwgcHJvZmlsZSIsImVtYWlsX3ZlcmlmaWVkIjpmYWxzZSwibmFtZSI6IlJFWkEgUklEV0FOU1lBSCIsInByZWZlcnJlZF91c2VybmFtZSI6IjE5OTUwNjEwMjAyMDEyMTAxNSIsImdpdmVuX25hbWUiOiJSRVpBIiwiZmFtaWx5X25hbWUiOiJSSURXQU5TWUFIIiwiZW1haWwiOiJyZXphcmlkd2Fuc3lhaDEwQGdtYWlsLmNvbSJ9.ZhoTc_790VwFE2BC8zZcUR_QeJ15OMStq4Muq6Q1VU3Zz1RxdK7TDWDLqzNrflduNgMopQstDzhKScexrWQbt4LzJFK7p7k81FTUvTKBWJSykLiGGlUSwu9xycfYk2XmIZSDHF5ucMDnvwMvs2NMlb352TXO-G__YSLbiYvwK1iqr3wiYW2lc2mO00UJLAYoKjXzQPf6jLsORvcVNgelVj8RC_Jfu0rZy45tpUCf878XP5z_SVBbLA359p65bKEYY9p-dxHS1gf-bR8TlrkoOrqjJfZ4cjDB-yS2NVJLiWpQYZPKJ1VeVbySQlmu66P5OumuXjUWrFdYFGKEhNaD9A";
            $curl = curl_init();
            $endpoint = $url."/".$id;
            curl_setopt_array($curl, array(
                CURLOPT_URL => $endpoint,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'DELETE',
                
                CURLOPT_HTTPHEADER => array(
                    'accept: application/json',
                    "Auth: bearer $ssoToken",
                    "Authorization: Bearer $apimToken",
                    'Content-Type: application/json',
                    'Cookie: ff8d625df24f2272ecde05bd53b814bc=7ab5140822120a0161e7962bfbab8c66; pdns=1091068938.58148.0000'
                ),
            ));
        
            $response = curl_exec($curl);
        
            curl_close($curl);

            $ret = json_decode($response,true);
            $ret['endpoint'] = $endpoint;
            return $ret;
        } catch (\Throwable $th) {
            //throw $th;
            return array("status"=>false,"message"=>"failed obtaining connection with BKN");
        }
    }

    public function getDataPNS($module, $code)
    {
        try {
            $apimToken = $this->getApimToken();
            $ssoToken = "eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICJBUWNPM0V3MVBmQV9MQ0FtY2J6YnRLUEhtcWhLS1dRbnZ1VDl0RUs3akc4In0.eyJleHAiOjE3MzE5NTUzODQsImlhdCI6MTczMTkxMjE4NCwianRpIjoiZmNkYWIzZWMtYmU5ZC00NDdhLTk2NGEtOWQ2MTc0ZWVhZmZjIiwiaXNzIjoiaHR0cHM6Ly9zc28tc2lhc24uYmtuLmdvLmlkL2F1dGgvcmVhbG1zL3B1YmxpYy1zaWFzbiIsImF1ZCI6ImFjY291bnQiLCJzdWIiOiI5ZTZiZDg5NC01NzNmLTRiOWEtYmNmYS04M2NkYzRhNGNiNTciLCJ0eXAiOiJCZWFyZXIiLCJhenAiOiJzZG1kaWtidWRjbGllbnQiLCJzZXNzaW9uX3N0YXRlIjoiMzkzNjI1ZTUtZTc2Yy00N2FjLWJhNTEtN2I4YzNhMjUxNjAyIiwiYWNyIjoiMSIsInJlYWxtX2FjY2VzcyI6eyJyb2xlcyI6WyJyb2xlOnNpYXNuLWluc3RhbnNpOnBlcmVtYWphYW46b3BlcmF0b3IiLCJyb2xlOnNpYXNuLWluc3RhbnNpOnNrazpvcGVyYXRvciIsInJvbGU6c2lhc24taW5zdGFuc2k6cGVyZW1hamFhbjphcHByb3ZhbCIsInJvbGU6c2lhc24taW5zdGFuc2k6a3A6b3BlcmF0b3IiLCJyb2xlOm1hbmFqZW1lbi13czpkZXZlbG9wZXIiLCJvZmZsaW5lX2FjY2VzcyIsInVtYV9hdXRob3JpemF0aW9uIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwcm9maWxhc246dmlld3Byb2ZpbCJdfSwicmVzb3VyY2VfYWNjZXNzIjp7ImFjY291bnQiOnsicm9sZXMiOlsibWFuYWdlLWFjY291bnQiLCJtYW5hZ2UtYWNjb3VudC1saW5rcyIsInZpZXctcHJvZmlsZSJdfX0sInNjb3BlIjoiZW1haWwgcHJvZmlsZSIsImVtYWlsX3ZlcmlmaWVkIjpmYWxzZSwibmFtZSI6IlJFWkEgUklEV0FOU1lBSCIsInByZWZlcnJlZF91c2VybmFtZSI6IjE5OTUwNjEwMjAyMDEyMTAxNSIsImdpdmVuX25hbWUiOiJSRVpBIiwiZmFtaWx5X25hbWUiOiJSSURXQU5TWUFIIiwiZW1haWwiOiJyZXphcmlkd2Fuc3lhaDEwQGdtYWlsLmNvbSJ9.ZhoTc_790VwFE2BC8zZcUR_QeJ15OMStq4Muq6Q1VU3Zz1RxdK7TDWDLqzNrflduNgMopQstDzhKScexrWQbt4LzJFK7p7k81FTUvTKBWJSykLiGGlUSwu9xycfYk2XmIZSDHF5ucMDnvwMvs2NMlb352TXO-G__YSLbiYvwK1iqr3wiYW2lc2mO00UJLAYoKjXzQPf6jLsORvcVNgelVj8RC_Jfu0rZy45tpUCf878XP5z_SVBbLA359p65bKEYY9p-dxHS1gf-bR8TlrkoOrqjJfZ4cjDB-yS2NVJLiWpQYZPKJ1VeVbySQlmu66P5OumuXjUWrFdYFGKEhNaD9A";
                      //"eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICJBUWNPM0V3MVBmQV9MQ0FtY2J6YnRLUEhtcWhLS1dRbnZ1VDl0RUs3akc4In0.eyJleHAiOjE3MzE5NTUzODQsImlhdCI6MTczMTkxMjE4NCwianRpIjoiZmNkYWIzZWMtYmU5ZC00NDdhLTk2NGEtOWQ2MTc0ZWVhZmZjIiwiaXNzIjoiaHR0cHM6Ly9zc28tc2lhc24uYmtuLmdvLmlkL2F1dGgvcmVhbG1zL3B1YmxpYy1zaWFzbiIsImF1ZCI6ImFjY291bnQiLCJzdWIiOiI5ZTZiZDg5NC01NzNmLTRiOWEtYmNmYS04M2NkYzRhNGNiNTciLCJ0eXAiOiJCZWFyZXIiLCJhenAiOiJzZG1kaWtidWRjbGllbnQiLCJzZXNzaW9uX3N0YXRlIjoiMzkzNjI1ZTUtZTc2Yy00N2FjLWJhNTEtN2I4YzNhMjUxNjAyIiwiYWNyIjoiMSIsInJlYWxtX2FjY2VzcyI6eyJyb2xlcyI6WyJyb2xlOnNpYXNuLWluc3RhbnNpOnBlcmVtYWphYW46b3BlcmF0b3IiLCJyb2xlOnNpYXNuLWluc3RhbnNpOnNrazpvcGVyYXRvciIsInJvbGU6c2lhc24taW5zdGFuc2k6cGVyZW1hamFhbjphcHByb3ZhbCIsInJvbGU6c2lhc24taW5zdGFuc2k6a3A6b3BlcmF0b3IiLCJyb2xlOm1hbmFqZW1lbi13czpkZXZlbG9wZXIiLCJvZmZsaW5lX2FjY2VzcyIsInVtYV9hdXRob3JpemF0aW9uIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwcm9maWxhc246dmlld3Byb2ZpbCJdfSwicmVzb3VyY2VfYWNjZXNzIjp7ImFjY291bnQiOnsicm9sZXMiOlsibWFuYWdlLWFjY291bnQiLCJtYW5hZ2UtYWNjb3VudC1saW5rcyIsInZpZXctcHJvZmlsZSJdfX0sInNjb3BlIjoiZW1haWwgcHJvZmlsZSIsImVtYWlsX3ZlcmlmaWVkIjpmYWxzZSwibmFtZSI6IlJFWkEgUklEV0FOU1lBSCIsInByZWZlcnJlZF91c2VybmFtZSI6IjE5OTUwNjEwMjAyMDEyMTAxNSIsImdpdmVuX25hbWUiOiJSRVpBIiwiZmFtaWx5X25hbWUiOiJSSURXQU5TWUFIIiwiZW1haWwiOiJyZXphcmlkd2Fuc3lhaDEwQGdtYWlsLmNvbSJ9.ZhoTc_790VwFE2BC8zZcUR_QeJ15OMStq4Muq6Q1VU3Zz1RxdK7TDWDLqzNrflduNgMopQstDzhKScexrWQbt4LzJFK7p7k81FTUvTKBWJSykLiGGlUSwu9xycfYk2XmIZSDHF5ucMDnvwMvs2NMlb352TXO-G__YSLbiYvwK1iqr3wiYW2lc2mO00UJLAYoKjXzQPf6jLsORvcVNgelVj8RC_Jfu0rZy45tpUCf878XP5z_SVBbLA359p65bKEYY9p-dxHS1gf-bR8TlrkoOrqjJfZ4cjDB-yS2NVJLiWpQYZPKJ1VeVbySQlmu66P5OumuXjUWrFdYFGKEhNaD9A
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://apimws.bkn.go.id:8243/apisiasn/1.0/pns/{$module}/{$code}",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_SSL_VERIFYHOST => 2,   
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_SSL_CIPHER_LIST=>"DEFAULT@SECLEVEL=1",
                CURLOPT_SSLVERSION => "CURL_SSLVERSION_TLSv1_3",
                CURLOPT_HTTPHEADER => array(
                    'accept: application/json',
                    "Auth: bearer $ssoToken",
                    "Authorization: Bearer $apimToken"
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            echo $err;
            curl_close($curl);
            if ($err) {
                return null;
            } else {
                // $result = json_decode($response);
                return json_encode($response);
            }
        } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
            // Failed to get the access token
            exit($e->getMessage());
        }
    }
    
    public function getDataDetail($module, $id)
    {
        try {
            $apimToken = $this->getApimToken();
            $ssoToken = "eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICJBUWNPM0V3MVBmQV9MQ0FtY2J6YnRLUEhtcWhLS1dRbnZ1VDl0RUs3akc4In0.eyJleHAiOjE3MzE5NTUzODQsImlhdCI6MTczMTkxMjE4NCwianRpIjoiZmNkYWIzZWMtYmU5ZC00NDdhLTk2NGEtOWQ2MTc0ZWVhZmZjIiwiaXNzIjoiaHR0cHM6Ly9zc28tc2lhc24uYmtuLmdvLmlkL2F1dGgvcmVhbG1zL3B1YmxpYy1zaWFzbiIsImF1ZCI6ImFjY291bnQiLCJzdWIiOiI5ZTZiZDg5NC01NzNmLTRiOWEtYmNmYS04M2NkYzRhNGNiNTciLCJ0eXAiOiJCZWFyZXIiLCJhenAiOiJzZG1kaWtidWRjbGllbnQiLCJzZXNzaW9uX3N0YXRlIjoiMzkzNjI1ZTUtZTc2Yy00N2FjLWJhNTEtN2I4YzNhMjUxNjAyIiwiYWNyIjoiMSIsInJlYWxtX2FjY2VzcyI6eyJyb2xlcyI6WyJyb2xlOnNpYXNuLWluc3RhbnNpOnBlcmVtYWphYW46b3BlcmF0b3IiLCJyb2xlOnNpYXNuLWluc3RhbnNpOnNrazpvcGVyYXRvciIsInJvbGU6c2lhc24taW5zdGFuc2k6cGVyZW1hamFhbjphcHByb3ZhbCIsInJvbGU6c2lhc24taW5zdGFuc2k6a3A6b3BlcmF0b3IiLCJyb2xlOm1hbmFqZW1lbi13czpkZXZlbG9wZXIiLCJvZmZsaW5lX2FjY2VzcyIsInVtYV9hdXRob3JpemF0aW9uIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwcm9maWxhc246dmlld3Byb2ZpbCJdfSwicmVzb3VyY2VfYWNjZXNzIjp7ImFjY291bnQiOnsicm9sZXMiOlsibWFuYWdlLWFjY291bnQiLCJtYW5hZ2UtYWNjb3VudC1saW5rcyIsInZpZXctcHJvZmlsZSJdfX0sInNjb3BlIjoiZW1haWwgcHJvZmlsZSIsImVtYWlsX3ZlcmlmaWVkIjpmYWxzZSwibmFtZSI6IlJFWkEgUklEV0FOU1lBSCIsInByZWZlcnJlZF91c2VybmFtZSI6IjE5OTUwNjEwMjAyMDEyMTAxNSIsImdpdmVuX25hbWUiOiJSRVpBIiwiZmFtaWx5X25hbWUiOiJSSURXQU5TWUFIIiwiZW1haWwiOiJyZXphcmlkd2Fuc3lhaDEwQGdtYWlsLmNvbSJ9.ZhoTc_790VwFE2BC8zZcUR_QeJ15OMStq4Muq6Q1VU3Zz1RxdK7TDWDLqzNrflduNgMopQstDzhKScexrWQbt4LzJFK7p7k81FTUvTKBWJSykLiGGlUSwu9xycfYk2XmIZSDHF5ucMDnvwMvs2NMlb352TXO-G__YSLbiYvwK1iqr3wiYW2lc2mO00UJLAYoKjXzQPf6jLsORvcVNgelVj8RC_Jfu0rZy45tpUCf878XP5z_SVBbLA359p65bKEYY9p-dxHS1gf-bR8TlrkoOrqjJfZ4cjDB-yS2NVJLiWpQYZPKJ1VeVbySQlmu66P5OumuXjUWrFdYFGKEhNaD9A";
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://apimws.bkn.go.id:8243/apisiasn/1.0/{$module}/id/{$id}",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'accept: application/json',
                    "Auth: bearer $ssoToken",
                    "Authorization: Bearer $apimToken"
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                return null;
            } else {
                // $result = json_decode($response);
                return json_encode($response);
            }
        } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
            // Failed to get the access token
            exit($e->getMessage());
        }
    }

    public function getDataRealTime($module, $nip)
    {
        try {
            $apimToken = $this->getApimToken();
            $ssoToken = "eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICJBUWNPM0V3MVBmQV9MQ0FtY2J6YnRLUEhtcWhLS1dRbnZ1VDl0RUs3akc4In0.eyJleHAiOjE3MzE5NTUzODQsImlhdCI6MTczMTkxMjE4NCwianRpIjoiZmNkYWIzZWMtYmU5ZC00NDdhLTk2NGEtOWQ2MTc0ZWVhZmZjIiwiaXNzIjoiaHR0cHM6Ly9zc28tc2lhc24uYmtuLmdvLmlkL2F1dGgvcmVhbG1zL3B1YmxpYy1zaWFzbiIsImF1ZCI6ImFjY291bnQiLCJzdWIiOiI5ZTZiZDg5NC01NzNmLTRiOWEtYmNmYS04M2NkYzRhNGNiNTciLCJ0eXAiOiJCZWFyZXIiLCJhenAiOiJzZG1kaWtidWRjbGllbnQiLCJzZXNzaW9uX3N0YXRlIjoiMzkzNjI1ZTUtZTc2Yy00N2FjLWJhNTEtN2I4YzNhMjUxNjAyIiwiYWNyIjoiMSIsInJlYWxtX2FjY2VzcyI6eyJyb2xlcyI6WyJyb2xlOnNpYXNuLWluc3RhbnNpOnBlcmVtYWphYW46b3BlcmF0b3IiLCJyb2xlOnNpYXNuLWluc3RhbnNpOnNrazpvcGVyYXRvciIsInJvbGU6c2lhc24taW5zdGFuc2k6cGVyZW1hamFhbjphcHByb3ZhbCIsInJvbGU6c2lhc24taW5zdGFuc2k6a3A6b3BlcmF0b3IiLCJyb2xlOm1hbmFqZW1lbi13czpkZXZlbG9wZXIiLCJvZmZsaW5lX2FjY2VzcyIsInVtYV9hdXRob3JpemF0aW9uIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwcm9maWxhc246dmlld3Byb2ZpbCJdfSwicmVzb3VyY2VfYWNjZXNzIjp7ImFjY291bnQiOnsicm9sZXMiOlsibWFuYWdlLWFjY291bnQiLCJtYW5hZ2UtYWNjb3VudC1saW5rcyIsInZpZXctcHJvZmlsZSJdfX0sInNjb3BlIjoiZW1haWwgcHJvZmlsZSIsImVtYWlsX3ZlcmlmaWVkIjpmYWxzZSwibmFtZSI6IlJFWkEgUklEV0FOU1lBSCIsInByZWZlcnJlZF91c2VybmFtZSI6IjE5OTUwNjEwMjAyMDEyMTAxNSIsImdpdmVuX25hbWUiOiJSRVpBIiwiZmFtaWx5X25hbWUiOiJSSURXQU5TWUFIIiwiZW1haWwiOiJyZXphcmlkd2Fuc3lhaDEwQGdtYWlsLmNvbSJ9.ZhoTc_790VwFE2BC8zZcUR_QeJ15OMStq4Muq6Q1VU3Zz1RxdK7TDWDLqzNrflduNgMopQstDzhKScexrWQbt4LzJFK7p7k81FTUvTKBWJSykLiGGlUSwu9xycfYk2XmIZSDHF5ucMDnvwMvs2NMlb352TXO-G__YSLbiYvwK1iqr3wiYW2lc2mO00UJLAYoKjXzQPf6jLsORvcVNgelVj8RC_Jfu0rZy45tpUCf878XP5z_SVBbLA359p65bKEYY9p-dxHS1gf-bR8TlrkoOrqjJfZ4cjDB-yS2NVJLiWpQYZPKJ1VeVbySQlmu66P5OumuXjUWrFdYFGKEhNaD9A";
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://apimws.bkn.go.id:8243/apisiasn/1.0/{$module}/pns/{$nip}",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'accept: application/json',
                    "Auth: bearer $ssoToken",
                    "Authorization: Bearer $apimToken"
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                return null;
            } else {
                // $result = json_decode($response);
                return json_encode($response);
            }
        } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
            // Failed to get the access token
            exit($e->getMessage());
        }
    }

    public function postData($module, $data)
    {
        try {
            $apimToken = $this->getApimToken();
            $ssoToken = "eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICJBUWNPM0V3MVBmQV9MQ0FtY2J6YnRLUEhtcWhLS1dRbnZ1VDl0RUs3akc4In0.eyJleHAiOjE3MzE5NTUzODQsImlhdCI6MTczMTkxMjE4NCwianRpIjoiZmNkYWIzZWMtYmU5ZC00NDdhLTk2NGEtOWQ2MTc0ZWVhZmZjIiwiaXNzIjoiaHR0cHM6Ly9zc28tc2lhc24uYmtuLmdvLmlkL2F1dGgvcmVhbG1zL3B1YmxpYy1zaWFzbiIsImF1ZCI6ImFjY291bnQiLCJzdWIiOiI5ZTZiZDg5NC01NzNmLTRiOWEtYmNmYS04M2NkYzRhNGNiNTciLCJ0eXAiOiJCZWFyZXIiLCJhenAiOiJzZG1kaWtidWRjbGllbnQiLCJzZXNzaW9uX3N0YXRlIjoiMzkzNjI1ZTUtZTc2Yy00N2FjLWJhNTEtN2I4YzNhMjUxNjAyIiwiYWNyIjoiMSIsInJlYWxtX2FjY2VzcyI6eyJyb2xlcyI6WyJyb2xlOnNpYXNuLWluc3RhbnNpOnBlcmVtYWphYW46b3BlcmF0b3IiLCJyb2xlOnNpYXNuLWluc3RhbnNpOnNrazpvcGVyYXRvciIsInJvbGU6c2lhc24taW5zdGFuc2k6cGVyZW1hamFhbjphcHByb3ZhbCIsInJvbGU6c2lhc24taW5zdGFuc2k6a3A6b3BlcmF0b3IiLCJyb2xlOm1hbmFqZW1lbi13czpkZXZlbG9wZXIiLCJvZmZsaW5lX2FjY2VzcyIsInVtYV9hdXRob3JpemF0aW9uIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwcm9maWxhc246dmlld3Byb2ZpbCJdfSwicmVzb3VyY2VfYWNjZXNzIjp7ImFjY291bnQiOnsicm9sZXMiOlsibWFuYWdlLWFjY291bnQiLCJtYW5hZ2UtYWNjb3VudC1saW5rcyIsInZpZXctcHJvZmlsZSJdfX0sInNjb3BlIjoiZW1haWwgcHJvZmlsZSIsImVtYWlsX3ZlcmlmaWVkIjpmYWxzZSwibmFtZSI6IlJFWkEgUklEV0FOU1lBSCIsInByZWZlcnJlZF91c2VybmFtZSI6IjE5OTUwNjEwMjAyMDEyMTAxNSIsImdpdmVuX25hbWUiOiJSRVpBIiwiZmFtaWx5X25hbWUiOiJSSURXQU5TWUFIIiwiZW1haWwiOiJyZXphcmlkd2Fuc3lhaDEwQGdtYWlsLmNvbSJ9.ZhoTc_790VwFE2BC8zZcUR_QeJ15OMStq4Muq6Q1VU3Zz1RxdK7TDWDLqzNrflduNgMopQstDzhKScexrWQbt4LzJFK7p7k81FTUvTKBWJSykLiGGlUSwu9xycfYk2XmIZSDHF5ucMDnvwMvs2NMlb352TXO-G__YSLbiYvwK1iqr3wiYW2lc2mO00UJLAYoKjXzQPf6jLsORvcVNgelVj8RC_Jfu0rZy45tpUCf878XP5z_SVBbLA359p65bKEYY9p-dxHS1gf-bR8TlrkoOrqjJfZ4cjDB-yS2NVJLiWpQYZPKJ1VeVbySQlmu66P5OumuXjUWrFdYFGKEhNaD9A";
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://apimws.bkn.go.id:8243/apisiasn/1.0/{$module}/save",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($data),
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'accept: application/json',
                    "Auth: bearer $ssoToken",
                    "Authorization: Bearer $apimToken"
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);
            if ($err) {
                return $err;
            } else {
                return json_decode($response);
            }
        } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
            // Failed to get the access token
            //exit($e->getMessage());
            return $e->getMessage();
        }
    }


    public function postDataSKP2021($skp){
        $result = $this->postData("skp/2021",$skp);
        return $result;
    }

    public function downloadDokumen($filepath)
    {
        try {
            $apimToken = $this->getApimToken();
            $ssoToken = "eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICJBUWNPM0V3MVBmQV9MQ0FtY2J6YnRLUEhtcWhLS1dRbnZ1VDl0RUs3akc4In0.eyJleHAiOjE3MzE5NTUzODQsImlhdCI6MTczMTkxMjE4NCwianRpIjoiZmNkYWIzZWMtYmU5ZC00NDdhLTk2NGEtOWQ2MTc0ZWVhZmZjIiwiaXNzIjoiaHR0cHM6Ly9zc28tc2lhc24uYmtuLmdvLmlkL2F1dGgvcmVhbG1zL3B1YmxpYy1zaWFzbiIsImF1ZCI6ImFjY291bnQiLCJzdWIiOiI5ZTZiZDg5NC01NzNmLTRiOWEtYmNmYS04M2NkYzRhNGNiNTciLCJ0eXAiOiJCZWFyZXIiLCJhenAiOiJzZG1kaWtidWRjbGllbnQiLCJzZXNzaW9uX3N0YXRlIjoiMzkzNjI1ZTUtZTc2Yy00N2FjLWJhNTEtN2I4YzNhMjUxNjAyIiwiYWNyIjoiMSIsInJlYWxtX2FjY2VzcyI6eyJyb2xlcyI6WyJyb2xlOnNpYXNuLWluc3RhbnNpOnBlcmVtYWphYW46b3BlcmF0b3IiLCJyb2xlOnNpYXNuLWluc3RhbnNpOnNrazpvcGVyYXRvciIsInJvbGU6c2lhc24taW5zdGFuc2k6cGVyZW1hamFhbjphcHByb3ZhbCIsInJvbGU6c2lhc24taW5zdGFuc2k6a3A6b3BlcmF0b3IiLCJyb2xlOm1hbmFqZW1lbi13czpkZXZlbG9wZXIiLCJvZmZsaW5lX2FjY2VzcyIsInVtYV9hdXRob3JpemF0aW9uIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwcm9maWxhc246dmlld3Byb2ZpbCJdfSwicmVzb3VyY2VfYWNjZXNzIjp7ImFjY291bnQiOnsicm9sZXMiOlsibWFuYWdlLWFjY291bnQiLCJtYW5hZ2UtYWNjb3VudC1saW5rcyIsInZpZXctcHJvZmlsZSJdfX0sInNjb3BlIjoiZW1haWwgcHJvZmlsZSIsImVtYWlsX3ZlcmlmaWVkIjpmYWxzZSwibmFtZSI6IlJFWkEgUklEV0FOU1lBSCIsInByZWZlcnJlZF91c2VybmFtZSI6IjE5OTUwNjEwMjAyMDEyMTAxNSIsImdpdmVuX25hbWUiOiJSRVpBIiwiZmFtaWx5X25hbWUiOiJSSURXQU5TWUFIIiwiZW1haWwiOiJyZXphcmlkd2Fuc3lhaDEwQGdtYWlsLmNvbSJ9.ZhoTc_790VwFE2BC8zZcUR_QeJ15OMStq4Muq6Q1VU3Zz1RxdK7TDWDLqzNrflduNgMopQstDzhKScexrWQbt4LzJFK7p7k81FTUvTKBWJSykLiGGlUSwu9xycfYk2XmIZSDHF5ucMDnvwMvs2NMlb352TXO-G__YSLbiYvwK1iqr3wiYW2lc2mO00UJLAYoKjXzQPf6jLsORvcVNgelVj8RC_Jfu0rZy45tpUCf878XP5z_SVBbLA359p65bKEYY9p-dxHS1gf-bR8TlrkoOrqjJfZ4cjDB-yS2NVJLiWpQYZPKJ1VeVbySQlmu66P5OumuXjUWrFdYFGKEhNaD9A";
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://apimws.bkn.go.id:8243/apisiasn/1.0/download-dok?filePath={$filepath}",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'accept: application/json',
                    "Auth: bearer $ssoToken",
                    "Authorization: Bearer $apimToken"
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);
            if ($err) {
                return null;
            } else {
                // $result = json_decode($response);
                return $response;
            }
        } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
            // Failed to get the access token
            exit($e->getMessage());
        }
    }

    function uploadDiklatStruktural($data){
        $return = $this->genericPOST($data,"https://apimws.bkn.go.id:8243/apisiasn/1.0/diklat/save");
        return $return;
    }

    function uploadKursus($data){
        $return = $this->genericPOST($data,"https://apimws.bkn.go.id:8243/apisiasn/1.0/kursus/save");
        return $return;
    }

    function deleteDiklatStruktural($id){
        return $this->genericDelete($id,"https://apimws.bkn.go.id:8243/apisiasn/1.0/diklat/delete");
    }

    function deleteKursus($id){
        return $this->genericDelete($id,"https://apimws.bkn.go.id:8243/apisiasn/1.0/kursus/delete");
    }

    public function uploadDokumen($filepath, $id_ref_dokumen)
    {
        try {
            $apimToken = $this->getApimToken();
            $ssoToken = "eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICJBUWNPM0V3MVBmQV9MQ0FtY2J6YnRLUEhtcWhLS1dRbnZ1VDl0RUs3akc4In0.eyJleHAiOjE3MzE5NTUzODQsImlhdCI6MTczMTkxMjE4NCwianRpIjoiZmNkYWIzZWMtYmU5ZC00NDdhLTk2NGEtOWQ2MTc0ZWVhZmZjIiwiaXNzIjoiaHR0cHM6Ly9zc28tc2lhc24uYmtuLmdvLmlkL2F1dGgvcmVhbG1zL3B1YmxpYy1zaWFzbiIsImF1ZCI6ImFjY291bnQiLCJzdWIiOiI5ZTZiZDg5NC01NzNmLTRiOWEtYmNmYS04M2NkYzRhNGNiNTciLCJ0eXAiOiJCZWFyZXIiLCJhenAiOiJzZG1kaWtidWRjbGllbnQiLCJzZXNzaW9uX3N0YXRlIjoiMzkzNjI1ZTUtZTc2Yy00N2FjLWJhNTEtN2I4YzNhMjUxNjAyIiwiYWNyIjoiMSIsInJlYWxtX2FjY2VzcyI6eyJyb2xlcyI6WyJyb2xlOnNpYXNuLWluc3RhbnNpOnBlcmVtYWphYW46b3BlcmF0b3IiLCJyb2xlOnNpYXNuLWluc3RhbnNpOnNrazpvcGVyYXRvciIsInJvbGU6c2lhc24taW5zdGFuc2k6cGVyZW1hamFhbjphcHByb3ZhbCIsInJvbGU6c2lhc24taW5zdGFuc2k6a3A6b3BlcmF0b3IiLCJyb2xlOm1hbmFqZW1lbi13czpkZXZlbG9wZXIiLCJvZmZsaW5lX2FjY2VzcyIsInVtYV9hdXRob3JpemF0aW9uIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwcm9maWxhc246dmlld3Byb2ZpbCJdfSwicmVzb3VyY2VfYWNjZXNzIjp7ImFjY291bnQiOnsicm9sZXMiOlsibWFuYWdlLWFjY291bnQiLCJtYW5hZ2UtYWNjb3VudC1saW5rcyIsInZpZXctcHJvZmlsZSJdfX0sInNjb3BlIjoiZW1haWwgcHJvZmlsZSIsImVtYWlsX3ZlcmlmaWVkIjpmYWxzZSwibmFtZSI6IlJFWkEgUklEV0FOU1lBSCIsInByZWZlcnJlZF91c2VybmFtZSI6IjE5OTUwNjEwMjAyMDEyMTAxNSIsImdpdmVuX25hbWUiOiJSRVpBIiwiZmFtaWx5X25hbWUiOiJSSURXQU5TWUFIIiwiZW1haWwiOiJyZXphcmlkd2Fuc3lhaDEwQGdtYWlsLmNvbSJ9.ZhoTc_790VwFE2BC8zZcUR_QeJ15OMStq4Muq6Q1VU3Zz1RxdK7TDWDLqzNrflduNgMopQstDzhKScexrWQbt4LzJFK7p7k81FTUvTKBWJSykLiGGlUSwu9xycfYk2XmIZSDHF5ucMDnvwMvs2NMlb352TXO-G__YSLbiYvwK1iqr3wiYW2lc2mO00UJLAYoKjXzQPf6jLsORvcVNgelVj8RC_Jfu0rZy45tpUCf878XP5z_SVBbLA359p65bKEYY9p-dxHS1gf-bR8TlrkoOrqjJfZ4cjDB-yS2NVJLiWpQYZPKJ1VeVbySQlmu66P5OumuXjUWrFdYFGKEhNaD9A";
            $curl = curl_init();
            $id_ref_dokumen = $id_ref_dokumen ? $id_ref_dokumen : 891;
            $temp_path = APPPATH . '..' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'uploaded' . DIRECTORY_SEPARATOR . 'temp_file.pdf';
            $content = file_get_contents($filepath);
            file_put_contents($temp_path, $content);
            if (file_exists($temp_path)) {
                $postfields = array("file" => new CURLFile($temp_path), "id_ref_dokumen" => $id_ref_dokumen);
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://apimws.bkn.go.id:8243/apisiasn/1.0/upload-dok",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => $postfields,
                    CURLOPT_HTTPHEADER => array(
                        'Accept: application/json',
                        "Auth: bearer $ssoToken",
                        "Authorization: Bearer $apimToken"
                    ),
                ));

                $response = curl_exec($curl);
                $response = json_decode($response);
                $err = curl_error($curl);

                curl_close($curl);

                unlink($temp_path);
                if ($err) {
                    throw new Exception($err);
                } else {
                    return $response;
                }
            } else {
                throw new Exception('File not found.');
            }
        } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
            // Failed to get the access token
            exit($e->getMessage());
        }
    }
}
