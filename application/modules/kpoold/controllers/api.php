<?php 
class Api extends Front_Controller {
    public function latest($surat=""){
        $this->load->model('kpo/layanan_kpo_model');
        $this->db->where('perkiraan_kpo.status','10');
        $this->db->select('p.NIP_BARU');
        $records = $this->layanan_kpo_model->find_all();
        $output = array(
            'data'=>$records
        );
        header("Content-type:application/json");
        echo json_encode($output);
    }
    public function list_usulan(){
        $no_surat_pengantar = $this->input->get('no_surat_pengantar');
        if($no_surat_pengantar ==''){
            die("no_surat_pengantar is required");
        }
        $this->load->model('kpo/layanan_kpo_model');
        $this->db->where('perkiraan_kpo.no_surat_pengantar',$no_surat_pengantar);
        $this->layanan_kpo_model->select('p.NIP_BARU');
        $records = $this->layanan_kpo_model->find_all();
        $output = array(
            'data'=>$records
        );
        header("Content-type:application/json");
        echo json_encode($output);
    }
}