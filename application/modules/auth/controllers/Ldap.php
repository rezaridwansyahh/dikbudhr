<?php 
class Ldap extends Front_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->library('phpCAS');
    }
    public function login_ldap($username,$password){
       
        $_CASACTIVEDIRECTORY_ = 'ad.sdm.kemdikbud.go.id';
        $_CASACTIVEDIRECTORYSTAFFDN_ = 'OU=Staff,DC=ad,DC=sdm,DC=kemdikbud,DC=go,DC=id';
        $_CASACTIVEDIRECTORYSERVICEDN_ = 'OU=Layanan,DC=ad,DC=sdm,DC=kemdikbud,DC=go,DC=id';
        $_CASACTIVEDIRECTORYROLEDN_ = 'OU=Roles,DC=ad,DC=sdm,DC=kemdikbud,DC=go,DC=id';

        $ldapconn = ldap_connect($_CASACTIVEDIRECTORY_)
        or die("could not connect to LDAP server.");

        $ldap_staftdn = $_CASACTIVEDIRECTORYSTAFFDN_;
        $ldap_roledn = $_CASACTIVEDIRECTORYROLEDN_;
        $ldap_servicedn = $_CASACTIVEDIRECTORYSERVICEDN_;
        $userldap = "CN=".$username.",".$ldap_staftdn;
        
        //auth using reinput password credential (membandingkan inputan user dengan data induk pada active directory)
        $ldapbind = ldap_bind($ldapconn, $userldap, $password);	
        if(($username != '') && ($password != '')){
            if ($ldapbind) {
                $filter= "employeeID=$username*";
                $justthese = array("displayname", "employeeID","mail");
                
                $sr = ldap_search($ldapconn, $ldap_staftdn, $filter, $justthese) or die ("Search failed");
                $info = ldap_get_entries($ldapconn, $sr);
                $displayName = ucwords(strtolower($info[0]["displayname"][0]));
                $email = strtolower(strtolower($info[0]["mail"][0]));
                $this->do_after_login($username,$password,$email,$displayName);
                return $info;
            }
            else {
                		
            }
        }	
    }
    
    private function do_after_login($username,$password,$email,$userDisplayName){
        $ci = &get_instance();
        $this->load->library("users/auth");
        $this->load->model('users/user_model');
        
		$user_data = $ci->db->from("hris.users")->where("username",$username)->get()->first_row();
		if(!$user_data){
            $ci->load->helper('security');
            //ADHIARACHECK seharusnya, pke generate password kirim email, biar secure.
            $insert_data = array(
                'username'=>trim($username),
                'password'=>trim($password),
                'role_id'=>ROLE_PEGAWAI,
                'email'=>$email,
                'display_name'=>$userDisplayName,
                'active'=>1
            );
            $this->user_model->insert($insert_data);
            $user_data = $ci->db->from("hris.users")->where("username",$username)->get()->first_row();
        }
        else {
            $update_data = array('display_name'=>$userDisplayName,'email'=>$email,'password'=>trim($password));
            $this->user_model->update($user_data->id,$update_data);
            $user_data = $ci->db->from("hris.users")->where("username",$username)->get()->first_row();
        }
        
        $ci->auth->setup_session($user_data->id, trim($username), $user_data->password_hash,$email, ROLE_PEGAWAI, $remember=false,'', trim($username));
       
    }
}