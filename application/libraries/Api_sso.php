<?php
require "vendor/autoload.php";
class Api_sso
{
    protected $ci;
    public function __construct($params=array()){
        $this->ci = &get_instance();
        $params_default = array(
            'client_id'                => '8f7808c0-4e5f-4fb1-80bf-5b0d81dc0897',   // personal akses client dan password client harus false 
            'client_secret'            => 'd1e7f901-2eef-45ec-86c7-4b1a2153c14d',    // The client password assigned to you by the provider
            'redirectUri'             => current_url(),
            'authorize_url' => 'http://sso-sdm.kemdikbud.go.id/oauth/authorize',
            'token_url'=>'http://sso-sdm.kemdikbud.go.id/oauth/token',
            'resourceOwner'=>''
        );

        $params2 = array_merge($params_default,$params);
        //echo json_encode($params2);
        //die();
        $this->provider = new \League\OAuth2\Client\Provider\GenericProvider([
            'clientId'                => $params2['client_id'],    // The client ID assigned to you by the provider
            'clientSecret'            => $params2['client_secret'],    // The client password assigned to you by the provider
            'redirectUri'             => $params2['redirectUri'],
            'urlAuthorize'            => $params2['authorize_url'],
            'urlAccessToken'          => $params2['token_url'],
            'urlResourceOwnerDetails' => $params2['resourceOwner'],
            'response_type' => 'token',
            'scopes' => '',
            'prompt' => 'login'
        ]);
    }
    public function getProvider()
    {
        return $this->provider;
    }
    public function authorize()
    {
        if (!isset($_GET['code'])) {
            // If we don't have an authorization code then get one
            $authUrl = $this->provider->getAuthorizationUrl();
            $this->ci->session->set_userdata('oauth2state', $this->provider->getState());
            sleep(3);
            header('Location: ' . $authUrl);
            exit;
            // Check given state against previously stored one to mitigate CSRF attack
        } elseif (empty($_GET['state']) || ($_GET['state'] !== $this->ci->session->userdata('oauth2state'))) {
            $this->ci->session->unset_userdata('oauth2state');
            exit('Invalid state session : '.$this->ci->session->userdata('oauth2state')." url=".$_GET['state']);
        } else {
                $accessToken = $this->provider->getAccessToken('authorization_code', 
                [
                'code' => $_GET['code']
                ]);
                // die($accessToken." masuk");
            try {
                $this->ci->session->set_userdata('lipi_sso_access_token', $accessToken);
                $options['headers']['content-type'] = 'application/json';
                // Optional: Now you have a token you can look up a users profile data
                $request = $this->provider->getAuthenticatedRequest(
                    'GET',
                    'http://sso-sdm.kemdikbud.go.id/api/auth/user/me',
                    $accessToken,
                    $options
                );
                $response = $this->provider->getParsedResponse($request);
                return $response;
            } catch (Exception $e) {
                echo $e->getMessage();
                exit('Ups... contact your system administrator.');
            }
        }
    }
    public function getAccessToken()
    {
        // if (isset($_SESSION['accessToken'])) {
        //     $accessToken = unserialize($_SESSION['accessToken']);
        //     // print_r($accessToken);
            if ($accessToken->hasExpired()) {
                $accessToken = $this->provider->getAccessToken('client_credentials');
                $_SESSION['accessToken'] = serialize($accessToken);
            }
        // } else {
        //     $accessToken = $this->provider->getAccessToken('client_credentials');
        //     $_SESSION['accessToken'] = serialize($accessToken);
        // }
            die($accessToken);
        return $accessToken;
    }
    public function getGetTestApi()
    {
        try {
            $accessToken = $this->getAccessToken();
            $request = $this->provider->getAuthenticatedRequest(
                'GET',
                'http://sso-sdm.kemdikbud.go.id/api/auth/user/me',
                $accessToken
            );
            $response = $this->provider->getParsedResponse($request);
            return $response;
        } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
            // Failed to get the access token
            exit($e->getMessage());
        }
    }
    public function postSendUser($username,$password)
    {
        // try {
            // $accessToken = $this->getAccessToken();
            $options['body'] = json_encode(array(
                'username' => $username,
                'password' => $password
            ));
            $options['headers']['content-type'] = 'application/json';
            $request = $this->provider->getAuthenticatedRequest(
                'POST',
                'https://sso-sdm.kemdikbud.go.id/api/apps/users/checkuser',
                $accessToken,
                $options
            );
            $response = $this->provider->getParsedResponse($request);
            print_r($response);
            return $response;
        // } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
        //     // Failed to get the access token
        //     exit($e->getMessage());
        // }
    }
    public function loginSso($username,$password){
        $options['body'] = json_encode(array(
                'username' => $username,
                'password' => $password
            ));
            $options['headers']['content-type'] = 'application/json';
            $request = $this->provider->getAuthenticatedRequest(
                'POST',
                'http://sso-sdm.kemdikbud.go.id/api/auth/login',
                $accessToken,
                $options
            );
            $response = $this->provider->getParsedResponse($request);
            return $response;

    }
}
