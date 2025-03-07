<?php
class Emu
{
    public function getUrl($url,$apikey = "",$username = "",$password = "")
    {
        $url = str_replace("&amp;", '&', urldecode(trim($url)));
         
		$header = array('Accept: '=>'application/json',
            'X-API-KEY' => $apikey);
        $cookie_fname = tempnam("/tmp", "CURLCOOKIE");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/13.0.782.215 Safari/535.1");
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_USERPWD, $username.":".$password);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);    # required for https urls
        curl_setopt($ch, CURLOPT_TIMEOUT, 120);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
		//echo $url."<br><br>";
        
        $content = curl_exec($ch);
        $response = curl_getinfo($ch);
        curl_close($ch);

        $cookie_fhandle = fopen($cookie_fname, 'r');
        $cookie_fcontent = @fread($cookie_fhandle, filesize($cookie_fname));
        fclose($cookie_fhandle);
        unlink($cookie_fname);

        $cookie_response = $this->parseNetscapeCookie($cookie_fcontent);
        //if($cookie !== '')
        //$cookie .= '; ';
        foreach ($cookie_response as $cookie_line)
        {
            foreach ($cookie_line as $key => $val)
            {
                if ($key == 'name')
                    $cookie .= $val . '=';
                if ($key == 'value')
                    $cookie .= $val . '; ';
            }
        }
        $code = $response['http_code'];
        if ($response['http_code'] == 301 || $response['http_code'] == 302)
        {
            ini_set("user_agent", "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/13.0.782.215 Safari/535.1");
            if ($headers = get_headers($response['url']))
            {
                foreach ($headers as $value)
                {
                    if (substr(strtolower($value), 0, 9) == "location:")
                    {
                        if ($post_field !== '')
                        {
                            if ($isRefererStatic == TRUE)
                                return $this->getUrl(trim(substr($value, 9, strlen($value))), $post_field, $cookie, TRUE, $urlReferer);
                            else
                                return $this->getUrl(trim(substr($value, 9, strlen($value))), $post_field, $cookie);
                        }
                        else
                        {
                            if ($isRefererStatic == TRUE)
                                return $this->getUrl(trim(substr($value, 9, strlen($value))), '', $cookie, TRUE, $urlReferer);
                            else
                                return $this->getUrl(trim(substr($value, 9, strlen($value))), '', $cookie);
                        }

                    }
                }
            }
        }

        if ((preg_match("/>[[:space:]]+window\.location\.replace\('(.*)'\)/i", $content, $value) || preg_match("/>[[:space:]]+window\.location\=\"(.*)\"/i", $content, $value)) && $javascript_loop < 5)
            return $this->getUrl($value[1], '', $cookie, $javascript_loop + 1);
        else
            return array($content, $response);
    }
	
	
    // return cookie array of name - value pair
    private function parseNetscapeCookie($cookie_string)
    {
        $cookie_struct = array();
        // line by line
        $lines = explode("\n", $cookie_string);
        // parse
        foreach ($lines as $key => $val)
        {
            $val = trim($val);
            if (substr($val, 0, 10) == '#HttpOnly_') // could be faster without the "if" though
                $val = str_ireplace('#HttpOnly_', '', $val);
            else if (substr($val, 0, 1) == '#' || strlen($val) == 0)
                continue;
            $tabs = explode("\t", $val);
            $cookie['domain'] = $tabs[0];
            $cookie['flag'] = $tabs[1];
            $cookie['path'] = $tabs[2];
            $cookie['secure'] = $tabs[3];
            $cookie['expiration'] = $tabs[4];
            $cookie['name'] = $tabs[5];
            $cookie['value'] = $tabs[6];

            $cookie_struct[] = $cookie;
        }
        return $cookie_struct;
    }

    // return cookie
    public function getCookie($url, $javascript_loop = 0, $timeout = 30)
    {
        $url = str_replace("&amp;", '&', urldecode(trim($url)));
        $cookie_fname = tempnam("/tmp", "CURLCOOKIE");
        $cookie = "";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1");
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_fname);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);    # required for https urls
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);

        $content = curl_exec($ch);
        $response = curl_getinfo($ch);
        curl_close($ch);

        $cookie_fhandle = fopen($cookie_fname, 'r');
        $cookie_fcontent = @fread($cookie_fhandle, filesize($cookie_fname));
        fclose($cookie_fhandle);
        unlink($cookie_fname);

        $cookie_response = parseNetscapeCookie($cookie_fcontent);
        foreach ($cookie_response as $cookie_line)
        {
            foreach ($cookie_line as $key => $val)
            {
                if ($key == 'name')
                    $cookie .= $val . '=';
                if ($key == 'value')
                    $cookie .= $val . ';';
            }
        }
        if ($response['http_code'] == 301 || $response['http_code'] == 302)
        {
            ini_set("user_agent", "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1");
            if ($headers = getCookie($response['url']))
                foreach ($headers as $value)
                    if (substr(strtolower($value), 0, 9) == "location:")
                        return getCookie(trim(substr($value, 9, strlen($value))));
        }

        if ((preg_match("/>[[:space:]]+window\.location\.replace\('(.*)'\)/i", $content, $value) || preg_match("/>[[:space:]]+window\.location\=\"(.*)\"/i", $content, $value)) && $javascript_loop < 5)
            return getCookie($value[1], $javascript_loop + 1);
        else
            return $cookie;
    }

}

?>