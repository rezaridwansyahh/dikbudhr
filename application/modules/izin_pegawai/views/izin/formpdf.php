<style>
hr {
  margin: 5px 0;
  border-bottom: 1px solid #fefefe;
}

.table td{
    height : 40px;
    padding : 2px;
    margin-bottom : 5px;
}
.tablesmal td{
    height : 20px;
    padding : 1px;
}

 
    body {
         font-family: 'Arial';
         font-size: 12px;
         font-style: normal;
         font-variant: normal;
    }
    .break { page-break-before: always; }
    hr {
      margin: 5px 0;
      border-bottom: 1px solid #fefefe;
    }
    .headjudul {
        font-size : 34pt;
    }
    .headjudul1 {
        font-size : 17pt;
    }
    .headjudul2 {
        font-size : 9pt;
    }
    .headjudul3 {
        font-size : 22pt;
    }
    table {
        border-collapse: collapse;
        
    }
    table .tabel{
        font-size: 20pt;
    }
    table .tabel{
        font-size: 20pt;
    }
    td{
        height : 15px;
      padding : 2px;
      margin-bottom : 1px;
    }
    .tablemiddle td{
        padding : 3px;
    }
    .checkboxOne {
        width: 40px;
        height: 40px;
        background-color: #e9ecee;
        color: #99a1a7;
        border: 1px solid #adb8c0;
    }
    @font-face {
        font-family: 'Arial';
    }
    /* use this class to attach this font to any element i.e. <p class="fontsforweb_fontid_507">Text with this font applied</p> */
    .btnprint{
        display: none;
    }
    
 
</style>
<?php
  $id_jenis_izin = isset($jenis_izin->ID) ? $jenis_izin->ID : "";
?>
<title>Form Izin Pdf</title>
<table width="100%">
  <tr>
      <td valign="top" width="30%">
          
      </td>
      <td valign="top" align="center">
          <center>FORMULIR PERMINTAAN DAN PEMBERIAN CUTI</center>
      </td>
      <td valign="top" width="30%">
          
      </td>
      
  </tr>
</table>
<table width="100%" border="1">
  <tr>
    <td colspan="4">
        I. DATA PEGAWAI
    </td>
  </tr>
  <tr>
    <td width="10%">
        Nama
    </td>
    <td width="60%">
      <?php echo $pegawai->GELAR_DEPAN; ?><?php echo $pegawai->NAMA; ?><?php echo $pegawai->GELAR_BELAKANG; ?>
    </td>
    <td width="10%">
        NIP
    </td>
    <td>
      <?php echo $pegawai->NIP_BARU; ?>
    </td>
  </tr>
  <tr>
    <td>
        Jabatan
    </td>
    <td>
      <?php echo $izin_pegawai->JABATAN != "" ? $izin_pegawai->JABATAN : $pegawai->JABATAN_NAMA; ?> 
    </td>
    <td>
        Masa Kerja
    </td>
    <td>
      <?php echo $izin_pegawai->MASA_KERJA_TAHUN != "" ? $izin_pegawai->MASA_KERJA_TAHUN : $pegawai->masa_kerja_th; ?>Tahun
      <?php echo $izin_pegawai->MASA_KERJA_BULAN != "" ? $izin_pegawai->MASA_KERJA_BULAN : $pegawai->masa_kerja_bl; ?>Bulan 
    </td>
  </tr>
  <tr>
    <td>
        Unit Kerja
    </td>
    <td colspan="3">
      <?php echo $izin_pegawai->NAMA_UNIT_KERJA != "" ? $izin_pegawai->NAMA_UNIT_KERJA : $unor_induk->NAMA_UNOR; ?>
    </td>
  </tr>
</table>
<br>
<table width="100%" border="1">
  <tr>
    <td colspan="4">
        II. JENIS CUTI YANG DIAMBIL**
    </td>
  </tr>
  <tr>
    <td>
        1. Cuti Tahunan
    </td>
    <td width="40px">
        <?php echo $id_jenis_izin== "1" ? "Ya" : ""; ?>
    </td>
    <td>
        2. Cuti Besar
    </td>
    <td width="40px">
      <?php echo $id_jenis_izin== "2" ? "Ya" : ""; ?>
    </td>
  </tr>
  <tr>
    <td>
        3. Cuti Sakit
    </td>
    <td>
      <?php echo $id_jenis_izin== "3" ? "Ya" : ""; ?>
    </td>
    <td>
        4. Cuti Melahirkan
    </td>
    <td>
      <?php echo $id_jenis_izin== "4" ? "Ya" : ""; ?>
    </td>
  </tr>
  <tr>
    <td>
        5. Cuti Karena Alasan Penting
    </td>
    <td>
      <?php echo $id_jenis_izin== "5" ? "Ya" : ""; ?>
    </td>
    <td>
        6. Cuti di Luar Tanggungan Negara
    </td>
    <td>
      <?php echo $id_jenis_izin== "6" ? "Ya" : ""; ?>
    </td>
  </tr>
</table>
<br>
<table width="100%" border="1">
  <tr>
    <td>
        III. ALASAN CUTI
    </td>
  </tr>
  <tr>
    <td>
       <?php echo $izin_pegawai->KETERANGAN != "" ? "<br>".$izin_pegawai->KETERANGAN."<br><br>" : "<br><br>"; ?>
    </td>
  </tr>
</table>
<br>
<table width="100%" border="1">
  <tr>
    <td colspan="6">
        IV. LAMANYA CUTI
    </td>
  </tr>
  <tr>
    <td>
       Selama
    </td>
    <td>
        <?php echo $izin_pegawai->JUMLAH; ?>
         <?php echo $izin_pegawai->SATUAN; ?>
    </td>
    <td>
          Mulai Tanggal
    </td>
    <td>
        <?php
          $dateValue1 = strtotime($izin_pegawai->DARI_TANGGAL);
          $dari_tanggal = date('d-m-Y',$dateValue1);
          echo $dari_tanggal; 
        ?>
    </td>
    <td>
         s/d
    </td>
    <td>
        <?php
          $dateValue2 = strtotime($izin_pegawai->SAMPAI_TANGGAL);
          $sampai_tanggal = date('d-m-Y',$dateValue2);
          echo $sampai_tanggal; 
        ?>
    </td>
  </tr>
</table>
<br>
<table width="100%" border="1">
  <tr>
    <td colspan="5">
        V. CATATAN CUTI
    </td>
  </tr>
  <tr>
    <td colspan="3">
       1. CUTI TAHUNAN
    </td>
    <td width="60%">
        2. CUTI BESAR
    </td>
    <td width="40px">
         
    </td>
  </tr>
  <tr>
    <td width="10%">
      Tahun
    </td>
    <td width="10%">
        Sisa
    </td>
    <td>
       Keterangan
    </td>
    <td>
         3. CUTI SAKIT
    </td>
    <td>
    </td>
  </tr>
  <tr>
    <td>
      N-2
    </td>
    <td>
        <?php echo $id_jenis_izin== "1" ? $izin_pegawai->SISA_CUTI_TAHUN_N2 : ""; ?>
    </td>
    <td>
       
    </td>
    <td>
         4. CUTI MELAHIRKAN
    </td>
    <td align="center">
        <?php echo $id_jenis_izin== "4" ? "Anak Ke ".$izin_pegawai->ANAK_KE : ""; ?>
    </td>
  </tr>
  <tr>
    <td>
      N-1
    </td>
    <td>
        <?php echo $id_jenis_izin == "1" ? $izin_pegawai->SISA_CUTI_TAHUN_N1."" : ""; ?>
    </td>
    <td>
       
    </td>
    <td>
         5. CUTI KARENA ALASAN PENTING
    </td>
    <td>
    </td>
  </tr>
  <tr>
    <td>
      N
    </td>
    <td>
        <?php echo $id_jenis_izin== "1" ? $izin_pegawai->SISA_CUTI_TAHUN_N : ""; ?>
    </td>
    <td>
       
    </td>
    <td>
         6. CUTI DILUAR TANGGUNGAN NEGARA
    </td>
    <td>
    </td>
  </tr>
</table>
<br>
<table width="100%" border="1">
  <tr>
    <td colspan="3">
        VI. ALAMAT SELAMA MENJALANKAN CUTI
    </td>
  </tr>
  <tr>
    <td width="60%">
       
    </td>
    <td>
        TELP
    </td>
    <td>
          <?php echo $izin_pegawai->TLP_SELAMA_CUTI; ?>
    </td>
  </tr>
  
  <tr>
    <td>
       <?php echo $izin_pegawai->ALAMAT_SELAMA_CUTI; ?>
    </td>
    <td colspan="2" align="center">
      <center>
        Hormat saya,
      
      <br>
      <br>
      <br>
      (<?php echo $izin_pegawai->NAMA; ?>)
      <br>
      NIP <?php echo $izin_pegawai->NIP_PNS; ?>
      <br>
      </center>
    </td>
    
  </tr>

</table>
<br>
<table width="100%" border="1">
  <tr>
    <td colspan="4">
        VII. PERTIMBANGAN ATASAN LANGSUNG**
    </td>
  </tr>
  <tr>
    <td>
       DISETUJUI
    </td>
    <td>
        PERUBAHAN****
    </td>
    <td>
        DITANGGUHKAN**** 
    </td>
    <td>
        TIDAK DISETUJUI****
    </td>
  </tr>
  
  <tr>
    <td align="center" valign="middle">
      <br>
      <?php
        // disetujui atasan langsung
        $aatasan_langsung = isset($aatasan[2]) ? $aatasan[2] : "";
        $persetujuan_atasan_langsung = isset($verifikasidata[$aatasan_langsung->NIP_ATASAN]) ? $verifikasidata[$aatasan_langsung->NIP_ATASAN] : "";
        $dateValue = strtotime($persetujuan_atasan_langsung->TANGGAL_VERIFIKASI);
        $TGL_ATASAN = date('d-m-Y',$dateValue);
        //PRINT_R($persetujuan_atasan_langsung);
      ?>
      <?php echo $persetujuan_atasan_langsung->STATUS_VERIFIKASI == "3" ? $TGL_ATASAN : ""; ?>
      <?php echo $persetujuan_atasan_langsung->STATUS_VERIFIKASI == "3" ? "<br>".$persetujuan_atasan_langsung->NAMA : ""; ?>
      <?php echo $persetujuan_atasan_langsung->STATUS_VERIFIKASI == "3" ? "<br>".$persetujuan_atasan_langsung->NIP_ATASAN : ""; ?>
      
      <br>
    </td>
    <td align="center" valign="middle">
      <?php echo $persetujuan_atasan_langsung->STATUS_VERIFIKASI == "4" ? $TGL_ATASAN : ""; ?>
      <?php echo $persetujuan_atasan_langsung->STATUS_VERIFIKASI == "4" ? "<br>Alasan: ".$persetujuan_atasan_langsung->ALASAN_DITOLAK : ""; ?>
      
      <?php echo $persetujuan_atasan_langsung->STATUS_VERIFIKASI == "4" ? "<br>".$persetujuan_atasan_langsung->NAMA : ""; ?>
      <?php echo $persetujuan_atasan_langsung->STATUS_VERIFIKASI == "4" ? "<br>".$persetujuan_atasan_langsung->NIP_ATASAN : ""; ?>
    </td>
    <td align="center" valign="middle">
      <?php echo $persetujuan_atasan_langsung->STATUS_VERIFIKASI == "5" ? $TGL_ATASAN : ""; ?>
      <?php echo $persetujuan_atasan_langsung->STATUS_VERIFIKASI == "5" ? "<br>Alasan: ".$persetujuan_atasan_langsung->ALASAN_DITOLAK : ""; ?>
      <?php echo $persetujuan_atasan_langsung->STATUS_VERIFIKASI == "5" ? "<br>".$persetujuan_atasan_langsung->NAMA : ""; ?>
      <?php echo $persetujuan_atasan_langsung->STATUS_VERIFIKASI == "5" ? "<br>".$persetujuan_atasan_langsung->NIP_ATASAN : ""; ?>
    </td>

    <td align="center" valign="middle">
      <?php echo $persetujuan_atasan_langsung->STATUS_VERIFIKASI == "6" ? $TGL_ATASAN : ""; ?>
      <?php echo $persetujuan_atasan_langsung->STATUS_VERIFIKASI == "6" ? "<br>Alasan: ".$persetujuan_atasan_langsung->ALASAN_DITOLAK : ""; ?>
      
      <?php echo $persetujuan_atasan_langsung->STATUS_VERIFIKASI == "6" ? "<br>".$persetujuan_atasan_langsung->NAMA : ""; ?>
      <?php echo $persetujuan_atasan_langsung->STATUS_VERIFIKASI == "6" ? "<br>".$persetujuan_atasan_langsung->NIP_ATASAN : ""; ?>
    </td>
  </tr>
</table>
<br>
<table width="100%" border="1">
  <tr>
    <td colspan="4">
        VIII. KEPUTUSAN PEJABAT YANG BERWENANG MEMBERIKAN CUTI**
    </td>
  </tr>
  <tr>
    <td>
       DISETUJUI
    </td>
    <td>
        PERUBAHAN****
    </td>
    <td>
          DITANGGUHKAN**** 
    </td>
    <td>
         TIDAK DISETUJUI****
    </td>
  </tr>
  
  <tr>
    <td align="center" valign="middle">
      <br>
      <?php
        // disetujui atasan langsung
        $apybmc = isset($aatasan[3]) ? $aatasan[3] : "";
        $persetujuan_pybmc = isset($verifikasidata[$apybmc->NIP_ATASAN]) ? $verifikasidata[$apybmc->NIP_ATASAN] : "";
        $dateValue = strtotime($persetujuan_pybmc->TANGGAL_VERIFIKASI);
        $TGL_ATASAN = date('d-m-Y',$dateValue);
        //PRINT_R($persetujuan_pybmc);
      ?>
      <?php echo $persetujuan_pybmc->STATUS_VERIFIKASI == "3" ? $TGL_ATASAN : ""; ?>
      <?php echo $persetujuan_pybmc->STATUS_VERIFIKASI == "3" ? "<br>".$persetujuan_pybmc->NAMA : ""; ?>
      <?php echo $persetujuan_pybmc->STATUS_VERIFIKASI == "3" ? "<br>".$persetujuan_pybmc->NIP_ATASAN : ""; ?>
      
      <br>
    </td>
    <td align="center" valign="middle">
      <?php echo $persetujuan_pybmc->STATUS_VERIFIKASI == "4" ? $TGL_ATASAN : ""; ?>
      <?php echo $persetujuan_pybmc->STATUS_VERIFIKASI == "4" ? "<br>Alasan: ".$persetujuan_pybmc->ALASAN_DITOLAK : ""; ?>
      
      <?php echo $persetujuan_pybmc->STATUS_VERIFIKASI == "4" ? "<br>".$persetujuan_pybmc->NAMA : ""; ?>
      <?php echo $persetujuan_pybmc->STATUS_VERIFIKASI == "4" ? "<br>".$persetujuan_pybmc->NIP_ATASAN : ""; ?>
    </td>
    <td align="center" valign="middle">
      <?php echo $persetujuan_pybmc->STATUS_VERIFIKASI == "5" ? $TGL_ATASAN : ""; ?>
      <?php echo $persetujuan_pybmc->STATUS_VERIFIKASI == "5" ? "<br>Alasan: ".$persetujuan_pybmc->ALASAN_DITOLAK : ""; ?>
      <?php echo $persetujuan_pybmc->STATUS_VERIFIKASI == "5" ? "<br>".$persetujuan_pybmc->NAMA : ""; ?>
      <?php echo $persetujuan_pybmc->STATUS_VERIFIKASI == "5" ? "<br>".$persetujuan_pybmc->NIP_ATASAN : ""; ?>
    </td>

    <td align="center" valign="middle">
      <?php echo $persetujuan_pybmc->STATUS_VERIFIKASI == "6" ? $TGL_ATASAN : ""; ?>
      <?php echo $persetujuan_pybmc->STATUS_VERIFIKASI == "6" ? "<br>Alasan: ".$persetujuan_pybmc->ALASAN_DITOLAK : ""; ?>
      
      <?php echo $persetujuan_pybmc->STATUS_VERIFIKASI == "6" ? "<br>".$persetujuan_pybmc->NAMA : ""; ?>
      <?php echo $persetujuan_pybmc->STATUS_VERIFIKASI == "6" ? "<br>".$persetujuan_pybmc->NIP_ATASAN : ""; ?>
    </td>
  </tr>
</table>
<br><br>
<table width="100%" border="0">
  <tr>
    <td >
        Catatan: 
        <br>* Coret yang tidak perlu 
        <br>** Pilih salah satu dengan memberi tanda centang 
        <br>*** diisi oleh pejabat yang menangani bidang kepegawaian sebelum 
        <br>**** diberi tanda centang dan alasannya,.
        <br>N : Cuti tahun berjalan
        <br>N- 1 = Sisa cuti 1 tahun sebelumnya
        <br>N-2 = Sisa cuti 2 tahun sebelumnya
    </td>
  </tr>
   
</table>