<?php 

class Test_cas extends Admin_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->library('cas');
    }
    public function login(){   
        $this->cas->force_auth();
    }
    public function test_login_cas(){
        
        phpCAS::setDebug();
        phpCAS::setVerbose(true);
        //phpCAS::client(CAS_VERSION_3_0, "cas.sdm.kemdikbud.go.id", 8443, "/cas",false);
        phpCAS::setNoCasServerValidation();
        phpCAS::handleLogoutRequests(false);	
        phpCAS::forceAuthentication();	
    }
}