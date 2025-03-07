<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Convert
{
    public function hitung_ipsemester($semester, $nim)
	{
		$this->load->model('konversi/konversi_model', null, true);
		$sms = $semester;
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
		$datakrs = $this->datakrs_model->find_krs($sms,"",$mhs);
		$nilaiangka= 0;
		if (isset($datakrs) && is_array($datakrs) && count($datakrs)) : 
            $no = 1;
                foreach ($datakrs as $record) :
                if(isset($jsonkonversi[$record->nilai_huruf]))
                        $nilaiangka = $jsonkonversi[$record->nilai_huruf];
                if($nilaiangka!=""){
                    $jmlsks = $jmlsks + (int)$record->sks;
            
                    $nilaiangka = isset($jsonkonversi[$record->nilai_huruf]) ? $jsonkonversi[$record->nilai_huruf] : "";
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
	public function hitungjmlsks($nim)
	{
		$this->load->model('transkip/transkip_model', null, true);
		$datakrs = $this->transkip_model->getjumlahsks($nim);
		$jsonkrs[][] =array();
		$jmlsks=0;
		if (isset($datakrs) && is_array($datakrs) && count($datakrs)) :
		foreach ($datakrs as $record) : 
			 
				$jmlsks = $jmlsks + (int)$record->jumlahsks;
			 
		endforeach;
		endif;
		return $jmlsks; 
		
	}
	public function hitung_ipk($nim)
	{
		$this->load->model('konversi/konversi_model', null, true);
		$this->load->model('transkip/transkip_model', null, true);
		//die($mhs);
		$recordkonversi = $this->konversi_model->find_all();
		$jsonkonversi[] =array();
		if (isset($recordkonversi) && is_array($recordkonversi) && count($recordkonversi)) :
		foreach ($recordkonversi as $record) : 
			$jsonkonversi[$record->huruf] = $record->angka;
			//echo $record->huruf;
		endforeach;
		
		endif;
		$jmlsks = 0;
		$jmlbobot = 0;
		$datakrs = $this->transkip_model->find_transkip($nim,"","1");
		$jsonkrs[][] =array();
		$jmlsks=0;
		$semesterakhir = 1;
		
		$semester = 0;
		$jmlsks = 0;
		$jmlbobot = 0;
		$nilaiangka = "";
		if (isset($datakrs) && is_array($datakrs) && count($datakrs)) : 
		
			foreach ($datakrs as $record) :
			
			if(isset($jsonkonversi[$record->nilai_huruf]))
			{
				$nilaiangka = $jsonkonversi[$record->nilai_huruf];
				$jmlsks = $jmlsks + (int)$record->sks;
			   	$jmlbobot = $jmlbobot + ((int)$record->sks*(int)$nilaiangka);
			}else{
				//echo $record->nilai_huruf."NIlai huruf<br>";
				
			}			 
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
}