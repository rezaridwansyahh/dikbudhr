<?php defined('BASEPATH') || exit('No direct script access allowed');
/*Add By Bana*/
class Ropeg_model extends BF_Model
{

	public function detail_by_customized_satker_bak($array_satker, $nip)
	{
		$sql = 'WITH RECURSIVE r as (SELECT "ID" FROM hris.unitkerja WHERE "ID" in (\'' . $array_satker . '\')
				UNION ALL
				SELECT a."ID" FROM hris.unitkerja a JOIN r on a."DIATASAN_ID" = r."ID")
				SELECT pegawai.*,b1."KELAS" AS "KELAS_JABATAN" ,
				b1."NAMA_JABATAN" AS "JABATAN_INSTANSI_REAL_NAMA",
				pendidikan."NAMA" AS "PENDIDIKAN_NAMA",
				g."NAMA_PANGKAT",g."NAMA" as "NAMA_GOLONGAN",
				case when tkpendidikan."NAMA" is null then tk2."NAMA" else tkpendidikan."NAMA" end  as "TK_PENDIDIKAN_NAMA"
				FROM hris.pegawai 
				pegawai JOIN r on pegawai."UNOR_ID" = r."ID" 
				left join jabatan as b1 on pegawai."JABATAN_INSTANSI_REAL_ID" = b1."KODE_JABATAN"
				left join hris.pendidikan as pendidikan on pendidikan."ID" = pegawai."PENDIDIKAN_ID"
			  	left join hris.tkpendidikan as tkpendidikan on pendidikan."TINGKAT_PENDIDIKAN_ID" = tkpendidikan."ID"
				left join hris.tkpendidikan as tk2 on pegawai."TK_PENDIDIKAN" = tk2."ID"  
				left join hris.golongan as g on pegawai."GOL_ID" = g."ID"  
				WHERE pegawai."NIP_BARU"=?';
		$query = $this->db->query($sql, array($nip));
		return $query->first_row();
	}
	public function detail_by_customized_satker($array_satker, $nip)
	{
		$sql = 'select pegawai."ID",
			    "PNS_ID",
			    "NIP_LAMA",
			    "NIP_BARU",
			    pegawai."NAMA",
			    "GELAR_DEPAN",
			    "GELAR_BELAKANG",
			    "TEMPAT_LAHIR_ID",
			    "TGL_LAHIR",
			    "JENIS_KELAMIN",
			    "AGAMA_ID",
			    "JENIS_KAWIN_ID",
			    "NIK",
			    "NOMOR_DARURAT",
			    "NOMOR_HP",
			    "EMAIL",
			    "ALAMAT",
			    "NPWP",
			    "BPJS",
			    "JENIS_PEGAWAI_ID",
			    "KEDUDUKAN_HUKUM_ID",
			    "STATUS_CPNS_PNS",
			    "KARTU_PEGAWAI",
			    "NOMOR_SK_CPNS",
			    "TGL_SK_CPNS",
			    "TMT_CPNS",
			    "TMT_PNS",
			    "GOL_AWAL_ID",
			    "GOL_ID",
			    "TMT_GOLONGAN",
			    "MK_TAHUN",
			    "MK_BULAN",
			    "JENIS_JABATAN_IDx",
			    "JABATAN_ID",
			    "TMT_JABATAN",
			    "PENDIDIKAN_ID",
			    "TAHUN_LULUS",
			    "KPKN_ID",
			    "LOKASI_KERJA_ID",
			    "UNOR_ID",
			    "UNOR_INDUK_ID",
			    "INSTANSI_INDUK_ID",
			    "INSTANSI_KERJA_ID",
			    "SATUAN_KERJA_INDUK_ID",
			    "SATUAN_KERJA_KERJA_ID",
			    "GOLONGAN_DARAH",
			    "PHOTO",
			    "TMT_PENSIUN",
			    TRIM("LOKASI_KERJA") AS "LOKASI_KERJA",
			    "JML_ISTRI",
			    "JML_ANAK",
			    "NO_SURAT_DOKTER",
			    "TGL_SURAT_DOKTER",
			    "NO_BEBAS_NARKOBA",
			    "TGL_BEBAS_NARKOBA",
			    "NO_CATATAN_POLISI",
			    "TGL_CATATAN_POLISI",
			    "AKTE_KELAHIRAN",
			    "STATUS_HIDUP",
			    "AKTE_MENINGGAL",
			    "TGL_MENINGGAL",
			    "NO_ASKES",
			    "NO_TASPEN",
			    "TGL_NPWP",
			    "TEMPAT_LAHIR",
			    TRIM("PENDIDIKAN") AS "PENDIDDIKAN",
			    "TK_PENDIDIKAN",
			    TRIM("TEMPAT_LAHIR_NAMA") AS "TEMPAT_LAHIR_NAMA",
			    TRIM("JENIS_JABATAN_NAMA") AS "JENIS_JABATAN_NAMA",
			    TRIM("JABATAN_NAMA") AS "JABATAN_NAMA",
			    TRIM("KPKN_NAMA") AS "KPKN_NAMA",
			    TRIM("INSTANSI_INDUK_NAMA") AS "INSTANSI_INDUK_NAMA",
			    TRIM("INSTANSI_KERJA_NAMA") AS "INSTANSI_KERJA_NAMA",
			    TRIM("SATUAN_KERJA_INDUK_NAMA") AS "SATUAN_KERJA_INDUK_NAMA",
			    TRIM("SATUAN_KERJA_NAMA") AS "SATUAN_KERJA_NAMA",
			    "JABATAN_INSTANSI_ID",
			    "BUP",
			    TRIM("JABATAN_INSTANSI_NAMA") AS "JABATAN_INSTANSI_NAMA",
			    "JENIS_JABATAN_ID",
			    "terminated_date",
			    "status_pegawai",
			    "JABATAN_PPNPN",
			    "JABATAN_INSTANSI_REAL_ID",
			    "CREATED_DATE",
			    "CREATED_BY",
			    "UPDATED_DATE",
			    "UPDATED_BY",
			    "EMAIL_DIKBUD_BAK",
			    "EMAIL_DIKBUD",
			    "KODECEPAT",
			    "IS_DOSEN",
			    "MK_TAHUN_SWASTA",
			    "MK_BULAN_SWASTA",
			    b1."KELAS" AS "KELAS_JABATAN" ,
				b1."NAMA_JABATAN" AS "JABATAN_INSTANSI_REAL_NAMA",
				pendidikan."NAMA" AS "PENDIDIKAN_NAMA",
				case when tkpendidikan."NAMA" is null then tk2."NAMA" else tkpendidikan."NAMA" end  as "TK_PENDIDIKAN_NAMA",
				g."NAMA_PANGKAT",g."NAMA" as "NAMA_GOLONGAN"
				FROM hris.pegawai 
				pegawai 
				left join jabatan as b1 on pegawai."JABATAN_INSTANSI_REAL_ID" = b1."KODE_JABATAN"
				left join hris.pendidikan as pendidikan on pendidikan."ID" = pegawai."PENDIDIKAN_ID"
			  	left join hris.tkpendidikan as tkpendidikan on pendidikan."TINGKAT_PENDIDIKAN_ID" = tkpendidikan."ID"
				left join hris.tkpendidikan as tk2 on pegawai."TK_PENDIDIKAN" = tk2."ID"  
				left join hris.golongan as g on pegawai."GOL_ID" = g."ID"  
				WHERE pegawai."NIP_BARU"=?';
		$query = $this->db->query($sql, array($nip));
		return $query->first_row();
	}
	public function detail_by_customized_satker_asigo($array_satker, $nip)
	{
		$sql = 'select pegawai."ID",
			    "PNS_ID",
			    "NIP_LAMA",
			    "NIP_BARU",
			    pegawai."NAMA",
			    "GELAR_DEPAN",
			    "GELAR_BELAKANG",
			    "TEMPAT_LAHIR_ID",
			    "TGL_LAHIR",
			    "JENIS_KELAMIN",
			    "AGAMA_ID",
			    "JENIS_KAWIN_ID",
			    "NIK",
			    "NOMOR_DARURAT",
			    "NOMOR_HP",
			    "EMAIL",
			    "ALAMAT",
			    "NPWP",
			    "BPJS",
			    "JENIS_PEGAWAI_ID",
			    "KEDUDUKAN_HUKUM_ID",
			    "STATUS_CPNS_PNS",
			    "KARTU_PEGAWAI",
			    "NOMOR_SK_CPNS",
			    "TGL_SK_CPNS",
			    "TMT_CPNS",
			    "TMT_PNS",
			    "GOL_AWAL_ID",
			    "GOL_ID",
			    "TMT_GOLONGAN",
			    "MK_TAHUN",
			    "MK_BULAN",
			    "JENIS_JABATAN_IDx",
			    "JABATAN_ID",
			    "TMT_JABATAN",
			    "PENDIDIKAN_ID",
			    "TAHUN_LULUS",
			    "KPKN_ID",
			    "LOKASI_KERJA_ID",
			    "UNOR_ID",
			    "UNOR_INDUK_ID",
			    "INSTANSI_INDUK_ID",
			    "INSTANSI_KERJA_ID",
			    "SATUAN_KERJA_INDUK_ID",
			    "SATUAN_KERJA_KERJA_ID",
			    "GOLONGAN_DARAH",
			    "PHOTO",
			    "TMT_PENSIUN",
			    TRIM("LOKASI_KERJA") AS "LOKASI_KERJA",
			    "JML_ISTRI",
			    "JML_ANAK",
			    "NO_SURAT_DOKTER",
			    "TGL_SURAT_DOKTER",
			    "NO_BEBAS_NARKOBA",
			    "TGL_BEBAS_NARKOBA",
			    "NO_CATATAN_POLISI",
			    "TGL_CATATAN_POLISI",
			    "AKTE_KELAHIRAN",
			    "STATUS_HIDUP",
			    "AKTE_MENINGGAL",
			    "TGL_MENINGGAL",
			    "NO_ASKES",
			    "NO_TASPEN",
			    "TGL_NPWP",
			    "TEMPAT_LAHIR",
			    TRIM("PENDIDIKAN") AS "PENDIDDIKAN",
			    "TK_PENDIDIKAN",
			    TRIM("TEMPAT_LAHIR_NAMA") AS "TEMPAT_LAHIR_NAMA",
			    TRIM("JENIS_JABATAN_NAMA") AS "JENIS_JABATAN_NAMA",
			    TRIM("JABATAN_NAMA") AS "JABATAN_NAMA",
			    TRIM("KPKN_NAMA") AS "KPKN_NAMA",
			    TRIM("INSTANSI_INDUK_NAMA") AS "INSTANSI_INDUK_NAMA",
			    TRIM("INSTANSI_KERJA_NAMA") AS "INSTANSI_KERJA_NAMA",
			    TRIM("SATUAN_KERJA_INDUK_NAMA") AS "SATUAN_KERJA_INDUK_NAMA",
			    TRIM("SATUAN_KERJA_NAMA") AS "SATUAN_KERJA_NAMA",
			    "JABATAN_INSTANSI_ID",
			    "BUP",
			    TRIM("JABATAN_INSTANSI_NAMA") AS "JABATAN_INSTANSI_NAMA",
			    "JENIS_JABATAN_ID",
			    "terminated_date",
			    "status_pegawai",
			    "JABATAN_PPNPN",
			    "JABATAN_INSTANSI_REAL_ID",
			    "CREATED_DATE",
			    "CREATED_BY",
			    "UPDATED_DATE",
			    "UPDATED_BY",
			    "EMAIL_DIKBUD_BAK",
			    "EMAIL_DIKBUD",
			    "KODECEPAT",
			    "IS_DOSEN",
			    "MK_TAHUN_SWASTA",
			    "MK_BULAN_SWASTA",
			    b1."KELAS" AS "KELAS_JABATAN" ,
				b1."NAMA_JABATAN" AS "JABATAN_INSTANSI_REAL_NAMA",
				pendidikan."NAMA" AS "PENDIDIKAN_NAMA",
				case when tkpendidikan."NAMA" is null then tk2."NAMA" else tkpendidikan."NAMA" end  as "TK_PENDIDIKAN_NAMA",
				g."NAMA_PANGKAT",g."NAMA" as "NAMA_GOLONGAN"
				FROM hris.pegawai 
				pegawai 
				left join jabatan as b1 on pegawai."JABATAN_INSTANSI_REAL_ID" = b1."KODE_JABATAN"
				left join hris.pendidikan as pendidikan on pendidikan."ID" = pegawai."PENDIDIKAN_ID"
			  	left join hris.tkpendidikan as tkpendidikan on pendidikan."TINGKAT_PENDIDIKAN_ID" = tkpendidikan."ID"
				left join hris.tkpendidikan as tk2 on pegawai."TK_PENDIDIKAN" = tk2."ID"  
				left join hris.golongan as g on pegawai."GOL_ID" = g."ID"  
				WHERE pegawai."NIP_BARU"=?';
		$query = $this->db->query($sql, array($nip));
		return $query->first_row();
	}
	public function detail_by_customized_satker_asigo_bak($array_satker, $nip)
	{
		$sql = 'WITH RECURSIVE r as (SELECT "ID" FROM hris.unitkerja WHERE "ID" in (\'' . $array_satker . '\')
				UNION ALL
				SELECT a."ID" FROM hris.unitkerja a JOIN r on a."DIATASAN_ID" = r."ID")
				SELECT 
				pegawai."ID",
			    "PNS_ID",
			    "NIP_LAMA",
			    "NIP_BARU",
			    pegawai."NAMA",
			    "GELAR_DEPAN",
			    "GELAR_BELAKANG",
			    "TEMPAT_LAHIR_ID",
			    "TGL_LAHIR",
			    "JENIS_KELAMIN",
			    "AGAMA_ID",
			    "JENIS_KAWIN_ID",
			    "NIK",
			    "NOMOR_DARURAT",
			    "NOMOR_HP",
			    "EMAIL",
			    "ALAMAT",
			    "NPWP",
			    "BPJS",
			    "JENIS_PEGAWAI_ID",
			    "KEDUDUKAN_HUKUM_ID",
			    "STATUS_CPNS_PNS",
			    "KARTU_PEGAWAI",
			    "NOMOR_SK_CPNS",
			    "TGL_SK_CPNS",
			    "TMT_CPNS",
			    "TMT_PNS",
			    "GOL_AWAL_ID",
			    "GOL_ID",
			    "TMT_GOLONGAN",
			    "MK_TAHUN",
			    "MK_BULAN",
			    "JENIS_JABATAN_IDx",
			    "JABATAN_ID",
			    "TMT_JABATAN",
			    "PENDIDIKAN_ID",
			    "TAHUN_LULUS",
			    "KPKN_ID",
			    "LOKASI_KERJA_ID",
			    "UNOR_ID",
			    "UNOR_INDUK_ID",
			    "INSTANSI_INDUK_ID",
			    "INSTANSI_KERJA_ID",
			    "SATUAN_KERJA_INDUK_ID",
			    "SATUAN_KERJA_KERJA_ID",
			    "GOLONGAN_DARAH",
			    "PHOTO",
			    "TMT_PENSIUN",
			    TRIM("LOKASI_KERJA") AS "LOKASI_KERJA",
			    "JML_ISTRI",
			    "JML_ANAK",
			    "NO_SURAT_DOKTER",
			    "TGL_SURAT_DOKTER",
			    "NO_BEBAS_NARKOBA",
			    "TGL_BEBAS_NARKOBA",
			    "NO_CATATAN_POLISI",
			    "TGL_CATATAN_POLISI",
			    "AKTE_KELAHIRAN",
			    "STATUS_HIDUP",
			    "AKTE_MENINGGAL",
			    "TGL_MENINGGAL",
			    "NO_ASKES",
			    "NO_TASPEN",
			    "TGL_NPWP",
			    "TEMPAT_LAHIR",
			    TRIM("PENDIDIKAN") AS "PENDIDDIKAN",
			    "TK_PENDIDIKAN",
			    TRIM("TEMPAT_LAHIR_NAMA") AS "TEMPAT_LAHIR_NAMA",
			    TRIM("JENIS_JABATAN_NAMA") AS "JENIS_JABATAN_NAMA",
			    TRIM("JABATAN_NAMA") AS "JABATAN_NAMA",
			    TRIM("KPKN_NAMA") AS "KPKN_NAMA",
			    TRIM("INSTANSI_INDUK_NAMA") AS "INSTANSI_INDUK_NAMA",
			    TRIM("INSTANSI_KERJA_NAMA") AS "INSTANSI_KERJA_NAMA",
			    TRIM("SATUAN_KERJA_INDUK_NAMA") AS "SATUAN_KERJA_INDUK_NAMA",
			    TRIM("SATUAN_KERJA_NAMA") AS "SATUAN_KERJA_NAMA",
			    "JABATAN_INSTANSI_ID",
			    "BUP",
			    TRIM("JABATAN_INSTANSI_NAMA") AS "JABATAN_INSTANSI_NAMA",
			    "JENIS_JABATAN_ID",
			    "terminated_date",
			    "status_pegawai",
			    "JABATAN_PPNPN",
			    "JABATAN_INSTANSI_REAL_ID",
			    "CREATED_DATE",
			    "CREATED_BY",
			    "UPDATED_DATE",
			    "UPDATED_BY",
			    "EMAIL_DIKBUD_BAK",
			    "EMAIL_DIKBUD",
			    "KODECEPAT",
			    "IS_DOSEN",
			    "MK_TAHUN_SWASTA",
			    "MK_BULAN_SWASTA",
			    b1."KELAS" AS "KELAS_JABATAN" ,
				b1."NAMA_JABATAN" AS "JABATAN_INSTANSI_REAL_NAMA",
				pendidikan."NAMA" AS "PENDIDIKAN_NAMA",
				case when tkpendidikan."NAMA" is null then tk2."NAMA" else tkpendidikan."NAMA" end  as "TK_PENDIDIKAN_NAMA",
				g."NAMA_PANGKAT",g."NAMA" as "NAMA_GOLONGAN"
				FROM hris.pegawai 
				pegawai JOIN r on pegawai."UNOR_ID" = r."ID" 
				left join jabatan as b1 on pegawai."JABATAN_INSTANSI_REAL_ID" = b1."KODE_JABATAN"
				left join hris.pendidikan as pendidikan on pendidikan."ID" = pegawai."PENDIDIKAN_ID"
			  	left join hris.tkpendidikan as tkpendidikan on pendidikan."TINGKAT_PENDIDIKAN_ID" = tkpendidikan."ID"
				left join hris.tkpendidikan as tk2 on pegawai."TK_PENDIDIKAN" = tk2."ID"  
				left join hris.golongan as g on pegawai."GOL_ID" = g."ID"  
				WHERE pegawai."NIP_BARU"=?';
		$query = $this->db->query($sql, array($nip));
		return $query->first_row();
	}
	public function find_all_asesmen($satker_id, $nama_with_nip, $start, $limit)
	{
		$sql = 'SELECT pegawai."NAMA","NIP_BARU",b."NAMA_JABATAN",e."NAMA" as "JENIS_JABATAN",
				b."KELAS",c."NAMA_UNOR_ESELON_1",c."NAMA_SATKER","EMAIL","EMAIL_DIKBUD","NOMOR_HP","NOMOR_DARURAT","PHOTO",
				case when tkpendidikan."NAMA" is null then tk2."NAMA" else tkpendidikan."NAMA" end  as nama_tk_pendidikan,
				case when tkpendidikan."TINGKAT" is null then tk2."TINGKAT" else tkpendidikan."TINGKAT" end as tingkat 
				FROM hris.pegawai as pegawai 
				left join hris.jabatan as b on pegawai."JABATAN_INSTANSI_ID" = b."KODE_JABATAN"
				left join hris.jabatan as b1 on pegawai."JABATAN_INSTANSI_REAL_ID" = b1."KODE_JABATAN"
				left join hris.vw_unor_satker as c on pegawai."UNOR_ID" = c."ID_UNOR"
				left join hris.jenis_jabatan as e on e."ID" = pegawai."JENIS_JABATAN_ID"::varchar 
				left join hris."pns_aktif" "pa" ON "pegawai"."ID"="pa"."ID"
				left join hris.pendidikan as pendidikan on pendidikan."ID" = pegawai."PENDIDIKAN_ID"
			  	left join hris.tkpendidikan as tkpendidikan on pendidikan."TINGKAT_PENDIDIKAN_ID" = tkpendidikan."ID"
				left join hris.tkpendidikan as tk2 on pegawai."TK_PENDIDIKAN" = tk2."ID"  
				where c."ID_SATKER"=? AND
				upper(concat(pegawai."NAMA","NIP_BARU")) LIKE ?
				and "pa"."ID" is not null 
				AND "pegawai"."KEDUDUKAN_HUKUM_ID" <> \'99\' 
				and "pegawai"."KEDUDUKAN_HUKUM_ID" <> \'66\' 
				and "pegawai"."KEDUDUKAN_HUKUM_ID" <> \'52\' 
				and "pegawai"."KEDUDUKAN_HUKUM_ID" <> \'20\' 
				and "pegawai"."KEDUDUKAN_HUKUM_ID" <> \'04\' 
				AND ("status_pegawai" != 3 or "status_pegawai" is null )
				ORDER BY "NAMA" ASC LIMIT ? OFFSET ?';
		$nama_with_nip = "%" . strtoupper($nama_with_nip) . "%";
		$query = $this->db->query($sql, array($satker_id, $nama_with_nip, $limit, $start));
		$data = $query->result();
		//return parent::find_all();
		//var_dump($sql);
		return $data;
	}

	public function count_list_asesmen($satker_id, $nama_with_nip)
	{
		$sql = 'SELECT COUNT(*) as jml
				FROM hris.pegawai as pegawai 
				left join hris.jabatan as b on pegawai."JABATAN_INSTANSI_ID" = b."KODE_JABATAN"
				left join hris.jabatan as b1 on pegawai."JABATAN_INSTANSI_REAL_ID" = b1."KODE_JABATAN"
				left join hris.vw_unor_satker as c on pegawai."UNOR_ID" = c."ID_UNOR"
				left join hris.jenis_jabatan as e on e."ID" = pegawai."JENIS_JABATAN_ID"::varchar 
				left join hris."pns_aktif" "pa" ON "pegawai"."ID"="pa"."ID" 
				left join hris.pendidikan as pendidikan on pendidikan."ID" = pegawai."PENDIDIKAN_ID"
			  	left join hris.tkpendidikan as tkpendidikan on pendidikan."TINGKAT_PENDIDIKAN_ID" = tkpendidikan."ID" 
				where c."ID_SATKER"=? AND
				upper(concat(pegawai."NAMA","NIP_BARU")) LIKE ?
				and "pa"."ID" is not null 
				AND "pegawai"."KEDUDUKAN_HUKUM_ID" <> \'99\' 
				and "pegawai"."KEDUDUKAN_HUKUM_ID" <> \'66\' 
				and "pegawai"."KEDUDUKAN_HUKUM_ID" <> \'52\' 
				and "pegawai"."KEDUDUKAN_HUKUM_ID" <> \'20\' 
				and "pegawai"."KEDUDUKAN_HUKUM_ID" <> \'04\' 
				AND ("status_pegawai" != 3 or "status_pegawai" is null )';
		$nama_with_nip = "%" . strtoupper($nama_with_nip) . "%";
		$query =  $this->db->query($sql, array($satker_id, $nama_with_nip));
		$row = $query->first_row();
		return $row->jml;
	}

	public function find_all_asesmen_by_nip($nama_with_nip, $start, $limit)
	{
		$sql = 'SELECT pegawai."NAMA","NIP_BARU",b."NAMA_JABATAN",case when  b."JENIS_JABATAN"=\'2\' then b1."NAMA_JABATAN" else \'\' end as "NAMA_JABATAN_SK_FUNGSIONAL", b."KELAS",
				UPPER(b."KATEGORI_JABATAN") as "JENIS_JABATAN",
				b."KELAS",c."NAMA_UNOR_ESELON_1",c."NAMA_SATKER","EMAIL","EMAIL_DIKBUD","NOMOR_HP","NOMOR_DARURAT","PHOTO"
				,"TAHUN" as "TAHUN_ASESMEN","NILAI" 
								,"FILE_UPLOAD_FB_POTENSI"
								,"FILE_UPLOAD_LENGKAP_PT"
								,"FILE_UPLOAD_FB_PT","ID_SATKER"
				FROM hris.pegawai as pegawai 
				left join hris.jabatan as b on pegawai."JABATAN_INSTANSI_ID" = b."KODE_JABATAN"
				left join hris.jabatan as b1 on pegawai."JABATAN_INSTANSI_REAL_ID" = b1."KODE_JABATAN"
				left join hris.vw_unor_satker as c on pegawai."UNOR_ID" = c."ID_UNOR"
				left join hris.jenis_jabatan as e on e."ID"::varchar = b."JENIS_JABATAN" 
				left join hris."pns_aktif" "pa" ON "pegawai"."ID"="pa"."ID"
				left join 
						(
							select * from
							(
								SELECT "row_number"() OVER(partition by "PNS_NIP" order by "TAHUN" desc) as nomor,trim("PNS_NIP") as 
								"PNS_NIP","TAHUN","NILAI" 
								,"FILE_UPLOAD_FB_POTENSI"
								,"FILE_UPLOAD_LENGKAP_PT"
								,"FILE_UPLOAD_FB_PT"
								FROM hris.mv_riwayat_asesmen 
							) as asesmen where asesmen.nomor=\'1\'
						) as riwayat_asesmen ON pegawai."NIP_BARU" = riwayat_asesmen."PNS_NIP"
				where upper(concat(pegawai."NAMA","NIP_BARU")) LIKE ?
				and "pa"."ID" is not null 
				AND "pegawai"."KEDUDUKAN_HUKUM_ID" <> \'99\' 
				and "pegawai"."KEDUDUKAN_HUKUM_ID" <> \'66\' 
				and "pegawai"."KEDUDUKAN_HUKUM_ID" <> \'52\' 
				and "pegawai"."KEDUDUKAN_HUKUM_ID" <> \'20\' 
				and "pegawai"."KEDUDUKAN_HUKUM_ID" <> \'04\' 
				AND ("status_pegawai" != 3 or "status_pegawai" is null )
				ORDER BY "NAMA" ASC LIMIT ? OFFSET ?';
		$nama_with_nip = "%" . strtoupper($nama_with_nip) . "%";
		$query = $this->db->query($sql, array($nama_with_nip, $limit, $start));
		$data = $query->result();
		//return parent::find_all();
		//var_dump($sql);
		return $data;
	}

	public function count_list_asesmen_by_nip($nama_with_nip)
	{
		$sql = 'SELECT COUNT(*) as jml
				FROM hris.pegawai as pegawai 
				left join hris.jabatan as b on pegawai."JABATAN_INSTANSI_ID" = b."KODE_JABATAN"
				left join hris.jabatan as b1 on pegawai."JABATAN_INSTANSI_REAL_ID" = b1."KODE_JABATAN"
				left join hris.vw_unor_satker as c on pegawai."UNOR_ID" = c."ID_UNOR"
				left join hris.jenis_jabatan as e on e."ID"::varchar = b."JENIS_JABATAN" 
				left join hris."pns_aktif" "pa" ON "pegawai"."ID"="pa"."ID"
				left join 
						(
							select * from
							(
								SELECT "row_number"() OVER(partition by "PNS_NIP" order by "TAHUN" desc) as nomor,trim("PNS_NIP") as 
								"PNS_NIP","TAHUN","NILAI" 
								,"FILE_UPLOAD_FB_POTENSI"
								,"FILE_UPLOAD_LENGKAP_PT"
								,"FILE_UPLOAD_FB_PT"
								FROM hris.rwt_assesmen 
							) as asesmen where asesmen.nomor=\'1\'
						) as riwayat_asesmen ON pegawai."NIP_BARU" = riwayat_asesmen."PNS_NIP"
				where upper(concat(pegawai."NAMA","NIP_BARU")) LIKE ?
				and "pa"."ID" is not null 
				AND "pegawai"."KEDUDUKAN_HUKUM_ID" <> \'99\' 
				and "pegawai"."KEDUDUKAN_HUKUM_ID" <> \'66\' 
				and "pegawai"."KEDUDUKAN_HUKUM_ID" <> \'52\' 
				and "pegawai"."KEDUDUKAN_HUKUM_ID" <> \'20\' 
				and "pegawai"."KEDUDUKAN_HUKUM_ID" <> \'04\' 
				AND ("status_pegawai" != 3 or "status_pegawai" is null )';
		$nama_with_nip = "%" . strtoupper($nama_with_nip) . "%";
		$query =  $this->db->query($sql, array($nama_with_nip));
		$row = $query->first_row();
		return $row->jml;
	}
}
