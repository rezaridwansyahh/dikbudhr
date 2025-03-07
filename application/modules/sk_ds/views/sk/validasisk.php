
<?php
$this->load->library('convert');
    $convert = new convert();
?>
<div class="row">
        
        
        <div class="col-md-9">     
             <!-- <embed src="<?php echo $url_sk; ?>" width="100%" height="1000" alt="pdf"> -->
              <embed src="data:application/pdf;base64,<?php echo $b64; ?>" width="100%" height="700" alt="pdf">
        </div>
        <!-- /.col -->
        <div class="col-md-3">

          <!-- Profile Image -->
          <div class="box box-warning">
            <div class="box-body box-profile">
            <center>
              <img src="<?php echo base_url(); ?><?php echo $foto_pegawai; ?>" class="img-responsive pic-bordered" id="photopegawai" alt="Photo" width="50%">
            </center>
                <h3 class="profile-username text-center">
                    <?php echo isset($pegawai->GELAR_DEPAN) ? $pegawai->GELAR_DEPAN : ''; ?>  <?php echo isset($pegawai->NAMA) ? $pegawai->NAMA : ''; ?> <?php echo isset($pegawai->GELAR_BELAKANG) ? $pegawai->GELAR_BELAKANG : $data[0]->nama_pemilik_sk; ?>
                </h3>
              <p class="text-muted text-center">
                    <?php if($pegawai->JENIS_JABATAN_ID == "1") {  ?>
                       <b><?php if($pegawai->JENIS_JABATAN_ID == "1") { echo isset($NAMA_JABATAN_REAL) ? $NAMA_JABATAN_REAL  : ""; } ?></b>
                       <b>TMT <?php if($pegawai->JENIS_JABATAN_ID == "1") { echo isset($pegawai->TMT_JABATAN) ? $convert->fmtDate($pegawai->TMT_JABATAN,"dd month yyyy")  : ""; } ?></b>           
                    <?php } ?>
                    <?php if($pegawai->JENIS_JABATAN_ID == "2") {  ?>
                        <b><?php if($pegawai->JENIS_JABATAN_ID == "2") { echo isset($NAMA_JABATAN_REAL) ? $NAMA_JABATAN_REAL  : ""; } ?></b>
                        <b>TMT <?php if($pegawai->JENIS_JABATAN_ID == "2") { echo isset($pegawai->TMT_JABATAN) ? $convert->fmtDate($pegawai->TMT_JABATAN,"dd month yyyy")  : ""; } ?></b>
                    <?php } ?>
                    <?php if($pegawai->JENIS_JABATAN_ID == "4") { ?>
                        <b><?php if($pegawai->JENIS_JABATAN_ID == "4") { echo isset($NAMA_JABATAN_REAL) ? $NAMA_JABATAN_REAL  : ""; }?></b>
                        <b>TMT <?php if($pegawai->JENIS_JABATAN_ID == "4") { echo isset($pegawai->TMT_JABATAN) ? $convert->fmtDate($pegawai->TMT_JABATAN,"dd month yyyy")  : ""; } ?></b>    
                    <?php } ?>
              </p>

                 
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

          <!-- About Me Box -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Keterangan SK</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <strong><i class="fa fa-user margin-r-5"></i> Penandatangan </strong>
              <p class="text-muted">
                  <?php echo isset($pejabat->GELAR_DEPAN) ? $pejabat->GELAR_DEPAN : "-"; ?>
                  <?php echo isset($pejabat->NAMA) ? $pejabat->NAMA : "-"; ?>
                  <?php echo isset($pejabat->GELAR_BELAKANG) ? $pejabat->GELAR_BELAKANG : "-"; ?>
              </p>
              <hr>
              <strong><i class="fa fa-user margin-r-5"></i> Korektor Ke </strong>
              <p class="text-muted">
                  <?php echo $data[0]->korektor_ke; ?>
                  
              </p>
               <hr>
               <strong><i class="fa fa-user margin-r-5"></i> Status Koreksi </strong>
              <p class="text-muted">
                  <?php echo $data[0]->status_koreksi == "1" ? "<span class='label label-success'>Sudah Koreksi</span>" : ""; ?>
                  <?php echo $data[0]->status_koreksi == "2" ? "<span class='label label-warning'>Sedang Koreksi</span>" : ""; ?>
                  <?php echo ($data[0]->status_koreksi == "" or $data[0]->status_koreksi == "0") ? "<span class='label label-danger'>Belum Koreksi</span>" : ""; ?>
                  
              </p>
               <hr>
              
              <a class="btn btn-block btn-social btn-bitbucket" target="_blank" href="<?php echo $data->lokasi_file; ?>">
                <i class="fa fa-download"></i> Download SK
              </a>
              <?php if($data[0]->status_koreksi != "1"){ ?>
                <br>
                <?php echo form_open($this->uri->uri_string(), 'id="frm"'); ?>
                      <input type="hidden" name="id_file" value="<?php echo $data[0]->id_table; ?>">
                      <textarea name="catatan" id="catatan" class="form-control" width="100%" placeholder="Masukan Catatan Koreksi"><?php echo $data[0]->catatan_koreksi; ?></textarea>
                      <h5><b>Catatan Sebelumnya</b></h5>
                      <?php
                      if(isset($catatans) && is_array($catatans) && count($catatans)):
                        $no = 1;
                        foreach ($catatans as $record_catatan) {
                          echo $no.". ".$record_catatan->catatan_tindakan."<br>";
                          $no++;
                        }
                      else:
                        echo "-";
                      endif;
                      ?>
                      <br>
                      
                    <a class="btn btn-block btn-social btn-dropbox" id="btn_koreksi_ok">
                      <i class="fa fa-edit"></i> Teruskan
                    </a>
                    <a class="btn btn-block btn-social btn-google" id="btn_koreksi_batal">
                      <i class="fa fa-arrow-left"></i> Koreksi/Kembalikan Ke Pemroses
                    </a>
              </form>
            <?php }else{ ?>
                <button class="btn btn-block btn-social btn-google" id="btn_notsign">
                  <i class="fa fa-key"></i> SK Sudak dikoreksi
                </button>
            <?php } ?>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
</div>
<script>
  
  function submitdata(status){
    
    var json_url = "<?php echo base_url() ?>admin/sk/sk_ds/prosesvalidasi/"+status;
     $.ajax({    
      type: "POST",
      url: json_url,
      data: $("#frm").serialize(),
            dataType: "json",
            success: function(data){ 
                if(data.success){
                    swal("Pemberitahuan!", data.msg, "success");
                    window.location.href = "<?php echo base_url('admin/sk/sk_ds/validasi'); ?>";
                }
                else {
                  swal("Pemberitahuan!", data.msg, "error");    
                }
      }});
    return false; 
  }
$('body').on('click','#btn_koreksi_ok',function () { 
  swal({
    title: "Anda Yakin?",
    text: "Lanjutkan Proses ke Korektor selanjutnya/Ke Penandatangan!",
    type: "warning",
    showCancelButton: true,
    confirmButtonClass: 'btn-danger',
    confirmButtonText: 'Ya!',
    cancelButtonText: "Tidak, Batalkan!",
    closeOnConfirm: false,
    closeOnCancel: false
  },
  function (isConfirm) {
    if (isConfirm) {
      submitdata(1);
    } else {
      swal("Batal", "", "error");
    }
  });
});
$('body').on('click','#btn_koreksi_batal',function () { 
  swal({
    title: "Anda Yakin?",
    text: "Koreksi dokumen!",
    type: "warning",
    showCancelButton: true,
    confirmButtonClass: 'btn-danger',
    confirmButtonText: 'Ya!',
    cancelButtonText: "Tidak, Batalkan!",
    closeOnConfirm: false,
    closeOnCancel: false
  },
  function (isConfirm) {
    if (isConfirm) {
      var catatan = $("#catatan").val();
      if(catatan == ""){
        swal("Peringatan", "Silahkan masukan catatan koreksi", "error");
        $("#catatan").focus();
      }else{
        submitdata(3);
      }
    } else {
      swal("Batal", "", "error");
    }
  });
});
</script>