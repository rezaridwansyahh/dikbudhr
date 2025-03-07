<?php 
require APPPATH . '/libraries/LIPIAPI_REST_Controller.php';
class Kpo extends  LIPIAPI_REST_Controller {
	 public function latest_get(){
		$this->load->model('kpo/layanan_kpo_model');
        $this->db->where('perkiraan_kpo.status','10');
        $this->db->select('p.NIP_BARU');
        $records = $this->layanan_kpo_model->find_all();
        $output = array(
			'success'=>true,
            'data'=>$records
        );
         $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function list_usulan_get(){
        $no_surat_pengantar = $this->get('no_surat_pengantar');
       
		if ($no_surat_pengantar === NULL)
        {
            $output['msg'] = 'Parameter no_surat_pengantar di butuhkan';
            $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }
        $this->load->model('kpo/layanan_kpo_model');
		$this->db->order_by('perkiraan_kpo.nama',"ASC");
        $this->db->where('perkiraan_kpo.no_surat_pengantar_es1',$no_surat_pengantar);
		$this->db->where('perkiraan_kpo.status >= 6',null,false);
        $this->layanan_kpo_model->select('p.NIP_BARU');
        $records = $this->layanan_kpo_model->find_services();
        $output = array(
			'success'=>true,
			'total'=>sizeof($records),
            'data'=>$records ? $records : array()
        );
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function list_usulan_satker_get(){
        $no_surat_pengantar = $this->get('no_surat_pengantar');
       
        if ($no_surat_pengantar === NULL)
        {
            $output['msg'] = 'Parameter no_surat_pengantar di butuhkan';
            $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }
        $this->load->model('kpo/layanan_kpo_model');
        $this->db->order_by('perkiraan_kpo.nama',"ASC");
        $this->db->where('perkiraan_kpo.no_surat_pengantar',$no_surat_pengantar);
        $this->db->where('perkiraan_kpo.status >= 6',null,false);
        $this->layanan_kpo_model->select('p.NIP_BARU');
        $records = $this->layanan_kpo_model->find_services();
        $output = array(
            'success'=>true,
            'total'=>sizeof($records),
            'data'=>$records ? $records : array()
        );
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
}