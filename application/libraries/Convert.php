<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Convert
{
    public function ToRp($data)
    {
		$data=TRIM($data);
        $jum = strlen($data);
        $rp = '';
        $i = -3;
        while($jum>0)
        {
            $rp = '.' . substr($data, $i, $jum>3 ? 3 : $jum) . $rp;
            $jum = $jum - 3;
            $i = $jum>3 ? $i - 3 : $i - $jum;
        }

        return 'Rp ' . substr($rp, 1) . ',-';
    }
	public function ToRpnosimbol($data)
    {
		$data=TRIM($data);
        $jum = strlen($data);
        $rp = '';
        $i = -3;
        while($jum>0)
        {
            $rp = '.' . substr($data, $i, $jum>3 ? 3 : $jum) . $rp;
            $jum = $jum - 3;
            $i = $jum>3 ? $i - 3 : $i - $jum;
        }

        return substr($rp, 1);
    }
	public function ToUsd($data)
    {
        return 'USD ' . $data;
    }
	public function nilaitokata($nilai)
    {
		$kata="";
		switch($nilai){
			case $nilai<50 :
				$kata = "Kurang";
			break;
			case $nilai>=50 and $nilai<60:
				$kata = "Sedang";
			break;
			case $nilai>=60 and $nilai<70:
				$kata = "Cukup";
			break;
			case $nilai>70 and $nilai<80:
				$kata = "Baik";
			break;
			case $nilai>=80:
				$kata = "Baik Sekali";
			break;
		}
        return $nilai.' => ' . $kata;
    }
	public function ToUsdbak($data)
    {
        $jum = strlen($data);
        $rp = '';
        $i = -3;
        while($jum>0)
        {
            $rp = '.' . substr($data, $i, $jum>3 ? 3 : $jum) . $rp;
            $jum = $jum - 3;
            $i = $jum>3 ? $i - 3 : $i - $jum;
        }

        return 'USD ' . substr($rp, 1);
    }
	function countwordscustom($string, $limit=30) {
		$jml = strlen($string);
		if($jml>$limit){
			return  substr($string, 0, $limit)."...";
		}else{
			return  $string;
		}
	 }
	 function countwordscustombyspace($string, $limit=40) {
		 $str_code = explode(" ",$string);
	 	return implode(" ",array_splice($str_code,0,$limit));
	 }
	 function SeoUrl1($string) {
		 $string = str_replace('-', ' ', $string);
	 	return str_replace(' ', '-', $string);
	 }
	 function Terbilang($x = "") {
	 	if($x != "" and $x > 0){
	 		$abil = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
		  if ($x < 12)
			return " " . $abil[$x];
		  elseif ($x < 20)
			return $this->Terbilang($x - 10) . " Belas";
		  elseif ($x < 100)
			return $this->Terbilang($x / 10) . " Puluh" . $this->Terbilang($x % 10);
		  elseif ($x < 200)
			return " Seratus" . $this->Terbilang($x - 100);
		  elseif ($x < 1000)
			return $this->Terbilang($x / 100) . " Ratus" . $this->Terbilang($x % 100);
		  elseif ($x < 2000)
			return " Seribu" . $this->Terbilang($x - 1000);
		  elseif ($x < 1000000)
			return $this->Terbilang($x / 1000) . " Ribu" . $this->Terbilang($x % 1000);
		  elseif ($x < 1000000000)
			return $this->Terbilang($x / 1000000) . " Juta" . $this->Terbilang($x % 1000000);
	 	}else{
	 		return "";
	 	}
		
 
	 }
	function remove_accent($str)
	{
	  $a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ');
	  $b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o');
	  return str_replace($a, $b, $str);
	}
	
	function SeoUrl($str)
	{
	  return strtolower(preg_replace(array('/[^a-zA-Z0-9 -]/', '/[ -]+/', '/^-|-$/'), 
	  array('', '-', ''), $this->remove_accent($str)));
	}
	function fmtDate($inDate,$format) {
		if($inDate == "0000-00-00")
			return "";
		if($inDate == "")
			return "";
		if($format!="month"){
		list($tahun, $bulan, $tanggal) =  preg_split('/[\s-]/',$inDate);
		}
		 
		$bulanindo = array(
						"" => "",
						"1" => "Januari",
						"2" => "Februari",
						"3" => "Maret",
						"4" => "April",
						"5" => "Mei",
						"6" => "Juni",
						"7" => "Juli",
						"8" => "Agustus",
						"9" => "September",
						"10" => "Oktober",
						"11" => "November",
						"12" => "Desember"
						);
		switch($format) {
			case "dd-mm-yyyy time":
			  $hasil = $tanggal."-".$bulan."-".$tahun." ".$jam;
			break;
			case "dd-mm-yyyy":
				
			  $hasil = $tanggal."-".$bulan."-".$tahun;
			 
			break;
			case "dd/mm/yyyy":
				
			  $hasil = $tanggal."/".$bulan."/".$tahun;
			 
			break;
			case "dd month yyyy time":
			  $hasil = $tanggal." ".$bulanindo[(int)$bulan]." ".$tahun;
			case "dd-month-yyyy":
			  $hasil = $tanggal."-".$bulanindo[(int)$bulan]."-".$tahun;
			  
			break;
			case "dd month yyyy":
			  $hasil = $tanggal." ".$bulanindo[(int)$bulan]." ".$tahun;
			break;
			case "month-yyyy":
			  $hasil = $bulanindo[$bulan]."-".$tahun;
			  break;
			case "month": 
			  $hasil = $bulanindo[$inDate];
			  break;
			case "yyyy":
			  $hasil = $tahun;
			break;
		
		}	
	   if (empty($inDate)) $hasil="-";
	   
	   return $hasil;
	}
	
	function Tohari($hari) {
		if($hari == "")
			return "";
		 
		 
		$hari_indo = array(
						"" => "",
						"Sun" => "Minggu",
						"Mon" => "Senin",
						"Tue" => "Selasa",
						"Wed" => "Rabu",
						"Thu" => "Kamis",
						"Fri" => "Jumat",
						"Sat" => "Sabtu"
					);
		 
	   if (isset($hari_indo[$hari])){
	   		return $hari_indo[$hari];
	   }else
	   		return "-";
	}
	function Gethari($tanggal) {
		if($tanggal == "")
			return "";
		$nameOfDay = date('D', strtotime($tanggal));
		 
		$hari_indo = array(
						"" => "",
						"Sun" => "Minggu",
						"Mon" => "Senin",
						"Tue" => "Selasa",
						"Wed" => "Rabu",
						"Thu" => "Kamis",
						"Fri" => "Jumat",
						"Sat" => "Sabtu"
					);
		 
	   if (isset($hari_indo[$nameOfDay])){
	   		return $hari_indo[$nameOfDay];
	   }else
	   		return "-";
	}
	function fmtDateTime($inDate,$format) {
		$date = explode(" ", $inDate);
		$time = $date[1];
		$tgl = explode("-", $date[0]);
		list($tahun, $bulan, $tanggal) =  preg_split('/[\s-]/',$tgl[0]."-".$tgl[1]."-".$tgl[2]);
		 
		 
		$bulanindo = array(
						"" => "",
						"1" => "Januari",
						"2" => "Februari",
						"3" => "Maret",
						"4" => "April",
						"5" => "Mei",
						"6" => "Juni",
						"7" => "Juli",
						"8" => "Agustus",
						"9" => "September",
						"10" => "Oktober",
						"11" => "November",
						"12" => "Desember"
						);
		switch($format) {
			case "dd-mm-yyyy time":
			  $hasil = $tanggal."-".$bulan."-".$tahun." ".$jam;
			break;
			case "dd-mm-yyyy":
			//print $tanggal;
			  $hasil = $tanggal."-".$bulan."-".$tahun;
			 
			break;
			case "dd/mm/yyyy":
			  $hasil = $tanggal."/".$bulan."/".$tahun;
			 
			break;
			case "dd-month-yyyy":
			  $hasil = $tanggal."-".$bulanindo[(int)$bulan]."-".$tahun;
			  
			break;
			case "dd month yyyy":
			  $hasil = $tanggal." ".$bulanindo[(int)$bulan]." ".$tahun;
			break;
			case "month-yyyy":
			  $hasil = $bulanindo[$bulan]."-".$tahun;
			  break;
			case "month": 
			  $hasil = $bulanindo[$inDate];
			  break;
			case "yyyy":
			  $hasil = $tahun;
			break;
		
		}	
	   if (empty($inDate)) $hasil="-";
	   
	   return $hasil." ".$time;
	}
	function fmtDate2($inDate,$format) {
		$tgl = explode("/", $inDate);
		//echo $inDate."<br>";
		list($tahun, $bulan, $tanggal) =  preg_split('/[\s-]/',$tgl[2]."-".$tgl[1]."-".$tgl[0]);
		 
		$bulanindo = array(
						"" => "",
						"1" => "Januari",
						"2" => "Februari",
						"3" => "Maret",
						"4" => "April",
						"5" => "Mei",
						"6" => "Juni",
						"7" => "Juli",
						"8" => "Agustus",
						"9" => "September",
						"10" => "Oktober",
						"11" => "November",
						"12" => "Desember"
						);
		switch($format) {
			case "yyyy-mm-dd":
			  $hasil = $tahun."-".$bulan."-".$tanggal;
			break;
			case "dd-mm-yyyy time":
			  $hasil = $tanggal."-".$bulan."-".$tahun." ".$jam;
			break;
			case "dd-mm-yyyy":
				
			  $hasil = $tanggal."-".$bulan."-".$tahun;
			 
			break;
			case "dd/mm/yyyy":
				
			  $hasil = $tanggal."/".$bulan."/".$tahun;
			 
			break;
			case "dd-month-yyyy":
			  $hasil = $tanggal."-".$bulanindo[(int)$bulan]."-".$tahun;
			  
			break;
			case "dd month yyyy":
			  $hasil = $tanggal." ".$bulanindo[(int)$bulan]." ".$tahun;
			break;
			case "month-yyyy":
			  $hasil = $bulanindo[$bulan]."-".$tahun;
			  break;
			case "month": 
			  $hasil = $bulanindo[$inDate];
			  break;
			case "yyyy":
			  $hasil = $tahun;
			break;
		
		}	
	   if (empty($inDate)) $hasil="-";
	   
	   return $hasil;
	}
	function fmtDate3($inDate,$format) {
		$tgl = explode("/", $inDate);
		//echo $inDate."<br>";
		list($tahun, $bulan, $tanggal) =  preg_split('/[\s-]/',$tgl[2]."-".$tgl[0]."-".$tgl[1]); // asli excellnya bulan tanggal tahun
		 
		$bulanindo = array(
						"" => "",
						"1" => "Januari",
						"2" => "Februari",
						"3" => "Maret",
						"4" => "April",
						"5" => "Mei",
						"6" => "Juni",
						"7" => "Juli",
						"8" => "Agustus",
						"9" => "September",
						"10" => "Oktober",
						"11" => "November",
						"12" => "Desember"
						);
		switch($format) {
			case "yyyy-mm-dd":
				if($bulan < 10)
					$bulan = "0".$bulan;
				if($tanggal < 10)
					$tanggal = "0".$tanggal;
					
			  $hasil = $tahun."-".$bulan."-".$tanggal;
			break;
			case "dd-mm-yyyy time":
			  $hasil = $tanggal."-".$bulan."-".$tahun." ".$jam;
			break;
			case "dd-mm-yyyy":
				if($bulan < 10)
					$bulan = "0".$bulan;
				if($tanggal < 10)
					$tanggal = "0".$tanggal;
					
			  $hasil = $tanggal."-".$bulan."-".$tahun;
			 
			break;
			case "dd/mm/yyyy":
				if($bulan < 10)
					$bulan = "0".$bulan;
				if($tanggal < 10)
					$tanggal = "0".$tanggal;
					
			  $hasil = $tanggal."/".$bulan."/".$tahun;
			 
			break;
			case "dd-month-yyyy":
				if($bulan < 10)
					$bulan = "0".$bulan;
				if($tanggal < 10)
					$tanggal = "0".$tanggal;
					
			  $hasil = $tanggal."-".$bulanindo[(int)$bulan]."-".$tahun;
			  
			break;
			case "dd month yyyy":
			  $hasil = $tanggal." ".$bulanindo[(int)$bulan]." ".$tahun;
			break;
			case "month-yyyy":
			  $hasil = $bulanindo[$bulan]."-".$tahun;
			  break;
			case "month": 
			  $hasil = $bulanindo[$inDate];
			  break;
			case "yyyy":
			  $hasil = $tahun;
			break;
		
		}	
	   if (empty($inDate)) $hasil="-";
	   
	   return $hasil;
	}
	function getmonth($month) {
		$namabulan = $month;
		$bulanindo = array(
						"" => "",
						"01" => "Januari",
						"02" => "Februari",
						"03" => "MARET",
						"04" => "April",
						"05" => "Mei",
						"06" => "Juni",
						"07" => "Juli",
						"08" => "Agustus",
						"09" => "September",
						"10" => "Oktober",
						"11" => "November",
						"12" => "Desember"
						);
		if(isset($bulanindo[$month]))
			$namabulan = $bulanindo[$month];
	  	
	   if (empty($month)) $namabulan="-";
	   
	   return $namabulan;
	}
	function getday($dayname) {
		$hasil = "";
		$dayindo = array(
		   "" => "",
		   "Monday" => "Senin",
		   "Tuesday" => "Selasa",
		   "Wednesday" => "Rabu",
		   "Thursday" => "Kamis",
		   "Friday" => "Jumat",
		   "Saturday" => "Sabtu",
		   "Sunday" => "Minggu"
		);
		if(isset($dayindo["$dayname"])){
			$hasil = $dayindo["$dayname"];
		}
	   return $hasil;
	}
	function random_string($length,$kode="") {
		$key = '';
		$keys = array_merge(range(0, 9), range('a', 'z'));

		for ($i = 0; $i < $length; $i++) {
			$key .= $keys[array_rand($keys)];
		}

		return $key.$kode;
	}

}

?>