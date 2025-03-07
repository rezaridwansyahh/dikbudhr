<?php 
class Services extends Front_Controller {
    public function detail($ref){
        echo $ref;
    }
    public function qrcode($ref=''){
        $this->load->library('phpqrcode_lib');
        $ref = uniqid("sk_kgb");
        $barcode_file = $ref.".png";
        $barcode_file_path = APPPATH."../assets/qrcodes/".$barcode_file; 
        $qr_data = base_url("kgb/services/detail/".$ref);
        QRcode::png($qr_data, $barcode_file_path, 'L', 4, 2);
        return $barcode_file_path;
    }
}