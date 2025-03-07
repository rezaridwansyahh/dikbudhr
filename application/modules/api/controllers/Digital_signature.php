<?php 
require APPPATH . '/libraries/LIPIAPI_REST_Controller.php';
class Digital_signature extends  LIPIAPI_REST_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->library('form_validation');
	$this->load->model('pegawai/ropeg_model');
       
    }
     protected $methods = [
			'index_get' => ['level' => 10, 'limit' => 10],
            'xlist_get' => ['level' => 0, 'limit' => 10],
			'sub_get' => ['level' => 10, 'limit' => 10],
    ];
	public function resume_get(){
        $this->load->model("sk_ds/sk_ds_model");
        $penandatangan = $this->get('penandatangan');

        $jml_blm_koreksi = 0;
        $jml_sdh_koreksi = 0;
        $jml_blm_ttd = 0;
        $jml_sdh_ttd = 0;

        $jml_blm_ttd = $this->sk_ds_model->count_all_api("",true,$penandatangan);
        $jml_sdh_ttd = $this->sk_ds_model->count_all_sdhttd_api("",true,$penandatangan);


        $output = array(
            'jml_blm_koreksi' => $jml_blm_koreksi,
            'jml_sdh_koreksi'=>$jml_sdh_koreksi,
            'jml_blm_ttd'=>$jml_blm_ttd,
            'jml_sdh_ttd'=>$jml_sdh_ttd
        );
        $this->response($output, REST_Controller::HTTP_OK);

    }
	// belum ttd
	public function list_get(){

        $this->load->model("sk_ds/sk_ds_model");
        $this->load->model("pegawai/pegawai_model");
        $appKeyData = $this->getApplicationKey();
        //print_r($appKeyData);
        $satkers = array();
        if($appKeyData->satker_auth){
            $satkers = explode(',',$appKeyData->satker_auth);
        }
		
		$output = array(
            'success'=>false,
            'msg'=>'Unknown error'
        );
        $unor_id = $this->get('unor_id');
        $penandatangan = $this->get('penandatangan');
        $start = (int)$this->get('start');
        $limit = $this->get('limit');
        
        $this->pegawai_model->select("PNS_ID,NIK,NIP_BARU");
        $pegawai_login = $this->pegawai_model->find_by("NIP_BARU",trim($penandatangan));
        $PNS_ID = isset($pegawai_login->PNS_ID) ? $pegawai_login->PNS_ID : "";

        if ($start === NULL)
        {
           $start = 0;
        }
        else {
            if($start<0){
                $output['msg'] = 'Parameter start harus >=0 ';
                $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
            }
        }

        if ($limit === NULL)
        {
           $limit = 10;
        }
        else {
            if($limit==-1){
                // no problem
            }
            else if($limit>1000){
                $output['msg'] = 'Parameter limit maksimal 1000 ';
                $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
            }
            else if($limit<0){
                $output['msg'] = 'Parameter limit harus >=0 ';
                $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
            }

        }
        
		$filter_satkers = array();
		if(sizeof($satkers)==0){ // has ALL PRIV
			$filter_satkers[] = $unor_id;
		}
		else {
			$found_priv = false;
			foreach($satkers as $satker){
				if($satker == $unor_id){
					$found_priv = true;
				}
			}
			if(!$found_priv){
				$output['msg'] = 'Tidak ada akses untuk pegawai satker ';
                $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
				return;
			}	
			else {
				$filter_satkers[] = $unor_id;
			}
		}
        $total= $this->sk_ds_model->count_all_api("",true,$PNS_ID);
        if($limit==-1){

        }
        else {
            $this->db->limit($limit,$start);
        }
        $datas = $this->sk_ds_model->find_all_api("",true,$PNS_ID);
        if(empty($datas))
            $datas = null;
        $this->db->flush_cache();
         $output = array(
            'success' => true,
            'total'=>$total,
            'data'=>$datas
        );
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function listsudahttd_get(){
        $this->load->model("sk_ds/sk_ds_model");
        $this->load->model("pegawai/pegawai_model");
        $appKeyData = $this->getApplicationKey();
        //print_r($appKeyData);
        $satkers = array();
        if($appKeyData->satker_auth){
            $satkers = explode(',',$appKeyData->satker_auth);
        }
        
        $output = array(
            'success'=>false,
            'msg'=>'Unknown error'
        );
        $unor_id = $this->get('unor_id');
        $penandatangan = $this->get('penandatangan');
        $start = (int)$this->get('start');
        $limit = $this->get('limit');

        $this->pegawai_model->select("PNS_ID,NIK,NIP_BARU");
        $pegawai_login = $this->pegawai_model->find_by("NIP_BARU",trim($penandatangan));
        $PNS_ID = isset($pegawai_login->PNS_ID) ? $pegawai_login->PNS_ID : "";
        if ($start === NULL)
        {
           $start = 0;
        }
        else {
            if($start<0){
                $output['msg'] = 'Parameter start harus >=0 ';
                $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
            }
        }

        if ($limit === NULL)
        {
           $limit = 10;
        }
        else {
            if($limit==-1){
                // no problem
            }
            else if($limit>1000){
                $output['msg'] = 'Parameter limit maksimal 1000 ';
                $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
            }
            else if($limit<0){
                $output['msg'] = 'Parameter limit harus >=0 ';
                $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
            }

        }
        
        $filter_satkers = array();
        if(sizeof($satkers)==0){ // has ALL PRIV
            $filter_satkers[] = $unor_id;
        }
        else {
            $found_priv = false;
            foreach($satkers as $satker){
                if($satker == $unor_id){
                    $found_priv = true;
                }
            }
            if(!$found_priv){
                $output['msg'] = 'Tidak ada akses untuk pegawai satker ';
                $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
                return;
            }   
            else {
                $filter_satkers[] = $unor_id;
            }
        }
        $total= $this->sk_ds_model->count_all_sdhttd_api("",true,$PNS_ID);
        if($limit==-1){

        }
        else {
            $this->db->limit($limit,$start);
        }
        $datas = $this->sk_ds_model->find_all_sdh_ttd_api("",true,$PNS_ID);
        if(empty($datas))
            $datas = null;
        $this->db->flush_cache();
         $output = array(
            'success' => true,
            'total'=>$total,
            'data'=>$datas
        );
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function listbelum_koreksi_get(){
        $this->load->model("sk_ds/sk_ds_model");
        $appKeyData = $this->getApplicationKey();
        //print_r($appKeyData);
        $satkers = array();
        if($appKeyData->satker_auth){
            $satkers = explode(',',$appKeyData->satker_auth);
        }
        
        $output = array(
            'success'=>false,
            'msg'=>'Unknown error'
        );
        $unor_id = $this->get('unor_id');
        $start = (int)$this->get('start');
        $korektor = $this->get('korektor'); // nip korektor
        $lokasi_file = $this->get('lokasi_file'); // lokasi file
        $limit = $this->get('limit');
         
        if ($start === NULL)
        {
           $start = 0;
        }
        else {
            if($start<0){
                $output['msg'] = 'Parameter start harus >=0 ';
                $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
            }
        }

        if ($limit === NULL)
        {
           $limit = 10;
        }
        else {
            if($limit==-1){
                // no problem
            }
            else if($limit>1000){
                $output['msg'] = 'Parameter limit maksimal 1000 ';
                $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
            }
            else if($limit<0){
                $output['msg'] = 'Parameter limit harus >=0 ';
                $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
            }

        }
        
        $filter_satkers = array();
        if(sizeof($satkers)==0){ // has ALL PRIV
            $filter_satkers[] = $unor_id;
        }
        else {
            $found_priv = false;
            foreach($satkers as $satker){
                if($satker == $unor_id){
                    $found_priv = true;
                }
            }
            if(!$found_priv){
                $output['msg'] = 'Tidak ada akses untuk pegawai satker ';
                $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
                return;
            }   
            else {
                $filter_satkers[] = $unor_id;
            }
        }
        if($lokasi_file != ""){
            $this->sk_ds_model->where("lokasi_file",$lokasi_file);   
        }
        $total= $this->sk_ds_model->count_all_api_blmkoreksi($korektor,"",true);
        if($limit==-1){

        }
        else {
            $this->db->limit($limit,$start);
        }
        if($lokasi_file != ""){
            $this->sk_ds_model->where("lokasi_file",$lokasi_file);   
        }
        $datas = $this->sk_ds_model->find_all_api_blm_koreksi($korektor,"",true);
        if(empty($datas))
            $datas = null;
        $this->db->flush_cache();
         $output = array(
            'success' => true,
            'total'=>$total,
            'data'=>$datas
        );
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function listsudah_koreksi_get(){
        $this->load->model("sk_ds/sk_ds_model");
        $appKeyData = $this->getApplicationKey();
        //print_r($appKeyData);
        $satkers = array();
        if($appKeyData->satker_auth){
            $satkers = explode(',',$appKeyData->satker_auth);
        }
        
        $output = array(
            'success'=>false,
            'msg'=>'Unknown error'
        );
        $unor_id = $this->get('unor_id');
        $start = (int)$this->get('start');
        $korektor = $this->get('korektor'); // nip korektor
        //die("koreksor");
        $limit = $this->get('limit');
         
        if ($start === NULL)
        {
           $start = 0;
        }
        else {
            if($start<0){
                $output['msg'] = 'Parameter start harus >=0 ';
                $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
            }
        }

        if ($limit === NULL)
        {
           $limit = 10;
        }
        else {
            if($limit==-1){
                // no problem
            }
            else if($limit>1000){
                $output['msg'] = 'Parameter limit maksimal 1000 ';
                $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
            }
            else if($limit<0){
                $output['msg'] = 'Parameter limit harus >=0 ';
                $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
            }

        }
        
        $filter_satkers = array();
        if(sizeof($satkers)==0){ // has ALL PRIV
            $filter_satkers[] = $unor_id;
        }
        else {
            $found_priv = false;
            foreach($satkers as $satker){
                if($satker == $unor_id){
                    $found_priv = true;
                }
            }
            if(!$found_priv){
                $output['msg'] = 'Tidak ada akses untuk pegawai satker ';
                $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
                return;
            }   
            else {
                $filter_satkers[] = $unor_id;
            }
        }
        $total= $this->sk_ds_model->count_all_api_sdhkoreksi($korektor,"",true);
        if($limit==-1){

        }
        else {
            $this->db->limit($limit,$start);
        }
        $datas = $this->sk_ds_model->find_all_api_sdh_koreksi($korektor,"",true);
        if(empty($datas))
            $datas = null;
        $this->db->flush_cache();
         $output = array(
            'success' => true,
            'total'=>$total,
            'data'=>$datas
        );
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function detail_get(){
        $this->load->model("sk_ds/sk_ds_model");
        $id_file = $this->input->get('id_file');
        $file_name = $id_file.".pdf";
        $output = array(
            'success'=>false,
            'msg'=>'Unknown error'
        );
        if ($id_file == NULL)
        {
            $output['msg'] = 'Parameter id_file harus diisi';
            $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }
        $data = $this->sk_ds_model->find($id_file);
        $b64 = isset($data->teks_base64) ? $data->teks_base64 : "";
        $bin = base64_decode($b64, true);
        if (strpos($bin, '%PDF') !== 0) {
          throw new Exception('Missing the PDF file signature');
        }

        $settings = $this->db->query("
            select 
                s.\"value\" from hris.settings s
                
                where s.\"name\" = ? 
            
            ",array("site.pathsk"))->first_row();

        $direktori = trim($settings->value);
        if (file_exists($direktori.$file_name)) {
           unlink($direktori.$file_name);
        }
        file_put_contents(trim($direktori).$file_name, $bin);  

        $output = array(
            'success' => true,
            'data'=>$data
        );
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function cekfile_get(){
        $this->load->model("sk_ds/sk_ds_model");
        $this->load->model('ds_riwayat/ds_riwayat_model');
        $id_file = $this->input->get('id_file');
        $ses_nip = $this->input->get('nip') ? $this->input->get('nip') : "-";
        $ttd = $this->input->get('ttd') ? $this->input->get('ttd') : "-";
        $file_name = $id_file.".pdf";
        $output = array(
            'success'=>false,
            'msg'=>'Unknown error'
        );
        if ($id_file == NULL)
        {
            $output['msg'] = 'Parameter id_file harus diisi';
            $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }
        $data = $this->sk_ds_model->find($id_file);
        $b64 = isset($data->teks_base64) ? $data->teks_base64 : "";
        $id_pegawai_ttd = isset($data->id_pegawai_ttd) ? $data->id_pegawai_ttd : "";
        $bin = base64_decode($b64, true);
        if (strpos($bin, '%PDF') !== 0) {
          throw new Exception('Missing the PDF file signature');
        }

        $settings = $this->db->query("
            select 
                s.\"value\" from hris.settings s
                
                where s.\"name\" = ? 
            
            ",array("site.pathsk"))->first_row();

        $direktori = trim($settings->value);
        if (file_exists($direktori.$file_name)) {
           unlink($direktori.$file_name);
        }
        file_put_contents(trim($direktori).$file_name, $bin);  

        $output = array(
            'success' => true,
            'data'=>"not display"
        );
        // add log riwayat
        $catatan = "Membuka file SK oleh korektor";
        if($ttd == "1"){
            $catatan = "Membuka file SK oleh penandatangan";
        }
        $this->save_riwayat_ds($id_file,$ses_nip,$catatan,"");
        // end log riwayat
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function login_token_post()
    {

        date_default_timezone_set("Asia/Bangkok");
        $login = $this->input->post('login');
        $password = $this->input->post('password');
        if($login != "" and $password != ""){
            $token = "";
            $this->load->library('Api_digital');
            $ClassApidigital = new Api_digital();
            $returnlogin  = $ClassApidigital->getTokenBarier($login,$password);
            $return = json_decode($returnlogin);
            if($return->status == "200"){
                $status = true;
                $token = $return->data->token;
                $msg = "Login Berhasil";
            }else{
                $status = false;
                $msg = $return->message;
                $response ['status']= $status;
                $response ['msg']= $msg;
               // $this->response($response, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response 
            }
             
        }else{
            $status = false;
            $msg = "Lengkapi data user dan password";
            $response ['status']= $status;
            $response ['msg']= $msg;
            //$this->response($response, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response 
        }
        
        $response ['status']= $status;
        $response ['token'] = $token;
        $response ['msg']= $msg;
        $this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function dapatkantoken_post()
    {
        date_default_timezone_set("Asia/Bangkok");
         
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        if ($username == "") {
            $response['success'] = false;
            $response['msg'] = "Silahkan isi user Cert";
            $this->response($response, REST_Controller::HTTP_BAD_REQUEST); // OK (200) being the HTTP response code
            die();
        }

        $this->load->model('settings/settings_model');

        $settings = $this->db->query("
            select 
                s.\"value\" from hris.settings s
                
                where s.\"name\" = ? 
            
            ",array("site.barierds"))->first_row();

        $TokenBarier = trim($settings->value);
        $this->load->library('Api_digital');
        $ClassApidigital = new Api_digital();
        $msg = "";
        $returnlogin  = $ClassApidigital->getTokenFromUserCert($TokenBarier,$username);

        $token = "";
            $return = json_decode($returnlogin);
             
            if($return->status){
                $status = true;
                $msg = $return->msg;
                $token = $return->token;
            }else{
                $status = false;
                $msg = "Token tidak ditemukan";
                 
               //$this->response($response, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response 
            }
        $response ['success']= $status;
        $response ['msg']= $msg;
        $response ['token']= $token;
        $this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code

        exit();
    }
    public function gettoken_cert_post()
    {

        date_default_timezone_set("Asia/Bangkok");
        $usercert = $this->input->post('usercert');
        $barier = $this->input->post('barier');
        $msg = "";
        $token2 = "";
        //die($usercert." : ".$barier." ini");
        if($usercert != "" and $barier != ""){
            //die($barier);
            $this->load->library('Api_digital');
            $ClassApidigital = new Api_digital();
            $return  = $ClassApidigital->getTokenAksesUserCert($barier,$usercert);
            //print_r($return);
            //die();

            if($return->code == "200"){
                $status = true;
                $msg = "Login Berhasil";

                $token2s = explode(",",$return->message);
                $token2 = $token2s[0]; 
                $msg = $msg." ".$token2s[1]; 
            }else{
                $status = false;
                $msg = $return->message;
                if($msg =="")
                    $msg = "Gagal mendapatkan Token, Cek kembali user Cert anda";
                $response ['status']= $status;
                $response ['msg']= $msg;
            }
             
        }else{
            $status = false;
            $msg = "Silahkan lengkapi data usercert dan barier, token Cert usercert : ".$usercert." ".$barier;
            $response ['status']= $status;
            $response ['msg']= $msg;
            //$this->response($response, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response 
        }
        
        $response ['status']= $status;
        $response ['token'] = $token2;
        $response ['msg']= $msg;
        $this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function get_list_cert_post()
    {

        date_default_timezone_set("Asia/Bangkok");
        $usercert = $this->input->post('usercert');
        $barier = $this->input->post('barier');
        $msg = "";
        $cert = "";
        
        if($usercert != "" and $barier != ""){
            //die($barier);
            $this->load->library('Api_digital');
            $ClassApidigital = new Api_digital();
            $return  = $ClassApidigital->getListCert_login($barier,$usercert);
            //print_r($return);
            //die();
            if($return->code == "200"){
                $status = true;
                $msg = $return->message;
                $cert = $return->data[0]->id;
                 
            }else{
                $status = false;
                $msg = $return->message;
                if($msg =="")
                    $msg = "Gagal";
                $response ['status']= $status;
                $response ['msg']= $msg;
            }
             
        }else{
            $status = false;
            $msg = "Lengkapi data usercert dan barier list cert";
            $response ['status']= $status;
            $response ['msg']= $msg;
            //$this->response($response, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response 
        }
        
        $response ['status']= $status;
        $response ['cert'] = $cert;
        $response ['msg']= $msg;
        $this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function send_sign_post()
    {

        date_default_timezone_set("Asia/Bangkok");
        $this->load->model("sk_ds/sk_ds_model");
        //$this->load->library("settings.php");
        $settings = $this->db->query("
            select 
                s.\"value\" from hris.settings s
                
                where s.\"name\" = ? 
            
            ",array("site.pathsk"))->first_row();

        $direktori = trim($settings->value);
        //die($direktori);
        $id_file = $this->input->post('id_file');
        $id_ttd = $this->input->post('id_ttd');
        
        $datadetil = $this->sk_ds_model->find($id_file);
        if($datadetil->is_signed != "1"){
            $this->load->library('Api_digital');
            $ClassApidigital = new Api_digital();
            $retuanbase64  = $ClassApidigital->signsk($direktori.$id_file);
            // update data
            $status = false;
            $msg = "";
            if($retuanbase64 != ""){
                $data = array();
                $data["teks_base64"]     = $retuanbase64;
                $data["is_signed"]      = "1";
                $this->sk_ds_model->update($id_file,$data);

                // generate file
                //echo $retuanbase64;
                $new_file_name = $id_file.".pdf";
                $bin = base64_decode($retuanbase64, true);
                # Perform a basic validation to make sure that the result is a valid PDF file
                # Be aware! The magic number (file signature) is not 100% reliable solution to validate PDF files
                # Moreover, if you get Base64 from an untrusted source, you must sanitize the PDF contents
                if (strpos($bin, '%PDF') !== 0) {
                  throw new Exception('Missing the PDF file signature');
                }
                
                if (file_exists($direktori.$new_file_name)) {
                   unlink($direktori.$new_file_name);
                }
                file_put_contents(trim($direktori).$new_file_name, $bin);  
                // end 
                $status = true;
                $msg = "SK telah Berhasil ditanda Tangan digital";
            }else{
                $status = false;
                $msg = "Ada kesalahan";
            }
        }else{
            $status = false;
            $msg = "Status SK sudah di tandatangan";
        }
        
        $response ['status']= $status;
        $response ['msg']= $msg;
        $this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function send_sign_sk_post()
    {

        date_default_timezone_set("Asia/Bangkok");
        $this->load->model("sk_ds/sk_ds_model");
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('sk_ds/log_ds_model');
        $this->load->model('ds_riwayat/ds_riwayat_model');
        //$this->load->library("settings.php");
        $settings = $this->db->query("
            select 
                s.\"value\" from hris.settings s
                
                where s.\"name\" = ? 
            
            ",array("site.pathsk"))->first_row();

        $direktori = trim($settings->value);
        //die($direktori);
        $settings = $this->db->query("
            select 
                s.\"value\" from hris.settings s
                
                where s.\"name\" = ? 
            
            ",array("site.pathspesimen"))->first_row();
        // get NIK
        $direktorispesimen = trim($settings->value);

        $id_file = $this->input->post('id_file');
        $token = $this->input->post('token');
        //$usercert = $this->input->post('usercert');
        $passphrase = $this->input->post('passphrase');
        $NIP = $this->input->post('NIP');
        $data = $this->sk_ds_model->find($id_file);

        $this->pegawai_model->select("NIK,NIP_BARU");
        $pegawai_login = $this->pegawai_model->find_by("NIP_BARU",trim($NIP));
        $NIK = isset($pegawai_login->NIK) ? trim($pegawai_login->NIK) : "";

        // add log riwayat
        $this->save_riwayat_ds($id_file,$NIP,"Percobaan tanda tangan","");
        // end log riwayat
        

        if($data->is_signed != "1"){

            // create pdf jika belum ada
            $file_name = $id_file.".pdf";
            $b64 = $data->teks_base64;
            $bin = base64_decode($b64, true);

            if (file_exists($direktori.$file_name)) {
               unlink($direktori.$file_name);
            }

            if (strpos($bin, '%PDF') !== 0) {
              throw new Exception('Missing the PDF file signature');
            }
           // if (!file_exists($direktori.$file_name)) {
               file_put_contents($direktori.$file_name, $bin);    
            //}
            // end create pdf
            
            $this->load->library('signature/esign');
            $esign = new Esign();
            if($NIK != "" && $passphrase != ""){

                $spesimen = "default.png";
                if (file_exists($direktorispesimen.$NIP.'.png')) {
                    $spesimen = $NIP.'.png';
                }
                $return = $esign->sign($NIK,$passphrase,$direktori.$file_name,$direktorispesimen.$spesimen);
                if($return == 1){

                    // cek file signed
                    $filesigned = $direktori.$id_file."_signed.pdf";
                    $base64signed = "";
                    if (file_exists($filesigned)) {
                        $base64signed = chunk_split(base64_encode(file_get_contents($filesigned)));
                    }

                    $status = true;
                    $msg = "Dokumen sudah ditandatangan";
                    $data = array();
                    if($base64signed != ""){
                        $data["teks_base64_sign"]      = $base64signed;    
                    }
                    $data["is_signed"]      = "1";
                    $this->sk_ds_model->update($id_file,$data);
                    $this->save_log($id_file,$NIK,"Berhasil tanda tangan (Apps)",2,0);
                    // add log riwayat
                    $this->save_riwayat_ds($id_file,$NIP,"Berhasil tanda tangan","");
                    // end log riwayat
                }else{
                    //$this->save_log($id_file,$NIK,$return." Error",1);
                    $msg = $return;
                    $this->save_log($id_file,$NIK,$msg."(Apps)",1,0);
                    // add log riwayat
                    $this->save_riwayat_ds($id_file,$NIP,"Gagal tanda tangan",$msg);
                    // end log riwayat
                }
            }else{
                $msg = "Silahkan Lengkapi NIK dan Passphrase ";
            }
        }else{
            $status = false;
            $msg = "Status SK sudah di tandatangan";
        }
        
        $response ['status']= $status;
        $response ['msg']= $msg;
        $this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function send_sign_sk_all_post()
    {

        date_default_timezone_set("Asia/Bangkok");
        $this->load->model("sk_ds/sk_ds_model");
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('sk_ds/log_ds_model');
        $this->load->model('ds_riwayat/ds_riwayat_model');
        //$this->load->library("settings.php");
        $settings = $this->db->query("
            select 
                s.\"value\" from hris.settings s
                
                where s.\"name\" = ? 
            
            ",array("site.pathsk"))->first_row();

        $direktori = trim($settings->value);
        //die($direktori);
        $settings = $this->db->query("
            select 
                s.\"value\" from hris.settings s
                
                where s.\"name\" = ? 
            
            ",array("site.pathspesimen"))->first_row();
        // get NIK
        $direktorispesimen = trim($settings->value);

        $id_file = $this->input->post('id_file');
        $token = $this->input->post('token');
        //$usercert = $this->input->post('usercert');
        $passphrase = $this->input->post('passphrase');
        $NIP = $this->input->post('NIP');

        //$this->pegawai_model->select("NIK,NIP_BARU");
        $pegawai_login = $this->pegawai_model->find_by("NIP_BARU",trim($NIP));
        $NIK = isset($pegawai_login->NIK) ? trim($pegawai_login->NIK) : "";

        $data = json_encode($id_file,true);
        $replace = array("[","]"," ","\"");
        $id_file = str_replace($replace, '', $data);
        //echo $id_file;
        $aid_file = explode(",", $id_file);
        $jmlberhasil = 0;
        $jmlgagal = 0;
        if($passphrase == ""){
            $response ['status']= false;
            $response ['msg']= "Silahkan Masukan passphrase";
            $this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        
        if(count($aid_file) > 0){
            for($i=0;$i<count($aid_file);$i++){
                $file_pdf = $aid_file[$i];
                //$this->sk_ds_model->select("*");
                $data = $this->sk_ds_model->find($file_pdf);
                if(isset($data->is_signed) && $data->is_signed != "1"){

                    // create pdf jika belum ada
                    $file_name = $file_pdf.".pdf";
                    $b64 = $data->teks_base64;
                    $bin = base64_decode($b64, true);
                    if (strpos($bin, '%PDF') !== 0) {
                      throw new Exception('Missing the PDF file signature');
                    }
                    if (!file_exists($direktori.$file_name)) {
                       file_put_contents($direktori.$file_name, $bin);    
                    }
                    // end create pdf

                    $this->load->library('signature/esign');
                    $esign = new Esign();
                    if($NIK != "" && $passphrase != ""){
                        $spesimen = "default.png";
                        if (file_exists($direktorispesimen.$NIP.'.png')) {
                            $spesimen = $NIP.'.png';
                        }
                        // add log riwayat
                        $this->save_riwayat_ds($file_pdf,$NIP,"Percobaan tanda tangan dari check all","");
                        // end log riwayat
                        $return = $esign->sign($NIK,$passphrase,$direktori.$file_name,$direktorispesimen.$spesimen);
                        if($return == 1){

                            // cek file signed
                            $filesigned = $direktori.$file_pdf."_signed.pdf";
                            $base64signed = "";
                            if (file_exists($filesigned)) {
                                $base64signed = chunk_split(base64_encode(file_get_contents($filesigned)));
                            }

                            $status = true;
                            $msg = "Dokumen sudah ditandatangan";
                            $data = array();
                            if($base64signed != ""){
                                $data["teks_base64_sign"]      = $base64signed;    
                            }
                            $data["is_signed"]      = "1";
                            $this->sk_ds_model->update($file_pdf,$data);
                            $jmlberhasil++;

                            $this->save_log($file_pdf,$NIK,"Berahasil tanda tangan (Apps)",2,0);
                            // add log riwayat
                            $this->save_riwayat_ds($file_pdf,$NIP,"Tandatangan berhasil dari checkall","");
                            // end log riwayat
                        }else{
                            $status = false;
                            $msg = $return;
                            $this->save_log($file_pdf,$NIK,$msg."(Apps)",1,0);
                            // add log riwayat
                            $this->save_riwayat_ds($file_pdf,$NIP,"Tandatangan gagal dari checkall",$msg);
                            // end log riwayat
                            $jmlgagal++;
                            $response ['status']= $status;
                            $response ['msg']= $msg;
                            $this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
                        }
                    } 
                }else{
                    $status = false;
                    $msg = "Status SK sudah di tandatangan";
                    $jmlgagal++;
                }
            }
        }else{
            $response ['status']= false;
            $response ['msg']= "Silahkan Checklist dokumen";
            $this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        //$status = true;
       // $this->save_log("",$NIK,"Sebanyak ".$jmlberhasil." dokumen telah ditandatangan secara digital",2,$NIP);
        $msg = "Sebanyak ".$jmlberhasil." dokumen telah ditandatangan secara digital";
        
        
        
        $response ['status']= $status;
        $response ['msg']= $msg;
        $this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    private function save_log($ID_FILE = "",$NIK = "",$KETERANGAN = "",$STATUS = "",$NIP = "")
    {
        date_default_timezone_set("Asia/Bangkok");
        // get data detil
        $data = array();
        if($NIK != ""){
            $data["ID_FILE"]    = $ID_FILE;
            $data["NIK"]        = $NIK;
            $data["KETERANGAN"]          = $KETERANGAN;
            $data["CREATED_DATE"]        = date("Y-m-d h:i:s");
            if($NIP != ""){
                $data["CREATED_BY"]          = $NIP;
            }
            $data["STATUS"]          = $STATUS;
            
            $this->log_ds_model->insert($data);
        }
    }
    private function save_riwayat_ds($ID_FILE = "",$NIP = "",$TINDAKAN = "",$CATATAN_TINDAKAN = "")
    {
        date_default_timezone_set("Asia/Bangkok");
        // get data detil
        $data = array();
        if($NIP != ""){
            $data["id_file"]    = $ID_FILE;
            $data["id_pemroses"]        = $NIP;
            $data["tindakan"]          = $TINDAKAN;
            $data["catatan_tindakan"]          = $CATATAN_TINDAKAN;
            $data["waktu_tindakan"]        = date("Y-m-d h:i:s");
            $data["akses_pengguna"]          = "Apps";
            $this->ds_riwayat_model->insert($data);
        }
    }
    public function send_sign_sk_bppt_post()
    {

        date_default_timezone_set("Asia/Bangkok");
        $this->load->model("sk_ds/sk_ds_model");
        //$this->load->library("settings.php");
        $settings = $this->db->query("
            select 
                s.\"value\" from hris.settings s
                
                where s.\"name\" = ? 
            
            ",array("site.pathsk"))->first_row();

        $direktori = trim($settings->value);
        //die($direktori);
        $settings = $this->db->query("
            select 
                s.\"value\" from hris.settings s
                
                where s.\"name\" = ? 
            
            ",array("site.barierds"))->first_row();

        $barierds = trim($settings->value);
        $id_file = $this->input->post('id_file');
        $token = $this->input->post('token');
        $usercert = $this->input->post('usercert');
        $passphrase = $this->input->post('passphrase');
        $datadetil = $this->sk_ds_model->find($id_file);
        if($datadetil->is_signed != "1"){
            $this->load->library('Api_digital');
            $ClassApidigital = new Api_digital();
            $retuanbase64 = "";
            $return  = $ClassApidigital->signsk_all($barierds,$usercert,$passphrase,$direktori.$id_file,$token);
            $retuanbase64 = json_decode($return);
            //print_r($retuanbase64);
            //die();
            // update data
            $status = false;
            $msg = "";
            if($retuanbase64->status){
                $data = array();
                $data["teks_base64"]     = $retuanbase64->base_64;
                $data["is_signed"]      = "1";
                $this->sk_ds_model->update($id_file,$data);

                // generate file
                //echo $retuanbase64;
                $new_file_name = $id_file.".pdf";
                $bin = base64_decode($retuanbase64->base_64, true);
                # Perform a basic validation to make sure that the result is a valid PDF file
                # Be aware! The magic number (file signature) is not 100% reliable solution to validate PDF files
                # Moreover, if you get Base64 from an untrusted source, you must sanitize the PDF contents
                if (strpos($bin, '%PDF') !== 0) {
                  throw new Exception('Missing the PDF file signature');
                }
                if (file_exists($direktori.$new_file_name)) {
                   unlink($direktori.$new_file_name);
                }
                file_put_contents(trim($direktori).$new_file_name, $bin);  
                // end 
                $status = true;
                $msg = $retuanbase64->msg;
            }else{
                $status = false;
                $msg = $retuanbase64->msg;
            }
        }else{
            $status = false;
            $msg = "Status SK sudah di tandatangan";
        }
        
        $response ['status']= $status;
        $response ['msg']= $msg;
        $this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function send_koreksi_post()
    {

        date_default_timezone_set("Asia/Bangkok");
        $this->load->model("sk_ds/sk_ds_model");
        $this->load->model("sk_ds/sk_ds_korektor_model");
        $this->load->model('ds_riwayat/ds_riwayat_model');
       
        $id_file = $this->input->post('id_file'); // id file DD
        $catatan = $this->input->post('catatan');
        $status = $this->input->post('status');
        $is_returned = $this->input->post('is_returned');

        $ses_nip = $this->input->get('nip') ? $this->input->get('nip') : "-";

        // get data detil
        $datadetil = $this->sk_ds_korektor_model->find($id_file);
        $data = array();
        $return = false;
        // jika statusnya lanjutkan, maka ubah status koreksi pengoreksi selanjutnya jadi 2 (siap koreksi)
        if($datadetil->is_corrected == "2"){
           if($status=="1"){
                $korektor_ke = $datadetil->korektor_ke;
                $file_id = $datadetil->id_file;
                $korektor_selanjutnya = $korektor_ke + 1;
                // cek apakah ada korektor selanjutnya, jika sudah korektor terakhir maka update ketabel file_ds supaya siap tanda tangan
                $this->sk_ds_korektor_model->where("korektor_ke",$korektor_selanjutnya);
                $nextkorektor = $this->sk_ds_korektor_model->find_by("id_file",$file_id);
                if(isset($nextkorektor->id)){
                    // jika ada korektor selanjutnya
                    $data_updates = array(
                        'is_corrected'     => 2
                    );
                    $this->sk_ds_korektor_model->where("id_file",$file_id);
                    $this->sk_ds_korektor_model->update_where('korektor_ke', $korektor_selanjutnya, $data_updates);
                    // add log riwayat
                    $this->save_riwayat_ds($id_file,$ses_nip,"SK diteruskan ke korektor selanjutnya ".$korektor_selanjutnya,"");
                    // end log riwayat
                }else{
                    // jika tidak ada korektor selanjutnya
                    $data_updates = array(
                        'is_corrected'     => 1
                    );
                    $this->sk_ds_model->update($file_id,$data_updates);

                    // add log riwayat
                    $this->save_riwayat_ds($id_file,$ses_nip,"SK diteruskan ke penandatangan oleh korektor","");
                    // end log riwayat


                }
            }
            // jika statusnya dikembalikan
            if($status=="3"){
                $korektor_ke = $datadetil->korektor_ke;
                $file_id = $datadetil->id_file;
                // jika tidak ada korektor selanjutnya
                $data_updates = array(
                    'is_signed'     => 3,
                    'is_corrected'     => 3,
                    'is_returned'     => 1,
                    'catatan'     => $catatan
                    
                );
                $this->sk_ds_model->update($file_id,$data_updates);
                $data["is_returned"]     = $is_returned;

                // add log riwayat
                $this->save_riwayat_ds($id_file,$ses_nip,"SK dikembalikan oleh korektor",$catatan);
                // end log riwayat

            }
            

            // update data
            $data["is_corrected"]     = $status;
            $data["catatan_koreksi"]     = $catatan;
            
            
            $msg = "";
            if($this->sk_ds_korektor_model->update($id_file,$data)){
                $return = true;
                $msg = "Koreksi Selesai";
            }else{
                $msg = "Koreksi error ".$this->sk_ds_korektor_model->error;
            } 
        }else{
            $msg = "Status SK sudah dikoreksi";
        }
        
        $response ['success']= $return;
        $response ['msg']= $msg;
        $this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }


    public function send_koreksi_penandatangan_post()
    {

        date_default_timezone_set("Asia/Bangkok");
        $this->load->model("sk_ds/sk_ds_model");
        $this->load->model("sk_ds/sk_ds_korektor_model");
        $this->load->model('ds_riwayat/ds_riwayat_model');
        $id_file = $this->input->post('id_file'); // id file DD
        $catatan = $this->input->post('catatan');
        $status = $this->input->post('status');
        $is_returned = $this->input->post('is_returned');
        $ses_nip = $this->input->get('nip') ? $this->input->get('nip') : "-";
        // get data detil
        $datadetil = $this->sk_ds_model->find($id_file);
        $data = array();
        $return = false;
        // jika statusnya lanjutkan, maka ubah status koreksi pengoreksi selanjutnya jadi 2 (siap koreksi)
        if($datadetil->is_signed != "1"){
            
            if($status=="3"){
                $data["is_returned"]     = $is_returned;
            }

            // update data
            $data["is_corrected"]     = $status;
            $data["is_signed"]        = $status;
            $data["catatan"]          = $catatan;
            
            
            $msg = "";
            if($this->sk_ds_model->update($id_file,$data)){
                $return = true;
                $msg = "Koreksi Selesai";
                // add log riwayat
                $this->save_riwayat_ds($id_file,$ses_nip,"Koreksi SK oleh penandatangan",$catatan);
                // end log riwayat
            }else{
                $msg = "Koreksi error ".$this->sk_ds_model->error;
            } 
        }else if($datadetil->is_signed == "1"){
            $msg = "Status SK sudah tandatangan, tidak bisa dikembalikan";
        }
        
        $response ['success']= $return;
        $response ['msg']= $msg;
        $this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
 
    public function find_nip_get(){
        $this->load->model("sk_ds/sk_ds_model");
        $this->load->model("pegawai/pegawai_model");
        $appKeyData = $this->getApplicationKey();
        // print_r($appKeyData);
        $satkers = array();
        if($appKeyData->satker_auth){
            $satkers = explode(',',$appKeyData->satker_auth);
        }
        
        $output = array(
            'success'=>false,
            'msg'=>'Unknown error'
        );
        $nip = $this->get('nip');
        $start = (int)$this->get('start');
        $limit = $this->get('limit');
        if ($start === NULL)
        {
           $start = 0;
        }
        else {
            if($start<0){
                $output['msg'] = 'Parameter start harus >=0 ';
                $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
            }
        }

        if ($limit === NULL)
        {
           $limit = 50;
        }
        else {
            if($limit==-1){
                // no problem
            }
            else if($limit>1000){
                $output['msg'] = 'Parameter limit maksimal 1000 ';
                $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
            }
            else if($limit<0){
                $output['msg'] = 'Parameter limit harus >=0 ';
                $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
            }

        }
        $total= $this->sk_ds_model->count_all_pegawai_ttd($nip);
        if($limit==-1){

        }
        else {
            $this->db->limit($limit,$start);
        }
        $datas = $this->sk_ds_model->find_all_pegawai_ttd($nip);
        if(empty($datas))
            $datas = null;
        $this->db->flush_cache();
         $output = array(
            'success' => true,
            'total'=>$total,
            'data'=>$datas
        );
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function sk_by_nomor_post(){
        $this->load->model('sk_ds/sk_ds_model');
        $post = $this->input->post();
        $ds = $this->sk_ds_model->get_ds_by_sk($post->nomor_sk);
        $this->response($ds, REST_Controller::HTTP_OK);
    }

    public function doc_casn_post(){
        

        $this->load->model('tte/tte_model');
        $this->load->model('tte/variable_model');
       // $this->load->model('pegawai/pegawai_model');
        $this->load->model('tte/proses_variable_model');
        $this->load->model('tte/tte_master_korektor_model');
        $this->load->model('tte/tte_trx_draft_sk_model');
        $this->load->model('tte/tte_trx_draft_sk_detil_model');
        $this->load->model('tte/Tte_trx_korektor_draft_model');
        $this->load->model('sk_ds/kategori_ds_model');
        $this->load->model('sk_ds/sk_ds_model');
        $this->load->model('sk_ds/sk_ds_korektor_model');
        

        $status = false;
        $msg = "";  
        $post = $this->input->post();
        $penandatangan_sk = $this->input->post('penandatangan_sk');
        $id_master_proses   = $this->input->post('id_master_proses');
        $nip_sk             = $this->input->post('nip_pemilik_sk');
        $nama_pemilik_sk    = $this->input->post('nama_pemilik_sk');
        $averifikator = $this->input->post('verifikator');
        $kode = $this->input->post('id');    
        $direktori = trim($this->settings_lib->item('site.pathsk'));
        $halaman_ttd = $this->input->post('halaman_ttd');
        $show_qrcode = $this->input->post('show_qrcode');
        $letak_ttd = $this->input->post('letak_ttd');
        $id_file = "";
        $nomor_sk = $this->input->post('nomor_sk') ? trim($this->input->post('nomor_sk')) : null;
        $tgl_sk = $this->input->post('tgl_sk') ? trim($this->input->post('tgl_sk')) : null;
        $tmt_sk = $this->input->post('tmt_sk') ? trim($this->input->post('tmt_sk')) : null;
        $base64data = $this->input->post('doc');
        $auth = $this->input->post('auth');
        $save_file_ds = true;
        $data_ds = array();
        $data["base64pdf_hasil"]        = $base64data;    
        $data_ds["teks_base64"]         = $base64data;    

        if($nomor_sk != "" and $kode == "")
        {
            $id_file = md5($nomor_sk.date("ymdhis"));
            $data['id_file']            = $id_file;
        }

        $data['nip_sk']             = $nip_sk;
        $data['nama_pemilik_sk']    = $nama_pemilik_sk;
        
        $data['penandatangan_sk']             = $penandatangan_sk;
        $data['id_master_proses']             = $id_master_proses ? $id_master_proses : null;
        $data['tgl_sk']             = $tgl_sk;
        $data['tmt_sk']             = $tmt_sk;
        $data['nomor_sk']           = $nomor_sk;

        $data['show_qrcode']           = $show_qrcode;
        $data['letak_ttd']           = $letak_ttd;
        $data['halaman_ttd']           = $halaman_ttd;

        if($kode != ""){
            $data['updated_by']         = $auth;
            $data['updated_date']        = date("Y-m-d");

            $id = $this->tte_trx_draft_sk_model->update($kode,$data);
        }else{
            $data['created_by']             = $auth;
            $data['created_date']            = date("Y-m-d");
            //$id = "asdfghjkl";
            $id = $this->tte_trx_draft_sk_model->insert($data);   
            $kode = $id; 
        }

        if($id){
            $status = true;
            $msg = "Berhasil";   
        }else{
            $status = false;
            $msg = "Gagal";   
        }

        if($id_master_proses != ""){
            $datadel = array('id_tte_trx_draft_sk '=>$kode);
            $this->tte_trx_draft_sk_detil_model->delete_where($datadel);

            $this->proses_variable_model->where("id_proses",$id_master_proses);
            $proses_variable = $this->proses_variable_model->find_detil();
            if (isset($proses_variable) && is_array($proses_variable) && count($proses_variable)):
                foreach($proses_variable as $recordvar):
                    $datavar = array();
                    $datavar["id_tte_trx_draft_sk"] = $kode;
                    $datavar['id_variable']         = $recordvar->id_variable;
                    $datavar['isi']         = $this->input->post($recordvar->nama_variable);
                    $id_proses = $this->tte_trx_draft_sk_detil_model->insert($datavar);
                endforeach;
            endif;
            // korektor
            
            $datadel = array('id_tte_trx_draft_sk '=>$kode);
            $this->Tte_trx_korektor_draft_model->delete_where($datadel);
            $korektor_ke = 1;
            foreach ($averifikator as $pid) {
                if($pid != ""){
                    $datavar = array();
                    $datavar["id_pegawai_korektor"]      = $pid;
                    $datavar['id_tte_trx_draft_sk']        = $kode;
                    $datavar['korektor_ke']        = $korektor_ke;
                    $id_proses = $this->Tte_trx_korektor_draft_model->insert($datavar);
                    $korektor_ke++;
                }
            }
            if($save_file_ds){
                if($kode != ""){
                    $tte_draft = $this->tte_trx_draft_sk_model->find($kode);
                    $id_file = isset($tte_draft->id_file) ? $tte_draft->id_file : "";
                }
                
                // save to sk_ds
                // create file
                $data_ds["teks_base64"] = $this->createfile_pdf($base64data,$id_file,$this->input->post('mode_usul'),$kode);
                $data_file_ds = $id_sk_ds = $this->sk_ds_model->find_by("id_file",$id_file);
                $id_sk_ds      = isset($data_file_ds->id) ? $data_file_ds->id : "";
                $is_signed = isset($data_file_ds->is_signed) ? $data_file_ds->is_signed : "";
                if($is_signed == "1"){
                    $data_json = array(
                        'status' => false,
                        'msg' => "File sudah di tandatangan, tidak bisa diubah"
                    );

                    $json_data = json_encode($data_json);
                    echo $json_data;
                    die();
                }
                $tte = $this->tte_model->find($id_master_proses);        
                $kategori = $tte->nama_proses;
                $data_ds['id_file']           = $id_file;
                $data_ds['waktu_buat']        = date("Y-m-d");
                $data_ds['id_pegawai_ttd']    = $penandatangan_sk;
                $data_ds['nip_sk']            = $nip_sk;
                $data_ds['nama_pemilik_sk']            = $nama_pemilik_sk;
                $data_ds['kategori']          = $kategori;
                $data_ds['nomor_sk']          = $nomor_sk;

                $data_ds['show_qrcode']         = $show_qrcode;
                $data_ds['letak_ttd']           = $letak_ttd;
                $data_ds['halaman_ttd']         = $halaman_ttd;
                $data_ds['lokasi_file']  = base_url()."dokumen/validasi/".$id_file;

                if(count($averifikator) > 0){
                    $data_ds['is_signed']            = 0;
                    $data_ds['is_corrected']         = 0;
                }else{
                    $data_ds['is_corrected']         = 1;// dibuat langsung bisa tandatangan jika ga ada korektor        
                    $data_ds['is_signed']            = 0;
                }
                
                $data_ds['ds_ok']               = 1; // merupakan ttd digital
                if($id_sk_ds != ""){
                    $return_ds = $this->sk_ds_model->update($id_file,$data_ds);
                    //log_activity($auth,"Update File ds dari web " . ': ' . $id_sk_ds . ' : ' . $this->input->ip_address(), 'sk_ds');
                }else{
                    $return_ds = $this->sk_ds_model->insert($data_ds);    
                    //log_activity($auth,"Save File ds dari web " . ': ' . $return_ds . ' : ' . $this->input->ip_address(), 'sk_ds');
                }
                if($return_ds){
                    // save korektor
                    $averifikator = $this->input->post('verifikator');
                    $datadel_korektor = array('id_file '=>$id_file);
                    $this->sk_ds_korektor_model->delete_where($datadel_korektor);
                    $korektor_ke = 1;
                    if(count($averifikator) > 0){
                        foreach ($averifikator as $pid) {
                            if($pid != ""){
                                $data_korektor = array();
                                $data_korektor['id_file']        = $id_file;
                                $data_korektor["id_pegawai_korektor"]      = $pid;
                                $data_korektor['korektor_ke']        = $korektor_ke;
                                $data_korektor['is_corrected']       = $korektor_ke == 1 ? 2 : null;
                                $id_ds_korektor = $this->sk_ds_korektor_model->insert($data_korektor);
                                if($id_ds_korektor){
                                    //log_activity($auth,"Save Korektor ds dari web " . ': ' . $id_ds_korektor . ' : ' . $this->input->ip_address(), 'sk_ds');
                                }
                                $korektor_ke++;
                            }
                        }
                    } 
                    
                    $status = true;
                    $msg = "Upload berhasil";   
                }else{
                    $status = false;
                    $msg = "Upload gagal";   
                }
            }
        }


        //$base64hasil = $this->createfile_pdf($this->input->post('doc'),"asdfghjkl",1,"kodekode");
        $this->response(array("return"=>$data_ds["teks_base64"]), REST_Controller::HTTP_OK);
    }

    private function gen_qrcode($ref){
        $this->load->library('phpqrcode_lib');
        //$ref = uniqid("sk_kgb");
        $barcode_file = $ref.".png";
        $barcode_file_path = APPPATH."..".DIRECTORY_SEPARATOR."assets".DIRECTORY_SEPARATOR."qrcodes".DIRECTORY_SEPARATOR."".$barcode_file; 
        $qr_data = base_url()."dokumen/validasi/".$ref;
        QRcode::png($qr_data, $barcode_file_path, 'L', 8, 1);

        // === Adding image to qrcode
        $logo = APPPATH."..".DIRECTORY_SEPARATOR."assets".DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR."tutwuri.png";
        $QR = imagecreatefrompng($barcode_file_path);
        if($logo !== FALSE){
            $logopng = imagecreatefrompng($logo);
            $QR_width = imagesx($QR);
            $QR_height = imagesy($QR);
            $logo_width = imagesx($logopng);
            $logo_height = imagesy($logopng);
            
            list($newwidth, $newheight) = getimagesize($logo);
            $out = imagecreatetruecolor($QR_width, $QR_width);
            imagecopyresampled($out, $QR, 0, 0, 0, 0, $QR_width, $QR_height, $QR_width, $QR_height);
            imagecopyresampled($out, $logopng, $QR_width/2.65, $QR_height/2.65, 0, 0, $QR_width/4, $QR_height/4, $newwidth, $newheight);
            
        }
        imagepng($out,$barcode_file_path);
        imagedestroy($out);

        return $barcode_file_path;
    }


    private function createfile_pdf($b64 = "",$file_id = "",$usul = 0,$kode){
        //echo "coeg";
        $direktori = trim($this->settings_lib->item('site.pathsk'));
        //$direktori = "/Users/echa/";
        $file_name = $file_id.".pdf";
        if (file_exists($direktori.$file_name)) {
           unlink($direktori.$file_name);
        }
        
        $bin = base64_decode($b64, true);
        file_put_contents($direktori.$file_name, $bin);
        
        if($usul==2){
            $this->surat_usul_docs($direktori.$file_name,$file_id,$kode);
        }else if($usul==1){
            $this->nota_usul_docs($direktori.$file_name,$file_id,$kode);
        }else if($usul==3){
            $this->sk_cpns_docs($direktori.$file_name,$file_id,$kode);
        }else if($usul==4){
            $this->sertifikat($direktori.$file_name,$file_id,$kode);
        }

        $imagedata = file_get_contents($direktori.$file_name);
        $base64 = base64_encode($imagedata);
        $data["base64pdf_hasil"] = $base64;        
        $this->tte_trx_draft_sk_model->update($kode,$data);

        return $base64;        
    }

    private function surat_usul_docs($file_name,$id_file,$kode){
        //echo "coeg";
        $this->load->library('fpdf/FPDF');
        $this->load->library('fpdi/FPDI');
        $pdf = new FPDI(); // Array sets the X, Y dimensions in mm
        $pagecount = $pdf->setSourceFile($file_name);
        for($i=1;$i<=$pagecount;$i++){
            $tppl = $pdf->importPage($i);
            //$pdf->AddPage();
            //echo BASEPATH."../../assets/templates/kop.png";
            $specs = $pdf->getTemplateSize($tppl);
            $pdf->addPage($specs['h'] > $specs['w'] ? 'P' : 'L');
            $pdf->useTemplate($tppl, 0, 0, 0, 0);

            if($i==1){
                //echo $specs['h']." | ".$specs['w'];
                $pdf->Image(BASEPATH."../../assets/templates/kopristek.png",0,0,210,43);  
                $pdf->Image(BASEPATH."../../assets/templates/BSRE.png",0,$specs['h']-14,210,13); 
                $pdf->Image($this->gen_qrcode($id_file),140,$specs['h']-96,14,14);    
            }else if($i!=1){
                $pdf->Image($this->gen_qrcode($id_file),3,$specs['h']-19,16,16); // X start, Y start, X width, Y width in mm
                $pdf->Image(BASEPATH."../../assets/templates/deskripsi.jpg",19,$specs['h']-19,20,16);
            }
        }
        

        $pdf->Output($direktori.$file_name, "F");

    }

    private function nota_usul_docs($file_name,$id_file,$kode){
        //echo "coeg";
        $this->load->library('fpdf/FPDF');
        $this->load->library('fpdi/FPDI');
        $pdf = new FPDI(); // Array sets the X, Y dimensions in mm
        $pagecount = $pdf->setSourceFile($file_name);
        for($i=1;$i<=$pagecount;$i++){
            $tppl = $pdf->importPage($i);
            
            $specs = $pdf->getTemplateSize($tppl);
            $pdf->addPage($specs['h'] > $specs['w'] ? 'P' : 'L');
            $pdf->useTemplate($tppl, 0, 0, 0, 0);

            if($i==1){
                
                $pdf->Image($this->gen_qrcode($id_file),3,$specs['h']-40,16,16); // X start, Y start, X width, Y width in mm
                $pdf->Image(BASEPATH."../../assets/templates/deskripsi.jpg",19,$specs['h']-40,20,16);
                $pdf->Image(BASEPATH."../../assets/templates/BSRE.png",0,$specs['h']-20,210,13); 
                //$pdf->Image($this->gen_qrcode($id_file),143,$specs['h']-46,12,12);  
                  
            }
        }
        

        $pdf->Output($direktori.$file_name, "F");

    }

    private function sertifikat($file_name,$id_file,$kode){
        //echo "coeg";
        $this->load->library('fpdf/FPDF');
        $this->load->library('fpdi/FPDI');
        $pdf = new FPDI(); // Array sets the X, Y dimensions in mm
        $pagecount = $pdf->setSourceFile($file_name);
        for($i=1;$i<=$pagecount;$i++){
            $tppl = $pdf->importPage($i);
            
            $specs = $pdf->getTemplateSize($tppl);
            $pdf->addPage($specs['h'] > $specs['w'] ? 'P' : 'L');
            $pdf->useTemplate($tppl);

            if($i==1){
                
                $pdf->Image($this->gen_qrcode($id_file),$specs['w']-75,$specs['h']-46,21,21); // X start, Y start, X width, Y width in mm
                
                //$pdf->Image($this->gen_qrcode($id_file),143,$specs['h']-46,12,12);  
                  
            }
        }
        

        $pdf->Output($direktori.$file_name, "F");

    }

    private function sk_cpns_docs($file_name,$id_file,$kode){
        //echo "coeg";
        $this->load->library('fpdf/FPDF');
        $this->load->library('fpdi/FPDI');
        $pdf = new FPDI(); // Array sets the X, Y dimensions in mm
        $pagecount = $pdf->setSourceFile($file_name);
        for($i=1;$i<=$pagecount;$i++){
            $tppl = $pdf->importPage($i);
            
            $specs = $pdf->getTemplateSize($tppl);
            $pdf->addPage($specs['h'] > $specs['w'] ? 'P' : 'L',array(210,330));
            $pdf->useTemplate($tppl);

            if($i==1){
                
                $pdf->Image($this->gen_qrcode($id_file),$specs['w']-80,$specs['h']-47,14,14); // X start, Y start, X width, Y width in mm
                $pdf->Image(BASEPATH."../../assets/templates/BSRE.png",0,$specs['h']-15,210,13); 
                //$pdf->Image($this->gen_qrcode($id_file),143,$specs['h']-46,12,12);  
                  
            }
        }
        

        $pdf->Output($direktori.$file_name, "F");

    }
}
