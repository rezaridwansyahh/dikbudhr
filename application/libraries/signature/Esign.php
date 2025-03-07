<?php

include 'Client/BasicRest.php';

class Esign
{

    public function checkStatus($nik = '')
    {
        $rest = new BasicRest();
        $response = $rest->send('/api/user/status/' . $nik, 'GET');
        if(!$response) return $rest->getError();
        else return $response;
    }

    public function registrasi($nik = '')
    {
        $rest = new BasicRest();

        $data = array(
            'nik' => $nik,
            'nama' => 'agha-183602052019',
            'email' => 'agha.nugraha@bssn.go.id',
            'nomor_telepon' => '08971505626',
            'kota' => 'Jakarta',
            'provinsi' => 'DKI Jakarta',
            'nip' => '1234567890',
            'jabatan' => 'staff ahli',
            'unit_kerja' => 'Puskaji TI'
        );

        $files = array(
            'image_ttd' => $pdf,
            'ktp' => $pdf,
            'surat_rekomendasi' => $pdf
        );

        $response = $rest->send('/api/user/registrasi', 'POST', $data, $files);
        if(!$response) return $rest->getError();
        else return $response;
    }

    public function sign($nik = '', $pass = '', $pdf = '',$spesimen ="",$halaman_ttd = 1,$show_qrcode = 0,$letak_sk = 0)
    {
        $rest = new BasicRest();
        $ext = pathinfo($pdf, PATHINFO_EXTENSION);
        $filename = basename($pdf, '.' .$ext);
        $directoryName = realpath(dirname($pdf));
        $halaman = "PERTAMA";
        $linkQR = "";
        $xAxis = '600';
        $yAxis = '70';
        $width = '550';
        $height = '40';
        $text_label = "";
        if($halaman_ttd == 2){
            $halaman = "TERAKHIR";
        }
        $show_image = true;
        if($show_qrcode == 1){
            $show_image = false;
            $linkQR = base_url()."dokumen/validasi/".$filename;
            $text_label = "Dokumen ini telah ditandatangani secara elektronik menggunakan sertifikat elektronik yang diterbitkan oleh BSRe";
            
            if($letak_sk == 0){ //kanan bawah
                $xAxis = '300';
                $yAxis = '10';
                $width = '550';
                $height = '100';
            }
        }
        if($letak_sk == 1){ //tengah bawah
            $xAxis = '150';
            $yAxis = '10';
            $width = '650';
            $height = '100';
        }
        if($letak_sk == 2){ //kiri bawah
            $xAxis = '5';
            $yAxis = '5';
            $width = '140';
            $height = '70';
        }

        $data = array(
            'nik' => $nik,
            'passphrase' => $pass,
            'tampilan' => 'visible',
            'image' => $show_image,
            'halaman' => $halaman,
            'xAxis' => $xAxis,
            'yAxis' => $yAxis,
            'width' => $width,
            'height' => $height,
            'linkQR' => $linkQR,
            'text' => $text_label
        );

        $files = array(
            'file' => $pdf,
            'imageTTD' => $spesimen
        );

        $response = $rest->send('/api/sign/pdf', 'POST', $data, $files);
        if(!$response) {

            return $rest->getError();
        }
        else {
            $header = $rest->getHeader();
            
            $file = $rest->send('/api/sign/download/' . $header['id_dokumen'][0], 'GET');
            $fp = fopen($directoryName . '/' . $filename . '_signed.pdf', 'wb'); 
            fwrite($fp, $file);
            fclose($fp);
            return 1;
        }
    }
   
}