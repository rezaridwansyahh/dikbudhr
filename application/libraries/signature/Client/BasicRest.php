<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//require '../Config/esign.php';

class BasicRest
{

    /**
     * 
     */
    private $config;
    /**
     * 
     */
    private $error;

    /**
     * 
     */
    private $header;

    function __construct()
    {
        $this->config = include('config.php');
    }

    public function getError()
    {
        return $this->error;
    }

    public function getHeader()
    {
        return $this->header;
    }

    /**
     * 
     */
    public function send($url, $method, $queryData = null, array $files = null)
    {
        $curl = curl_init();
        $headers = [];

        if(!is_null($files)) $content = http_build_query($queryData);
        else $content = '';

        $auth = base64_encode($this->config['client_id'].':'.$this->config['client_secret']);

        if(!is_null($files)) {

            $header = "boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW";
            $body = $this->makeCurlFile($files);
        }
        else {
            $header = '';
            $body = '';
        }

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->config['host'] . $url . '?' . $content,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADERFUNCTION => function($curl, $head) use (&$headers)
            {
              $len = strlen($head);
              $head = explode(':', $head, 2);
              if (count($head) < 2) // ignore invalid headers
                return $len;
          
              $headers[strtolower(trim($head[0]))][] = trim($head[1]);
          
              return $len;
            },
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_HTTPHEADER => array(
                'cache-control: no-cache',
                'Authorization: Basic ' . $auth,
                $header
            ),
        ));
/*

curl_setopt_array($curl, array(
  CURLOPT_URL => $this->config['host'] . $url . '?' . $content,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "",
  CURLOPT_HTTPHEADER => array(
                'cache-control: no-cache',
                'Authorization: Basic ' . $auth,
                $header
            ),
));
*/
        $response = curl_exec($curl);
        //print_r($response);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            $this->error = $err;
            return false;
        } else {
            $this->header = $headers;
            $res = json_decode($response);
            if(json_last_error() == JSON_ERROR_NONE) {
                if(isset($res->error)){
                    if(isset($res->message)){
                      $this->error = $res->error."-".$res->message;  
                    }else{
                      $this->error = $res->error;
                    }
                    
                    return false;
                }else return $res;
            }
            else return $response;
        }
        
    }

    /**
     * 
     */
    private function makeCurlFile(array $files = array()){
        $body = [];
        foreach ($files as $k => $v) {
            switch (true) {
                case false === $v = realpath(filter_var($v)):
                case !is_file($v):
                case !is_readable($v):
                    continue; // or return false, throw new InvalidArgumentException
            }
            $mime = mime_content_type($v);
            $info = pathinfo($v);
            $name = $info['basename'];
            $body = array_merge($body, [$k => new \CURLFile($v, $mime, $name)]);
        }
        
        return $body;
    }
    
}
