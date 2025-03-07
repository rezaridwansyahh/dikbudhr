<?php 
function random_color_part() {
    return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
}

function random_color() {
    return random_color_part() . random_color_part() . random_color_part();
}

function rupiah($angka){
	$hasil_rupiah = "Rp " . number_format($angka,2,',','.');
	return $hasil_rupiah;
}

function convertDate($source,$fromFormat,$toFormat){
	$dt = DateTime::createFromFormat($fromFormat,$source);
	if($dt){
		return $dt->format($toFormat);
	}
}
function getIndonesiaFormat($date){
	$dt = DateTime::createFromFormat('Y-m-d',$date);
	$bulans = array('Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','Nopember','Desember');
	return $dt->format('d')." ".$bulans[$dt->format('m')-1]." ".$dt->format('Y');
}
 
function Terbilang($x) {
	$abil = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
	  if ($x < 12)
		return " " . $abil[$x];
	  elseif ($x < 20)
		return Terbilang($x - 10) . " Belas";
	  elseif ($x < 100)
		return Terbilang($x / 10) . " Puluh" . Terbilang($x % 10);
	  elseif ($x < 200)
		return " Seratus" . Terbilang($x - 100);
	  elseif ($x < 1000)
		return Terbilang($x / 100) . " Ratus" . Terbilang($x % 100);
	  elseif ($x < 2000)
		return " Seribu" . Terbilang($x - 1000);
	  elseif ($x < 1000000)
		return Terbilang($x / 1000) . " Ribu" . Terbilang($x % 1000);
	  elseif ($x < 1000000000)
		return Terbilang($x / 1000000) . " Juta" . Terbilang($x % 1000000);

}

function getSatkerPegawai($nip){
	$ci = &get_instance();
	$row =  $ci->db->query('
			SELECT
			es1."ID" as es1_id,es1."NAMA_UNOR" as es1_text, es1."IS_SATKER" as es1_flag_satker, 
			es2."ID" as es2_id,es2."NAMA_UNOR" as es2_text, es2."IS_SATKER" as es2_flag_satker,
			es3."ID" as es3_id,es3."NAMA_UNOR" as es3_text, es3."IS_SATKER" as es3_flag_satker,
			es4."ID" as es4_id,es4."NAMA_UNOR" as es4_text, es4."IS_SATKER" as es4_flag_satker
		FROM
			pegawai p 
			left join  vw_unit_list vw on p."UNOR_ID" = vw."ID" 
			left join unitkerja es1 on vw."ESELON_1" = es1."ID"
			left join unitkerja es2 on vw."ESELON_2" = es2."ID"
			left join unitkerja es3 on vw."ESELON_3" = es3."ID"
			left join unitkerja es4 on vw."ESELON_4" = es4."ID"
		WHERE
			"NIP_BARU" = ?
	',array(trim($nip)))->first_row();
	if($row){
		if($row->es4_flag_satker==1){
				return $row->es4_id;
		}
		if($row->es3_flag_satker==1){
			return $row->es3_id;
		}
		if($row->es2_flag_satker==1){
			return $row->es2_id;
		}
		if($row->es1_flag_satker==1){
			return $row->es1_id;
		}
	}
	return null;
}
function getEselon1Pegawai($nip){
	$ci = &get_instance();
	$row =  $ci->db->query('
			SELECT
			es1."ID" as es1_id,es1."NAMA_UNOR" as es1_text, es1."IS_SATKER" as es1_flag_satker, 
			es2."ID" as es2_id,es2."NAMA_UNOR" as es2_text, es2."IS_SATKER" as es2_flag_satker,
			es3."ID" as es3_id,es3."NAMA_UNOR" as es3_text, es3."IS_SATKER" as es3_flag_satker,
			es4."ID" as es4_id,es4."NAMA_UNOR" as es4_text, es4."IS_SATKER" as es4_flag_satker
		FROM
			pegawai p 
			left join  vw_unit_list vw on p."UNOR_ID" = vw."ID" 
			left join unitkerja es1 on vw."ESELON_1" = es1."ID"
			left join unitkerja es2 on vw."ESELON_2" = es2."ID"
			left join unitkerja es3 on vw."ESELON_3" = es3."ID"
			left join unitkerja es4 on vw."ESELON_4" = es4."ID"
		WHERE
			"NIP_BARU" = ?
	',array(trim($nip)))->first_row();
	if($row){
		return $row->es1_id;
	}
	return null;
}

function get_list_status_layanan_kpo(){
	return array(
		array('id'=>1,'value'=>'Data Awal Perkiraan BKN'),
		array('id'=>2,'value'=>'TMS - Satker'),
		array('id'=>3,'value'=>'MS - Satker'),
		array('id'=>4,'value'=>'Di Kirim ke Sekretariat'),
		array('id'=>5,'value'=>'TMS - Sekretariat'),
		array('id'=>6,'value'=>'MS - Sekretariat'),
		array('id'=>7,'value'=>'Di Kirim ke Pusat'),
		array('id'=>8,'value'=>'TMS - Pusat'),
		array('id'=>9,'value'=>'MS - Pusat'),
		array('id'=>10,'value'=>'Sedang proses di BKN'),
			array('id'=>11,'value'=>'Belum Diproses (BKN)'),
			array('id'=>12,'value'=>'Batal Berkas (BKN)'),
			array('id'=>13,'value'=>'Pembuatan nota persetujuan (BKN)'),
			array('id'=>14,'value'=>'Berkas tidak lengkap (BTL BKN)'),
			array('id'=>15,'value'=>'Batal Berkas (BKN)'),
			array('id'=>16,'value'=>'TMS (BKN)'),
			array('id'=>17,'value'=>'Proses KP Selesai (BKN)')
	);
}
function get_list_status_layanan_ppo(){
	return array(
		array('id'=>1,'value'=>'Data Awal Perkiraan'),
		array('id'=>2,'value'=>'TMS - Satker'),
		array('id'=>3,'value'=>'MS - Satker'),
		array('id'=>4,'value'=>'Di Kirim ke Sekretariat'),
		array('id'=>5,'value'=>'TMS - Sekretariat'),
		array('id'=>6,'value'=>'MS - Sekretariat'),
		array('id'=>7,'value'=>'Di Kirim ke Pusat'),
		array('id'=>8,'value'=>'TMS - Pusat'),
		array('id'=>9,'value'=>'MS - Pusat'),
		array('id'=>10,'value'=>'Sedang proses di BKN')
	);
}

function get_status_layanan_kpo($id=null){
	$status_arr = get_list_status_layanan_kpo();
	foreach($status_arr as $row){
		if($row['id']==$id){
			return $row['value'];
		}
	}
}
function get_status_layanan_ppo($id=null){
	$status_arr = get_list_status_layanan_ppo();
	foreach($status_arr as $row){
		if($row['id']==$id){
			return $row['value'];
		}
	}
}
if(!function_exists('print_json')){
	function print_json($params){
		header("Content-type:application/json");
		if(is_object($params) || is_array($params)){
			 die(json_encode($params));	
		}
		else {
			die(($params));
		}	
	}
}
function getlistbeasiswa(){
		return array('1'=>'Beasiswa','2'=>'Mandiri');
	 
}
function getlistdatastatustb(){
		return array('1'=>'Pengajuan','2'=>'Disetujui','3'=>'Ditolak');
	 
}
function getliststatusatasan(){
		return array( '3'=>'Menunggu Persetujuan','1'=>'Diterima','2'=>'Ditolak');
	 
}
function Liststatus_izin(){
		return array(
			array('id'=>1,'value'=>'Menunggu Persetujuan'),
			array('id'=>2,'value'=>'Proses'),
			array('id'=>3,'value'=>'Disetujui'),
			array('id'=>4,'value'=>'Perubahan'),
			array('id'=>5,'value'=>'Ditangguhkan'),
			array('id'=>6,'value'=>'Ditolak')

		);
	 
}

function getlistdatastatus_cuti(){
		return array(
		array('id'=>1,'value'=>'<small class="label text-center bg-yellow">Menunggu Persetujuan</small>'),
		array('id'=>2,'value'=>'<small class="label text-center bg-yellow">Proses</small>'),
		array('id'=>3,'value'=>'<small class="label text-center bg-green">Disetujui</small>'),
		array('id'=>4,'value'=>'<small class="label text-center bg-blue">Perubahan</small>'),
		array('id'=>5,'value'=>'<small class="label text-center bg-red">Ditangguhkan</small>'),
		array('id'=>6,'value'=>'<small class="label text-center bg-red">Ditolak</small>')

	);
}
function get_status_cuti($id=null){
	$status_arr = getlistdatastatus_cuti();
	//print_r($status_arr);
	foreach($status_arr as $row){
		if($row['id']==$id){
			return $row['value'];
		}
	}
}

function get_list_pejabat_cuti(){
	return array(
		array('id'=>1,'value'=>'Verifikator'),
		array('id'=>2,'value'=>'Atasan Langsung'),
		array('id'=>3,'value'=>'PYBMC')
	);
}
function getlist_pejabat_cuti(){
		return array(
		array('id'=>1,'value'=>'<small class="label text-center bg-green">Verifikator</small>'),
		array('id'=>2,'value'=>'<small class="label text-center bg-yellow">Atasan Langsung</small>'),
		array('id'=>3,'value'=>'<small class="label text-center bg-blue">PYBMC</small>'), 

	);
}
function get_pejabat_cuti($id=null){
	$status_arr = getlist_pejabat_cuti();
	//print_r($status_arr);
	foreach($status_arr as $row){
		if($row['id']==$id){
			return $row['value'];
		}
	}
}
function getlist_status_jenis_izin(){
		return array(
		array('id'=>1,'value'=>'<small class="label text-center bg-green">Aktif</small>'),
		array('id'=>2,'value'=>'<small class="label text-center bg-red">Tidak Aktif</small>'),

	);
}
function status_jenis_izin($id=null){
	$status_arr = getlist_status_jenis_izin();
	//print_r($status_arr);
	foreach($status_arr as $row){
		if($row['id']==$id){
			return $row['value'];
		}
	}
}
function get_list_jenis_satker(){
	return array(
		array('id'=>1,'value'=>'PUSAT'),
		array('id'=>2,'value'=>'UPT'),
		array('id'=>3,'value'=>'PTN'),
		array('id'=>4,'value'=>'PTN BLU'),
		array('id'=>5,'value'=>'PTN B'),
		array('id'=>6,'value'=>'PTN BH')
	);
}
function jenis_jabatan(){
		return array(
		array('id'=>1,'value'=>'Struktural'),
		array('id'=>2,'value'=>'Fungsional Tertentu'),
		array('id'=>3,'value'=>'Fungsional Umum'),
	);
}
function get_jenis_jabatan($id=null){
	$status_arr = jenis_jabatan();
	//print_r($status_arr);
	foreach($status_arr as $row){
		if($row['id']==$id){
			return $row['value'];
		}
	}
}