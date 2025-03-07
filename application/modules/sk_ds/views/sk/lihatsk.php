
<?php
$this->load->library('convert');
    $convert = new convert();
?>
<div class="row">
        <?php echo form_open($this->uri->uri_string(), 'id="frm"'); ?>
        <input type="hidden" name="id_file" value="<?php echo $data->id_file; ?>">
        
        <div class="col-md-9">     
            <!-- <embed src="<?php echo $url_sk; ?>" width="100%" height="700" alt="pdf"> -->
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
                    <?php echo isset($pegawai->GELAR_DEPAN) ? $pegawai->GELAR_DEPAN : ''; ?>  <?php echo isset($pegawai->NAMA) ? $pegawai->NAMA : ''; ?> <?php echo isset($pegawai->GELAR_BELAKANG) ? $pegawai->GELAR_BELAKANG : ''; ?>
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
              <p class="text-muted">
                  <?php echo $data->is_signed == "1" ? "<span class='label label-success'>Sudah Tandatangan</span>" : ""; ?>
                  <?php echo $data->is_signed == "0" ? "<span class='label label-danger'>Belum Tandatangan</span>" : ""; ?>
                  <?php echo $data->is_signed == "3" ? "<span class='label label-danger'>Dikoreksi</span>" : ""; ?>
              </p>

              <?php
              if($data->is_signed == "0" && $pejabat->NIP_BARU == $pegawai_login->NIP_BARU){ ?>
                  <a class="btn btn-block btn-social btn-dropbox" id="btn_sign">
                  <i class="fa fa-edit"></i> Tanda Tangani SK
                </a>
              <?php } ?>
              <hr>
              <strong><i class="fa fa-user margin-r-5"></i> Korektor </strong>
              <p class="text-muted">
                  <?php 
                    if(isset($korektor) && is_array($korektor) && count($korektor)):
                      $no = 1;
                    foreach ($korektor as $record) {
                        echo $record->korektor_ke.". ";
                        echo $record->NAMA;
                    ?>
                      <?php echo $record->is_corrected == "1" ? "<span class='label label-success'>Sudah Koreksi</span>" : ""; ?>
                      <?php echo $record->is_corrected == "2" ? "<span class='label label-warning'>Sedang Koreksi</span>" : ""; ?>
                      <?php echo $record->is_corrected == "" ? "<span class='label label-danger'>Belum Koreksi</span>" : ""; ?>
                    <?php
                        echo "<br>";
                        $no++;
                      }
                    endif;

                  ?>
                  
              </p>
              
              <hr>
               
              <a class="btn btn-block btn-social btn-facebook" target="_blank" href="<?php echo base_url() ?>admin/sk/sk_ds/downloadsk/<?php echo $data->id_file; ?>">
                <i class="fa fa-download"></i> Download draft SK
              </a>
              <?php if($data->is_signed == "1"){ ?>
              <a class="btn btn-block btn-social btn-linkedin" target="_blank" href="<?php echo base_url() ?>admin/sk/sk_ds/downloadsksigned/<?php echo $data->id_file; ?>">
                <i class="fa fa-download"></i> Download SK tandatangan
              </a>
              <?php } ?>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </form>
</div>

<script>
  
  function submitdata(pass){
    var valid_file = "<?php echo $data->id_file; ?>";
    var datas = "id_file="+valid_file+"&passphrase="+pass+"&username=<?php echo isset($pegawai_login->NIK) ? trim($pegawai_login->NIK) : ""; ?>&NIP=<?php echo isset($pegawai_login->NIP_BARU) ? trim($pegawai_login->NIP_BARU) : ""; ?>";//var datas = "id_file="+valid_file+"&passphrase="+pass+"&username=<?php echo isset($pegawai->NIK) ? trim($pegawai->NIK) : ""; ?>";
    var json_url = "<?php echo base_url() ?>admin/sk/sk_ds/tandatangansk";
     $.ajax({    
      type: "POST",
      url: json_url,
      data: datas,
            dataType: "json",
            success: function(data){ 
                if(data.success){
                    swal("Pemberitahuan!", data.msg, "success");
                    //window.location = "<?php echo base_url(); ?>admin/sk/sk_ds/";
                    location.reload();
                }
                else {
                  swal("Pemberitahuan!", data.msg, "error");    
                }
      }});
    return false; 
  }
   
$( "#btn_sign" ).click(function() {
        swal({
        title: "Tandatangi SK",
        //text: "Silahkan Masukan Pasphrase anda dengan NIK \"<?php echo isset($pegawai_login->NIK) ? trim($pegawai_login->NIK) : "-"; ?>\"",
        text: "Silahkan Masukan Pasphrase anda",

        inputType: "password",
        type: "input",
        showCancelButton: true,
        closeOnConfirm: false,
        animation: "slide-from-top",
        inputPlaceholder: "Pasphrase"
      },
      function(inputValue){
        if (inputValue === false) return false;

        if (inputValue === "") {
          swal.showInputError("Silahkan masukan Pasphrase!");
          return false
        }
        swal("Perhatian", "Tunggu sebentar, tandatangan digital sedang proses", "info");
        submitdata(inputValue);
      });

    });
 
</script>