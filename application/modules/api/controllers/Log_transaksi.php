<?php
require APPPATH . '/libraries/LIPIAPI_REST_Controller.php';
class Log_transaksi extends  LIPIAPI_REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('pegawai/log_transaksi_model');
    }
    protected $methods = [
        'index_get' => ['level' => 10, 'limit' => 10],
        'xlist_get' => ['level' => 0, 'limit' => 10],
        'sub_get' => ['level' => 10, 'limit' => 10],
    ];
    

    public function list_pegawai_get()
    {
        $output = array(
            'success' => false,
            'msg' => 'Unknown error'
        );
        $tanggal_mulai = $this->get('tanggal_mulai');
        $tanggal_selesai = $this->get('tanggal_selesai');
        $start = (int)$this->get('start');
        $limit = $this->get('limit');
        if ($tanggal_mulai === NULL) {
            $tanggal_mulai = date("Y-m-d");
        }
        if ($tanggal_selesai === NULL) {
            $tanggal_selesai = date("Y-m-d");
        }
        if ($start === NULL) {
            $start = 0;
        } else {
            if ($start < 0) {
                $output['msg'] = 'Parameter start harus >=0 ';
                $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
            }
        }

        if ($limit === NULL) {
            $limit = 1000;
        } else {
            if ($limit == -1) {
                // no problem
            } else if ($limit > 1000) {
                $output['msg'] = 'Parameter limit maksimal 1000 ';
                $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
            } else if ($limit < 0) {
                $output['msg'] = 'Parameter limit harus >=0 ';
                $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
            }
        }
        $total = $this->log_transaksi_model->count_dari_sampai($tanggal_mulai,$tanggal_selesai);
        if ($limit == -1) {
        } else {
            $this->db->limit($limit, $start);
        }
        $pegawais = $this->log_transaksi_model->find_dari_sampai($tanggal_mulai,$tanggal_selesai);
        $this->db->flush_cache();
        $output = array(
            'success' => true,
            'total' => $total,
            'data' => $pegawais
        );
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
}
