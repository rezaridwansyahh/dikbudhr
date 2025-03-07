<?php 
require APPPATH . '/libraries/LIPIAPI_REST_Controller.php';
class Unitkerja extends  LIPIAPI_REST_Controller {
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
            'add_riwayat_jabatan_post' => ['level' => 0, 'limit' => 10],
            'add_riwayat_pindah_unitkerja_post' => ['level' => 0, 'limit' => 10],
            'add_riwayat_kepangkatan_post' => ['level' => 0, 'limit' => 10],
            'add_riwayat_skp_post' => ['level' => 0, 'limit' => 10],
    ];
	 
 
    public function list_pejabat_get(){
        $this->load->model("pegawai/unitkerja_model");
        $appKeyData = $this->getApplicationKey();
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
        $limit = $this->get('limit');
        $nama_with_nip = $this->get('param1');
        /*
        if ($unor_id === NULL)
        {
            $output['msg'] = 'Parameter unor_id di butuhkan bro';
            $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }
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

        if($limit==-1){

        }
        else {
            $this->db->limit($limit,$start);
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
				$output['msg'] = 'Parameter limit harus >=0 ';
                $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
				return;
			}	
			else {
				$filter_satkers[] = $unor_id;
			}
		}
        */
        $total= $this->unitkerja_model->count_pejabat($unor_id);
        //$this->unitkerja_model->limit(100);
        $pegawais = $this->unitkerja_model->get_pejabat($unor_id,$start,$limit);
        $this->db->flush_cache();
         $output = array(
            'success' => true,
            'total'=>$total,
            'data'=>$pegawais
        );
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function list_eselon_1_get(){
        $this->load->model("pegawai/unitkerja_model");
       
        
        $output = array(
            'success'=>false,
            'msg'=>'Unknown error'
        );
        $unor_id = $this->get('unor_id');
        $start = (int)$this->get('start');
        $limit = $this->get('limit');
         
        $total= $this->unitkerja_model->count_unitkerja();
        $records = $this->unitkerja_model->find_unitkerja();
        $this->db->flush_cache();
         $output = array(
            'success' => true,
            'total'=>$total,
            'data'=>$records
        );
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function list_unitkerja_get(){
        $this->load->model("pegawai/unitkerja_model");
        $output = array(
            'success'=>false,
            'msg'=>'Unknown error'
        );
        $diatasan_id = $this->get('diatasan_id');
        $start = (int)$this->get('start');
        $limit = $this->get('limit');
         
        $total= $this->unitkerja_model->count_unitkerja($diatasan_id);
        if ($start === NULL)
        {
           $start = 0;
        }
        if($limit==-1){

        }
        else {
            $this->db->limit($limit,$start);
        }
        $records = $this->unitkerja_model->find_unitkerja($diatasan_id);
        $this->db->flush_cache();
         $output = array(
            'success' => true,
            'total'=>$total,
            'data'=>$records
        );
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }


}
