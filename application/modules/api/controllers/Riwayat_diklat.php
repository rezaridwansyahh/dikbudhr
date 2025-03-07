<?php 
require APPPATH . '/libraries/LIPIAPI_REST_Controller.php';
class Riwayat_diklat extends  LIPIAPI_REST_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->library('form_validation');
       
    }
     protected $methods = [
			'index_get' => ['level' => 10, 'limit' => 10],
            'xlist_get' => ['level' => 0, 'limit' => 10],
			'sub_get' => ['level' => 10, 'limit' => 10],
            'add_riwayat_pendidikan_post' => ['level' => 0, 'limit' => 10],
            'add_riwayat_jabatan_post' => ['level' => 0, 'limit' => 10],
            'add_riwayat_pindah_unitkerja_post' => ['level' => 0, 'limit' => 10],
            'add_riwayat_kepangkatan_post' => ['level' => 0, 'limit' => 10],
            'add_riwayat_skp_post' => ['level' => 0, 'limit' => 10],
    ];
    public function add_riwayat_dikfung_post(){
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('pegawai/diklat_fungsional_model');
        if(sizeof($_POST)==0){
            $this->form_validation->set_data(array('____check_____'=>1));
        }
        $this->form_validation->set_rules($this->diklat_fungsional_model->get_validation_rules());
        
        $response = array(
            'success'=>false,
            'msg'=>'Unknown error'
        );
        if ($this->form_validation->run() === false) {
            $response['msg'] = $this->form_validation->error_array();//validation_errors();
            $this->response($response, REST_Controller::HTTP_BAD_REQUEST); // OK (200) being the HTTP response code
            die();
        }
       
        $data = $this->diklat_fungsional_model->prep_data($this->input->post());
        
         // Make sure we only pass in the fields we want
        $base64     = "";
        $file_ext   = "";
        $file_size  = "";
        $file_tmp   = "";
        $type       = "";
        if (isset($_FILES['file_dokumen']) && $_FILES['file_dokumen']['name']) 
        {
            $errors=array();
            $allowed_ext = array('pdf');
            $file_name =$_FILES['file_dokumen']['name'];
            // $file_name =$_FILES['image']['tmp_name'];
            $file_ext   = explode('.',$file_name);
            $jmltitik   = count($file_ext);
            $file_size  = $_FILES['file_dokumen']['size'];
            $file_tmp   = $_FILES['file_dokumen']['tmp_name'];
            $type       = $_FILES['file_dokumen']['type'];
            //echo $file_ext[1];echo "<br>";
            $data_base64 = file_get_contents($file_tmp);
            $base64 = 'data:' . $type . ';base64,' . base64_encode($data_base64);

            if(in_array(end($file_ext),$allowed_ext) === false)
            {
                $errors[]='Extension not allowed';
                $response['msg'] = "
                <div class='alert alert-block alert-error fade in note note-danger'>
                    <a class='close' data-dismiss='alert'>&times;</a>
                    <h4 class='alert-heading'>
                        Ada kesalahan
                    </h4>
                    <p>Extension not allowed</p>
                </div>
                ";
                echo json_encode($response);
                exit();
            }
            if($file_size > 50097152)
            {
                $errors[]= 'File size must be under 50mb';
                $response['msg'] = "
                <div class='alert alert-block alert-error fade in note note-danger'>
                    <a class='close' data-dismiss='alert'>&times;</a>
                    <h4 class='alert-heading'>
                        Ada kesalahan
                    </h4>
                    <p>File size must be under 50Mb</p>
                </div>
                ";
                echo json_encode($response);
                exit();
            }
        }
        if($base64 == "")
            unset($data['FILE_BASE64']);
        else{
            $data['FILE_BASE64']        = $base64;
            $data['KETERANGAN_BERKAS']  = isset($file_ext[$jmltitik-1]) ? $file_ext[$jmltitik-1]."  ".$type. " ".$file_size." KB" : null;
        }
        // end upload berkas

        $this->pegawai_model->where("NIP_BARU",$this->input->post("NIP"));
        $pegawai_data = $this->pegawai_model->find_first_row();  
        $data["NIP_BARU"] = $pegawai_data->NIP_BARU;
        $data["NIP_LAMA"] = $pegawai_data->NIP_LAMA;
      
          if(empty($data["TANGGAL_KURSUS"])){
            unset($data["TANGGAL_KURSUS"]);
        }
        $id_data = $this->input->post("DIKLAT_FUNGSIONAL_ID");
        $inserted_id = $this->diklat_fungsional_model->insert($data);

        $response = array(
            'success'=>true,
            'id'=>$inserted_id
        );
        $this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function add_riwayat_diklat_struktural_post(){
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('pegawai/jenis_diklat_struktural_model');
        if(sizeof($_POST)==0){
            $this->form_validation->set_data(array('____check_____'=>1));
        }
        $this->form_validation->set_rules($this->jenis_diklat_struktural_model->get_validation_rules());
        
        $response = array(
            'success'=>false,
            'msg'=>'Unknown error'
        );
        if ($this->form_validation->run() === false) {
            $response['msg'] = $this->form_validation->error_array();//validation_errors();
            $this->response($response, REST_Controller::HTTP_BAD_REQUEST); // OK (200) being the HTTP response code
            die();
        }
       
        $data = $this->diklat_struktural_model->prep_data($this->input->post());
        
        // Make sure we only pass in the fields we want
        $base64     = "";
        $file_ext   = "";
        $file_size  = "";
        $file_tmp   = "";
        $type       = "";
        if (isset($_FILES['file_dokumen']) && $_FILES['file_dokumen']['name']) 
        {
            $errors=array();
            $allowed_ext = array('pdf');
            $file_name =$_FILES['file_dokumen']['name'];
            // $file_name =$_FILES['image']['tmp_name'];
            $file_ext   = explode('.',$file_name);
            $jmltitik   = count($file_ext);
            $file_size  = $_FILES['file_dokumen']['size'];
            $file_tmp   = $_FILES['file_dokumen']['tmp_name'];
            $type       = $_FILES['file_dokumen']['type'];
            //echo $file_ext[1];echo "<br>";
            $data_base64 = file_get_contents($file_tmp);
            $base64 = 'data:' . $type . ';base64,' . base64_encode($data_base64);

            if(in_array(end($file_ext),$allowed_ext) === false)
            {
                $errors[]='Extension not allowed';
                $response['msg'] = "
                <div class='alert alert-block alert-error fade in note note-danger'>
                    <a class='close' data-dismiss='alert'>&times;</a>
                    <h4 class='alert-heading'>
                        Ada kesalahan
                    </h4>
                    <p>Extension not allowed</p>
                </div>
                ";
                echo json_encode($response);
                exit();
            }
            if($file_size > 50097152)
            {
                $errors[]= 'File size must be under 50mb';
                $response['msg'] = "
                <div class='alert alert-block alert-error fade in note note-danger'>
                    <a class='close' data-dismiss='alert'>&times;</a>
                    <h4 class='alert-heading'>
                        Ada kesalahan
                    </h4>
                    <p>File size must be under 50Mb</p>
                </div>
                ";
                echo json_encode($response);
                exit();
            }
        }
        if($base64 == "")
            unset($data['FILE_BASE64']);
        else{
            $data['FILE_BASE64']        = $base64;
            $data['KETERANGAN_BERKAS']  = isset($file_ext[$jmltitik-1]) ? $file_ext[$jmltitik-1]."  ".$type. " ".$file_size." KB" : null;
        }
        // end upload berkas
        $this->pegawai_model->where("NIP_BARU",$this->input->post("NIP"));
        $pegawai_data = $this->pegawai_model->find_first_row();  
        $data["PNS_ID"] = $pegawai_data->PNS_ID;
        $data["NIP_BARU"] = $pegawai_data->NIP_BARU;
        $data["NIP_LAMA"] = $pegawai_data->NIP_LAMA;

        $jenis_diklat = $this->jenis_diklat_struktural_model->find($this->input->post("ID_DIKLAT"));
        $data["NAMA_DIKLAT"] = $jenis_diklat->NAMA;
        if(empty($data["TANGGAL"])){
            unset($data["TANGGAL"]);
        }
        $inserted_id = $this->diklat_struktural_model->insert($data);

        $response = array(
            'success'=>true,
            'id'=>$inserted_id
        );
        $this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function add_riwayat_skp_post(){
        $this->load->model('pegawai/riwayat_prestasi_kerja_model');
        if(sizeof($_POST)==0){
            $this->form_validation->set_data(array('____check_____'=>1));
        }
        $this->form_validation->set_rules($this->riwayat_prestasi_kerja_model->get_validation_rules());
        
        $response = array(
            'success'=>false,
            'msg'=>'Unknown error'
        );
        if ($this->form_validation->run() === false) {
            $response['msg'] = $this->form_validation->error_array();//validation_errors();
            $this->response($response, REST_Controller::HTTP_BAD_REQUEST); // OK (200) being the HTTP response code
            die();
        }
       
        $data = $this->riwayat_prestasi_kerja_model->prep_data($this->input->post());

        $nilai_perilaku = $data['PERILAKU_DISIPLIN']+$data['PERILAKU_INTEGRITAS']+$data['PERILAKU_KERJASAMA']+$data['PERILAKU_KOMITMEN']+$data['PERILAKU_ORIENTASI_PELAYANAN'];
        if(isset($data['JABATAN_TIPE'])){
            if($data['JABATAN_TIPE'] ==1){
                $nilai_perilaku+= $data['PERILAKU_KEPEMIMPINAN'];
                $data['NILAI_PERILAKU'] = $nilai_perilaku/6;
            }
            else $data['NILAI_PERILAKU'] = $nilai_perilaku/5;
        }
        else $data['NILAI_PERILAKU'] = $nilai_perilaku/5;
        $data['NILAI_PROSENTASE_PERILAKU'] = 40;
        $data['NILAI_PERILAKU_AKHIR'] = $data['NILAI_PERILAKU']*$data['NILAI_PROSENTASE_PERILAKU']/100;

        $data['NILAI_PROSENTASE_SKP'] = 60;
        $data['NILAI_SKP_AKHIR'] = $data['NILAI_SKP']*$data['NILAI_PROSENTASE_SKP']/100;

        $data['NILAI_PPK'] = $data['NILAI_SKP_AKHIR']+$data['NILAI_PERILAKU_AKHIR'];


        $inserted_id = $this->riwayat_prestasi_kerja_model->insert($data);
        $response = array(
            'success'=>true,
            'id'=>$inserted_id
        );
        $this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
   
	public function list_get(){
        $pegawai_nip = $this->get('pegawai_nip');
        if ($pegawai_nip === NULL)
        {
            $output['msg'] = 'Parameter pegawai_nip di butuhkan';
            $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }
        $data_f = $this->db->select("rds.*")->
				from("hris.rwt_diklat_fungsional rds")->
				where('rds.NIP_BARU',$pegawai_nip)->get()->result();
		$data_s = $this->db->select("rds.*")->
				from("hris.rwt_diklat_struktural rds")->
				join("hris.pegawai p","p.PNS_ID = rds.PNS_ID","LEFT")->
				where('p.NIP_BARU',$pegawai_nip)->get()->result();			
         $output = array(
            'success' => true,
			'rw_fungsional'=>$data_f,
            'rw_struktural'=>$data_s
        );
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function detail_get(){
        $pegawai_nip = $this->input->post('nip');
        $output = array(
            'success'=>false,
            'msg'=>'Unknown error'
        );
        $pegawai_nip = $this->get('pegawai_nip');
        if ($pegawai_nip === NULL)
        {
            $output['msg'] = 'Parameter pegawai_nip di butuhkan';
            $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }
        $data_pegawai = $this->db->from("hris.pegawai")->where('NIP_BARU',$pegawai_nip)->get()->first_row();
        $output = array(
            'success' => true,
            'data'=>$data_pegawai
        );
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

//----------------------------------------------------RIFKYZ-----------------------------
    public function unor_get(){
        $unor_id = 'A8ACA7397AEB3912E040640A040269BB';//$this->input->post('unor_id'); 1940CF2301E5B17CE050640A150273EF

        $output = array(
            'success'=>false,
            'msg'=>'Unknown error'
        );
        //$unor_id = $this->input->post('unor_id');
        if ($unor_id === NULL)
        {
            $output['msg'] = 'Parameter unor_id di butuhkan';
            $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }
        $data_unor = $this->db->from("hris.unitkerja")->get()->result_array();//->where('ID',$unor_id)->get()->first_row();
        $output = array(
            'success' => true,
            'data'=>$data_unor
        );
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
}