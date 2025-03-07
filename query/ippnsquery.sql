 SELECT pns_aktip."NIP_BARU", pns_aktip."NAMA", pns_aktip."UNOR_ID", 
    pns_aktip."JENIS_JABATAN_ID", 
    COALESCE(fungsional_sum.tot_fungsional, 0::bigint) AS tot_fungsional, 
    COALESCE(kursus_sum.done_kursus, false) AS done_kursus, 
    COALESCE(struktural_sum.done_struktural, false) AS done_struktural, 
    false AS diklat_fungsional, vw."ID" AS unit_id, vw."ESELON_1", 
    vw."ESELON_2", vw."ESELON_3", vw."ESELON_4", pns_aktip."TK_PENDIDIKAN", 
    jenis_hukuman."TINGKAT_HUKUMAN", rwt_prestasi_kerja."NILAI_SKP", 
    rwt_prestasi_kerja."TAHUN", golongan."NAMA_PANGKAT", 
    pns_aktip."JENIS_KELAMIN", golongan."NAMA2" AS golongan, vw."NAMA_UNOR", 
    tkpendidikan."ABBREVIATION",
	pns_aktip."JABATAN_NAMA"
   FROM hris.pegawai pns_aktip
   JOIN hris.vw_unit_list vw ON pns_aktip."UNOR_ID"::text = vw."ID"::text
   LEFT JOIN ( SELECT rwt_diklat_fungsional."NIP_BARU", 
       sum(rwt_diklat_fungsional."JUMLAH_JAM"::integer) AS tot_fungsional
      FROM hris.rwt_diklat_fungsional
     WHERE rwt_diklat_fungsional."JUMLAH_JAM"::text <> ''::text
     GROUP BY rwt_diklat_fungsional."NIP_BARU") fungsional_sum ON pns_aktip."NIP_BARU"::text = fungsional_sum."NIP_BARU"::text
   LEFT JOIN ( SELECT DISTINCT rwt_kursus."PNS_NIP", true AS done_kursus
   FROM hris.rwt_kursus) kursus_sum ON pns_aktip."NIP_BARU"::bpchar = kursus_sum."PNS_NIP"
   LEFT JOIN ( SELECT DISTINCT rwt_diklat_struktural."PNS_NIP", 
    true AS done_struktural
   FROM hris.rwt_diklat_struktural) struktural_sum ON pns_aktip."NIP_BARU"::text = struktural_sum."PNS_NIP"::text
   LEFT JOIN hris.rwt_hukdis ON pns_aktip."NIP_BARU"::bpchar = rwt_hukdis."PNS_NIP"
   LEFT JOIN hris.jenis_hukuman ON rwt_hukdis."ID_JENIS_HUKUMAN" = jenis_hukuman."ID"
   LEFT JOIN hris.rwt_prestasi_kerja ON pns_aktip."NIP_BARU"::text = rwt_prestasi_kerja."PNS_NIP"::text
   LEFT JOIN hris.golongan ON pns_aktip."GOL_ID" = golongan."ID"
   LEFT JOIN hris.tkpendidikan ON pns_aktip."TK_PENDIDIKAN" = tkpendidikan."ID"::bpchar
  WHERE rwt_prestasi_kerja."TAHUN" = 2017 OR rwt_prestasi_kerja."TAHUN" IS NULL
  ORDER BY pns_aktip."NAMA";