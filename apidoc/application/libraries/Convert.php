<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Convert
{
	 
    public function Getsemester($nim="",$tahun="")
    {
    	$sms = 1;
    	$tahunnim 		= substr($nim,0,4);
		$tahunajaran 	= substr($tahun,0,4);
		$semesterta 	= substr($tahun,-1);
		$beda = (int)$tahunajaran - (int)$tahunnim;
		
		if($semesterta=="2"){
			$sms = ($beda+1)*2;
			 
		}else{
			$sms = (($beda+1)*2)-1;
		}
        return $sms;
    }
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

        return substr($rp, 1) . ',-';
    }
	public function Addseparator1($data)
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
	
	function Addseparator($number)
	{
		 
		$regex  = '/%((?:[\^!\-]|\+|\(|\=.)*)([0-9]+)?'.
				  '(?:#([0-9]+))?(?:\.([0-9]+))?([in%])/';
		if($number!="")
			$number = number_format($number, 0, '.', ',');
		else
			$number = "0";
			
		return $number;
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
		$jml = strlen(strip_tags($string));
		if($jml>$limit){
			return  substr(strip_tags($string), 0, $limit)."...";
		}else{
			return  strip_tags($string);
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
	 function Terbilang($x) {
		$abil = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
		  if ($x < 12)
			return " " . $abil[$x];
		  elseif ($x < 20)
			return $this->Terbilang($x - 10) . "belas";
		  elseif ($x < 100)
			return $this->Terbilang($x / 10) . " puluh" . $this->Terbilang($x % 10);
		  elseif ($x < 200)
			return " seratus" . $this->Terbilang($x - 100);
		  elseif ($x < 1000)
			return $this->Terbilang($x / 100) . " ratus" . $this->Terbilang($x % 100);
		  elseif ($x < 2000)
			return " seribu" . $this->Terbilang($x - 1000);
		  elseif ($x < 1000000)
			return $this->Terbilang($x / 1000) . " ribu" . $this->Terbilang($x % 1000);
		  elseif ($x < 1000000000)
			return $this->Terbilang($x / 1000000) . " juta" . $this->Terbilang($x % 1000000);
 
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
	/* Call this function to create a slug from $string */
	function create_slug($string){
	 $string = remove_accents($string);
	 $string = symbols_to_words($string);
	 $string = strtolower($string); // Force lowercase
	 $space_chars = array(
	  " ", // space
	  "…", // ellipsis
	  "–", // en dash
	  "—", // em dash
	  "/", // slash
	  "\\", // backslash
	  ":", // colon
	  ";", // semi-colon
	  ".", // period
	  "+", // plus sign
	  "#", // pound sign
	  "~", // tilde
	  "_", // underscore
	  "|", // pipe
	 );
	 foreach($space_chars as $char){
	  $string = str_replace($char, '-', $string); // Change spaces to dashes
	 }
	 // Only allow letters, numbers, and dashes
	 $string = preg_replace('/([^a-zA-Z0-9\-]+)/', '', $string);
	 $string = preg_replace('/-+/', '-', $string); // Clean up extra dashes
	 if(substr($string, -1)==='-'){ // Remove - from end
	  $string = substr($string, 0, -1);
	 }
	 if(substr($string, 0, 1)==='-'){ // Remove - from start
	  $string = substr($string, 1);
	 }
	 return $string;
	}

	/**
	 * Borrowed from WordPress
	 * Converts all accent characters to ASCII characters.
	 *
	 * If there are no accent characters, then the string given is just returned.
	 *
	 * @since 1.2.1
	 *
	 * @param string $string Text that might have accent characters
	 * @return string Filtered string with replaced "nice" characters.
	 */
	function remove_accents($string) {
	 if(!preg_match('/[\x80-\xff]/', $string)){
	  return $string;
	 }
	 if($this->seems_utf8($string)){
	  $chars = array(
	  // Decompositions for Latin-1 Supplement
	  chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
	  chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
	  chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
	  chr(195).chr(135) => 'C', chr(195).chr(136) => 'E',
	  chr(195).chr(137) => 'E', chr(195).chr(138) => 'E',
	  chr(195).chr(139) => 'E', chr(195).chr(140) => 'I',
	  chr(195).chr(141) => 'I', chr(195).chr(142) => 'I',
	  chr(195).chr(143) => 'I', chr(195).chr(145) => 'N',
	  chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
	  chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
	  chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
	  chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
	  chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
	  chr(195).chr(159) => 's', chr(195).chr(160) => 'a',
	  chr(195).chr(161) => 'a', chr(195).chr(162) => 'a',
	  chr(195).chr(163) => 'a', chr(195).chr(164) => 'a',
	  chr(195).chr(165) => 'a', chr(195).chr(167) => 'c',
	  chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
	  chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
	  chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
	  chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
	  chr(195).chr(177) => 'n', chr(195).chr(178) => 'o',
	  chr(195).chr(179) => 'o', chr(195).chr(180) => 'o',
	  chr(195).chr(181) => 'o', chr(195).chr(182) => 'o',
	  chr(195).chr(182) => 'o', chr(195).chr(185) => 'u',
	  chr(195).chr(186) => 'u', chr(195).chr(187) => 'u',
	  chr(195).chr(188) => 'u', chr(195).chr(189) => 'y',
	  chr(195).chr(191) => 'y',
	  // Decompositions for Latin Extended-A
	  chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
	  chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
	  chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
	  chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
	  chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
	  chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
	  chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
	  chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
	  chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
	  chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
	  chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
	  chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
	  chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
	  chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
	  chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
	  chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
	  chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
	  chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
	  chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
	  chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
	  chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
	  chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
	  chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
	  chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
	  chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
	  chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
	  chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
	  chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
	  chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
	  chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
	  chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
	  chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
	  chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
	  chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
	  chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
	  chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
	  chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
	  chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
	  chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
	  chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
	  chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
	  chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
	  chr(197).chr(148) => 'R',chr(197).chr(149) => 'r',
	  chr(197).chr(150) => 'R',chr(197).chr(151) => 'r',
	  chr(197).chr(152) => 'R',chr(197).chr(153) => 'r',
	  chr(197).chr(154) => 'S',chr(197).chr(155) => 's',
	  chr(197).chr(156) => 'S',chr(197).chr(157) => 's',
	  chr(197).chr(158) => 'S',chr(197).chr(159) => 's',
	  chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
	  chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
	  chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
	  chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
	  chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
	  chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
	  chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
	  chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
	  chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
	  chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
	  chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
	  chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
	  chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
	  chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
	  chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
	  chr(197).chr(190) => 'z', chr(197).chr(191) => 's',
	  // Euro Sign
	  chr(226).chr(130).chr(172) => 'E',
	  // GBP (Pound) Sign
	  chr(194).chr(163) => '');
	  $string = strtr($string, $chars);
	 } else {
	  // Assume ISO-8859-1 if not UTF-8
	  $chars['in'] = chr(128).chr(131).chr(138).chr(142).chr(154).chr(158)
	   .chr(159).chr(162).chr(165).chr(181).chr(192).chr(193).chr(194)
	   .chr(195).chr(196).chr(197).chr(199).chr(200).chr(201).chr(202)
	   .chr(203).chr(204).chr(205).chr(206).chr(207).chr(209).chr(210)
	   .chr(211).chr(212).chr(213).chr(214).chr(216).chr(217).chr(218)
	   .chr(219).chr(220).chr(221).chr(224).chr(225).chr(226).chr(227)
	   .chr(228).chr(229).chr(231).chr(232).chr(233).chr(234).chr(235)
	   .chr(236).chr(237).chr(238).chr(239).chr(241).chr(242).chr(243)
	   .chr(244).chr(245).chr(246).chr(248).chr(249).chr(250).chr(251)
	   .chr(252).chr(253).chr(255);
	  $chars['out'] = "EfSZszYcYuAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy";
	  $string = strtr($string, $chars['in'], $chars['out']);
	  $double_chars['in'] = array(chr(140), chr(156), chr(198), chr(208), chr(222), chr(223), chr(230), chr(240), chr(254));
	  $double_chars['out'] = array('OE', 'oe', 'AE', 'DH', 'TH', 'ss', 'ae', 'dh', 'th');
	  $string = str_replace($double_chars['in'], $double_chars['out'], $string);
	 }
	 return $string;
	}

	function symbols_to_words($output){
	 $output = str_replace('@', ' at ', $output);
	 $output = str_replace('%', ' percent ', $output);
	 $output = str_replace('&', ' and ', $output);
	 return $output;
	}
	function fmtDate($inDate,$format) {
		if($format!="month"){
		list($tahun, $bulan, $tanggal) =  preg_split('/[\s-]/',$inDate);
		}
		if($inDate == "0000-00-00")
			return "";
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
	function GetMonth($number) {
		 
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
		if($number!="" and $number <13 and $number >0)
			$hasil = $bulanindo[(int)$number];
		else
			$hasil = "";
	  
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
	function getrangeevaluasi($value){
		switch($value)
	    {
	        // 1st column
	        case $value <= 0:
	            echo "";
	          	 break;
	        case ($value >= 1 and $value <= 1.99):
	            echo "Tidak Baik";
	            break;
	        
	        // 2nd column
	        case $value > 1.99 and $value <= 2.99:
	            echo "Cukup";
	            break;
	        
	        case $value > 2.99 and $value <= 3.99:
	            echo "Baik";
	            break;
	        case $value > 3.99 and $value <= 4.99:
	            echo "Sangat Baik";
	        	 break;
	        default:
	        	echo "-";
	            break;
	    }

	}

}

?>