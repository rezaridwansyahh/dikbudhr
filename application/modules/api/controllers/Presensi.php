<?php
require APPPATH . '/libraries/LIPIAPI_REST_Controller.php';
class Presensi extends  LIPIAPI_REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }
    protected $methods = [
        'index_get' => ['level' => 10, 'limit' => 10],
        'xlist_get' => ['level' => 0, 'limit' => 10],
        'cek_get' => ['level' => 10, 'limit' => 10],
        'add_kehadiran_post' => ['level' => 0, 'limit' => 10]
    ];
    public function store_kehadiran_post()
    {
        $dbhadir = $this->load->database('kehadiran', true);
        $isWFO = trim($this->input->post('iswfo'));
        $sn = trim($this->input->post('sn'));
        $data = [
            'userid' => trim($this->input->post('nip')),
            'checktime' => $this->input->post('checktime'),
            'checktype' => trim($this->input->post('checktype')),
            'verifycode' => trim($this->input->post('verifycode')),
            'sn' => $sn,
            'editdate' => NULL,
            'editby' => NULL
        ];

        if ( !$isWFO ) {
            $data['checktype'] = 0;
            $data['verifycode'] = 0;
            $data['editdate'] = date('Y-m-d H:i:s');
            $data['editby'] = 'service-service';
            $data['sn'] = '-'; 
        }

        if ( empty(trim($this->input->post('nip'))) || empty($this->input->post('checktime')) ) {
            $response = array(
                'success' => false,
                'message' => 'Parameter NIP dan Waktu Absen Wajib diisi!'
            );
            $this->response($response, 500); // OK (200) being the HTTP response code
        }
        $where = ['userid'=>trim($this->input->post('nip')), 'checktime'=>$this->input->post('checktime')];
        $dbhadir->where($where);
        $query = $dbhadir->get('checkinout');
        if ( $query->num_rows() == 0 ) {
            $inserted_id = $dbhadir->insert('checkinout', $data);
            $response = array(
                'success' => true,
                'id' => $inserted_id
            );
            $this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        } else {
            $response = array(
                'success' => false,
                'message' => 'Absen pegawai '.trim($this->input->post('nip')).' sudah ada. Periksa kembali isian anda!!'
            );
            $this->response($response, 500); // OK (200) being the HTTP response code
        }
        
    }
}
