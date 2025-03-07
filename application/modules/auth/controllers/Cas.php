<?php 
class Cas extends Front_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->library('phpCAS');
    }
    public function index(){
        phpCAS::setDebug();
        phpCAS::setVerbose(true);
        phpCAS::client(CAS_VERSION_3_0, "cas.sdm.kemdikbud.go.id", 443, "/cas",false);
        phpCAS::setNoCasServerValidation();
        phpCAS::handleLogoutRequests(false);	
        try {
            if (phpCAS::isAuthenticated())
            {
                $username = phpCAS::getUser();
                $userattrib = phpCAS::getAttributes();
                $userDisplayName = $userattrib['displayname'];	            				
                $data_login  =array(1, $username, '', $userattrib, $userDisplayName);
                $this->do_after_login($username,$userattrib,$userDisplayName);
                return 1;
            } else {
                phpCAS::forceAuthentication();
            }
        }catch(Exception $e){
            //echo $e;
        }	
    }
    public function test_after_login(){
        $this->setup_session("198608302010121005");
    }
    private function do_after_login($username,$userattrib,$userDisplayName){
        $ci = &get_instance();
        $this->load->library("users/auth");
        $this->load->model('users/user_model');
        
		$user_data = $ci->db->from("hris.users")->where("username",$username)->get()->first_row();
		if(!$user_data){
            $ci->load->helper('security');
            
            //ADHIARACHECK seharusnya, pke generate password kirim email, biar secure.
            $insert_data = array(
                'username'=>trim($username),
                'password'=>uniqid("gen"),
                'role_id'=>ROLE_PEGAWAI,
                'email'=>$userattrib['email'],
                'display_name'=>$userDisplayName,
                'active'=>1
            );
            $this->user_model->insert($insert_data);
            $user_data = $ci->db->from("hris.users")->where("username",$username)->get()->first_row();
        }
        else {
         
        }

        $remember = false;
        // Try to login
        if ($ci->auth->loginex($username, uniqid("gen"), $remember) === TRUE)
        {
            if ($this->settings_lib->item('auth.do_login_redirect') && !empty ($this->auth->login_destination))
            {
                //die(trim($this->auth->login_destination)."masuk log");
                Template::redirect(trim($this->auth->login_destination));
            }
            else
            {
                
                Template::redirect(trim($this->auth->login_destination));
            }
        }
        //$ci->auth->setup_session($user_data->id, trim($username), $user_data->password_hash, $userattrib['email'], ROLE_PEGAWAI, $remember=false,'', trim($username));
        ///print_r($this->session->userdata());    
        //die();
        //redirect("admin/content");
    }
}
