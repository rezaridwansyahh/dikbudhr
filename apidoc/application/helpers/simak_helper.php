<?php
     
    defined('BASEPATH') OR exit('No direct script access allowed');
	ob_start();
    function hitung_ips($nim){
        $this->load->model('konversi_model', null, true);
        
		$mhs = $nim; 
		//die($mhs);
		$recordkonversi = $this->konversi_model->find_all();
		$jsonkonversi[] =array();
		if (isset($recordkonversi) && is_array($recordkonversi) && count($recordkonversi)) :
		foreach ($recordkonversi as $record) : 
			$jsonkonversi[$record->huruf] = $record->angka;
		endforeach;
		endif;
		$jmlsks = 0;
		$jmlbobot = 0;
		$datakrs = $this->datakrs_model->find_distinct("","",$mhs);
		//print_r($datakrs);
		if (isset($datakrs) && is_array($datakrs) && count($datakrs)) : 
		    $no = 1;
			foreach ($datakrs as $record) :
				//echo $record->semester." = ".$record->sks." = ".$jmlsks." ".$record->kode_mata_kuliah." ".$record->nama_mata_kuliah."<br>";
            if(isset($jsonkonversi[$record->nilai_huruf]))
            {
                $nilaiangka = $jsonkonversi[$record->nilai_huruf];
                $jmlsks = $jmlsks + (int)$record->sks;
                
                $nilaiangka = $jsonkonversi[$record->nilai_huruf];
                $jmlbobot = $jmlbobot + ((int)$record->sks*(int)$nilaiangka);
            }
			  // echo $jmlsks;
			endforeach;
		endif;
		
		$ipk = 0;
		if($jmlbobot!="" and $jmlsks != "")
		{
			$ipk = round($jmlbobot/$jmlsks, 2);
		}
		//die($jmlsks." ini");
		return $ipk; 
		
    }
    
	function get_maksimal_krs_yang_boleh_diambil($ips=""){
		$mak_sks = 0;
		$CI =& get_instance();
		$CI->load->model('mahasiswa_model');


		$get_role_krs = $CI->mahasiswa_model->get_role_krs($ips);
		return $get_role_krs->maksimal_sks;
		//return $record_role->maksimal_sks;		
    }
    
    function hitung_ipsemester($sms, $nim){
		$CI =& get_instance();
		$CI->load->model('mahasiswa_model');

		//Ambil Master Konversi untuk depetakan menjadi Arra Konversi
		$recordkonversi = $CI->mahasiswa_model->get_konversi();
		foreach ($recordkonversi as $value) {
			$arr_konversi[$value->huruf] = $value->angka;
		}

		$jmlsks 	= 0;
		$jmlbobot 	= 0;
		$nilaiangka	= 0;
		// Ambil data KRS
		$datakrs = $CI->mahasiswa_model->get_find_ips($sms,$nim);
		
		if (isset($datakrs) && is_array($datakrs) && count($datakrs)) : 
		    $no = 1;
			foreach ($datakrs as $record) :
				//cek Nilai Hurufnya
				if(isset($arr_konversi[$record->nilai_huruf]))
					// Cek Nilai Konversi dari Huruf ke Angka
					$nilaiangka = $arr_konversi[$record->nilai_huruf];
			    if($nilaiangka!=""){
				    $jmlsks = $jmlsks + (int)$record->sks;
				    $nilaiangka = $arr_konversi[$record->nilai_huruf];
				    $jmlbobot = $jmlbobot + ((int)$record->sks*(int)$nilaiangka);
			    }
			  // echo $jmlsks;
			endforeach;
		endif;
		
		$ipk = 0;
		if($jmlbobot!="" and $jmlsks != "")
		{
			$ipk = round($jmlbobot/$jmlsks, 2);
			//echo $jmlbobot." ini";
		}
		return $ipk; 
		
    }

    function test(){
        echo "FAK";
    }
    
?>

	


