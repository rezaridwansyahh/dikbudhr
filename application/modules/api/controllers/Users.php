<?php 
require APPPATH . '/libraries/LIPIAPI_REST_Controller.php';
class Users extends  LIPIAPI_REST_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('pegawai/ropeg_model');
       
    }
     protected $methods = [
            'index_get' => ['level' => 10, 'limit' => 10],
            'xlist_get' => ['level' => 0, 'limit' => 10],
            'sub_get' => ['level' => 10, 'limit' => 10],
            'add_riwayat_pendidikan_post' => ['level' => 0, 'limit' => 10],
    ];


    public function login_post(){

        $this->load->model('users/user_model');
        $this->load->library('users/auth');
        $post = $this->post();
        $status = false;
        $msg = "";
        // Cek Login 
        $resultlogin = $this->auth->loginapi(
            $this->input->post('login'),
            $this->input->post('password'),1
        );
        if(isset($resultlogin->username) && $resultlogin->username != ""){
            $nip = trim($resultlogin->nip) != "" ? trim($resultlogin->nip) : trim($resultlogin->username);
            $username = trim($resultlogin->username);
            $role_id = trim($resultlogin->role_id);
            $display_name = trim($resultlogin->display_name);
            $photo = trim($resultlogin->PHOTO);
            $data_json = array(
                        'nip' => $nip,
                        'username' => $username,
                        'role_id' => $role_id,
                        'display_name' => $display_name,
                        'foto' => $photo,
                    );
            $status = true;
            $msg = "Login berhasil";
        }else{
            $data_json = array(
                        'nip' => null,
                        'username' => null,
                        'role_id' => null,
                        'display_name' => null,
                        'foto' => null
                        );
            $msg = "Login gagal";

        }
        

        $data = array(
            'status' => $status,
            'status_code' => '200',
            'msg' => $msg,
            'data' => $data_json
        );

        
        $this->response($data, 200);
    }


    public function login_admin_post(){
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('users/user_model');
        $this->load->library('users/auth');
        $post = $this->post();
        $status = false;
        $msg = "";
        // Cek Login 
        $resultlogin = $this->auth->loginapi(
            $this->input->post('login'),
            $this->input->post('password'),1
        );
        if(isset($resultlogin->username) && $resultlogin->username != "" ){
            $nip = trim($resultlogin->nip) != "" ? trim($resultlogin->nip) : trim($resultlogin->username);

            $detail = $this->pegawai_model->find_detil_with_nip($nip);

            if($resultlogin->role_id==6 || $resultlogin->role_id==4 || $resultlogin->role_id==1){
                // cek pejabat cuti
                $this->load->model('izin_pegawai/vw_pejabat_cuti_model');
                $is_pejabat_cuti = $this->vw_pejabat_cuti_model->count_me(trim($resultlogin->username));

                
                $username = trim($resultlogin->username);
                $role_id = trim($resultlogin->role_id);
                $display_name = trim($resultlogin->display_name);
                $photo = trim($resultlogin->PHOTO);
                $data_json = array(
                            'nip' => $nip,
                            'username' => $username,
                            'role_id' => $role_id,
                            'display_name' => $display_name,
                            'foto' => $photo,
                            'is_pejabat' => $is_pejabat_cuti,
                            'login'=>$resultlogin,
                            'unor'=>$detail->UNOR_ID
                        );
                $status = true;
                $msg = "Login berhasil";

            }else{
                $data_json = array(
                    'nip' => null,
                    'username' => $resultlogin->username,
                    'role_id' =>  $resultlogin->role_id,
                    'display_name' => $resultlogin->display_name,
                );
                $msg = "Login Gagal, bukan admin satker";
            }
            
        }else{
            $data_json = array(
                        'nip' => null,
                        'username' => null,
                        'role_id' => null,
                        'display_name' => null,
                        'foto' => null
                        );
            $msg = "Login gagal";

        }
        

        $data = array(
            'status' => $status,
            'status_code' => '200',
            'msg' => $msg,
            'data' => $data_json
        );

        
        $this->response($data, 200);
    }

}
