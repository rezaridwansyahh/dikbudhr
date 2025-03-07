<?php 
require APPPATH . '/libraries/LIPIAPI_REST_Controller.php';
class Peta_jabatan extends  LIPIAPI_REST_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->library('form_validation');
	$this->load->model('baperjakat/baperjakat_model');
    $this->load->model('petajabatan/kandidat_baperjakat_model');
       
    }
     protected $methods = [
			'index_get' => ['level' => 10, 'limit' => 10],
            'xlist_get' => ['level' => 0, 'limit' => 10],
			'sub_get' => ['level' => 10, 'limit' => 10],
            'add_riwayat_pendidikan_post' => ['level' => 0, 'limit' => 10],
    ];
	 
    public function list_pelantikan_get(){
        $bulan = $this->get('bulan');
        $tahun = $this->get('tahun');
        if ($tahun === NULL)
        {
            $output['msg'] = 'Parameter tahun di butuhkan';
            $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }
        if ($bulan === NULL)
        {
            $output['msg'] = 'Parameter bulan di butuhkan';
            $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }
        $records = $this->kandidat_baperjakat_model->find_dilantik($tahun,$bulan);
        $output = array(
            'success' => true,
            'data'=>$records
        );
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
}
