<?php 

class Test_ldap extends Front_Controller
{
    public function __construct(){
        parent::__construct();
        
    }
    public function index(){   
        $username = '196411121992031005';
		$password = 'Rahasi@.321';
        $return = Modules::run('auth/ldap/login_ldap',$username,$password);
        echo json_encode($return);
    }
}