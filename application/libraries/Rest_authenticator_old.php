<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Rest_authenticator {

    public function auth($username,$password){
		$ci = &get_instance();
		$rs = $ci->db->query("select * from hris.users where username = ? ",array($username))->result('array');
		return sizeof($rs)>0;
	}

}
