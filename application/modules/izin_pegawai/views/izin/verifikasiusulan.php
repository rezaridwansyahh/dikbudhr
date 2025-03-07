<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/select2/select2.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/css/dropzone/dropzone.min.css">
<script src="<?php echo base_url(); ?>themes/admin/js/dropzone/dropzone.min.js"></script>
<?php 
    $this->load->library('convert');
    $convert = new convert();
    $id = isset($izin_pegawai->ID) ? $izin_pegawai->ID : '';
    // print_r($izin_pegawai);
?>
<div class="callout callout-success">
   <h4>Perhatian</h4>
   <p>Halaman untuk memverifikasi usulan izin "<?php echo $nama_izin; ?>" dari bawahan anda</p>
 </div>

<div class="row">
    <div class="col-md-9">     
        <div class='box box-warning' id="form-riwayat-assesmen-add">
            <div class="box-body">
                    <?php
                    $this->load->view('izin/_approval',array('level'=>$level,'NIP_ATASAN'=>$izin_pegawai->NIP_ATASAN,'NAMA_ATASAN'=>$izin_pegawai->NAMA_ATASAN,'NIP_PPK'=>$NIP_izin_pegawai->izin_pegawai->NIP_PYBMC,'NAMA_PPK'=>$izin_pegawai->NAMA_PYBMC,'NAMA_PEGAWAI'=>$pegawai->NAMA,"status_atasan"=>true));
                    ?>
                <hr>
                <div class="messages">
                </div>
            <?php echo form_open($this->uri->uri_string(), 'id="frm"'); ?>
                <fieldset>
                    <input id='ID' type='hidden' name='ID' value="<?php echo set_value('ID', isset($izin_pegawai->ID) ? $izin_pegawai->ID : ""); ?>" />
                    <input id='ID_VERIFIKASI' type='hidden' name='ID_VERIFIKASI' value="<?php echo set_value('ID', isset($ID_VERIFIKASI) ? $ID_VERIFIKASI : ""); ?>" />
                    <input id='NIP_PNS' type='hidden' name='NIP_PNS' value="<?php echo set_value('NIP_PNS', isset($izin_pegawai->NIP_PNS) ? trim($izin_pegawai->NIP_PNS) : ""); ?>" />
                    <input id='NIP_ATASAN' type='hidden' name='NIP_ATASAN' value="<?php echo set_value('NIP_ATASAN', isset($izin_pegawai->NIP_ATASAN) ? $izin_pegawai->NIP_ATASAN : ""); ?>" />
                    <input id='NIP_PPK' type='hidden' name='NIP_PPK' value="<?php echo set_value('NIP_PPK', isset($izin_pegawai->NIP_PPK) ? $izin_pegawai->NIP_PPK : ""); ?>" />
                    <input id='KODE_IZIN' type='hidden' name='KODE_IZIN' value="<?php echo set_value('KODE_IZIN', isset($izin_pegawai->KODE_IZIN) ? $izin_pegawai->KODE_IZIN : ""); ?>" />
                    <input id='KODE_JENIS_IZIN' type='hidden' name='KODE_JENIS_IZIN' value="<?php echo set_value('KODE_JENIS_IZIN', isset($KODE_JENIS_IZIN) ? trim($KODE_JENIS_IZIN) : ""); ?>" />

                    <input id='DARI_TANGGAL' type='hidden' name='DARI_TANGGAL' value="<?php echo set_value('DARI_TANGGAL', isset($izin_pegawai->DARI_TANGGAL) ? trim($izin_pegawai->DARI_TANGGAL) : ""); ?>" />
                    <input id='SAMPAI_TANGGAL' type='hidden' name='SAMPAI_TANGGAL' value="<?php echo set_value('SAMPAI_TANGGAL', isset($izin_pegawai->SAMPAI_TANGGAL) ? trim($izin_pegawai->SAMPAI_TANGGAL) : ""); ?>" />
                    <input id='JUMLAH' type='hidden' name='JUMLAH' value="<?php echo set_value('JUMLAH', isset($izin_pegawai->JUMLAH) ? trim($izin_pegawai->JUMLAH) : ""); ?>" />
                    
                    <?php if($kode_izin == "1"){ ?>
                    <div class="control-group  col-sm-3">
                        <?php echo form_label("SISA TAHUN N", 'SISA_CUTI_TAHUN_N', array('class' => 'control-label')); ?>
                        <div class="input-group date">
                            <?php echo set_value('SISA_CUTI_TAHUN_N', isset($izin_pegawai->SISA_CUTI_TAHUN_N) ? $izin_pegawai->SISA_CUTI_TAHUN_N : $data_cuti->SISA_N); ?>
                            <span class="info">Hari</i></span>
                        </div>
                    </div>
                    <div class="control-group  col-sm-3">
                        <?php echo form_label("SISA TAHUN N-1", 'SISA_CUTI_TAHUN_N1', array('class' => 'control-label')); ?>
                        <div class="input-group date">
                            <?php echo set_value('SISA_CUTI_TAHUN_N1', isset($izin_pegawai->SISA_CUTI_TAHUN_N1) ? $izin_pegawai->SISA_CUTI_TAHUN_N1 : $data_cuti->SISA_N_1); ?>
                            <span class="info">Hari</i></span>
                        </div>
                    </div>
                    <div class="control-group  col-sm-3">
                        <?php echo form_label("SISA TAHUN N-2", 'SISA_CUTI_TAHUN_N2', array('class' => 'control-label')); ?>
                        <div class="input-group date">
                            <?php echo set_value('SISA_CUTI_TAHUN_N2', isset($izin_pegawai->SISA_CUTI_TAHUN_N2) ? $izin_pegawai->SISA_CUTI_TAHUN_N2 : $data_cuti->SISA_N_2); ?>
                            <span class="info">Hari</i></span>
                        </div>
                    </div>
                    <div class="control-group  col-sm-3">
                        <?php echo form_label("JUMLAH SISA CUTI", 'SISA', array('class' => 'control-label')); ?>
                        <div class="input-group date">
                            <?php echo set_value('SISA', isset($izin_pegawai->SISA) ? $izin_pegawai->SISA : $data_cuti->SISA); ?>
                            <span class="info">Hari</i></span>
                        </div>
                    </div>
                    <?php } ?>
                    <?php if($izin_pegawai->SAMPAI_TANGGAL != ""){ ?>
                    <div class="control-group col-sm-3">
                        <label for="inputNAMA" class="control-label">DARI TANGGAL</label>
                        <div class="input-group date">

                            <?php echo set_value('DARI_TANGGAL', isset($izin_pegawai->DARI_TANGGAL) ? $izin_pegawai->DARI_TANGGAL : ''); ?>
                            <span class='help-inline'><?php echo form_error('DARI_TANGGAL'); ?></span>
                        </div>
                    </div> 
                    <div class="control-group col-sm-3">
                        <label for="inputNAMA" class="control-label">SAMPAI TANGGAL</label>
                        <div class="input-group date">
                            <?php echo set_value('SAMPAI_TANGGAL', isset($izin_pegawai->SAMPAI_TANGGAL) ? $izin_pegawai->SAMPAI_TANGGAL : ''); ?>
                            <span class='help-inline'><?php echo form_error('SAMPAI_TANGGAL'); ?></span>
                        </div>
                    </div> 
                <?php }else{ ?>
                    <div class="control-group col-sm-3">
                        <label for="inputNAMA" class="control-label">TANGGAL</label>
                        <div class="input-group date">

                            <?php echo set_value('DARI_TANGGAL', isset($izin_pegawai->DARI_TANGGAL) ? $izin_pegawai->DARI_TANGGAL : ''); ?>
                            <span class='help-inline'><?php echo form_error('DARI_TANGGAL'); ?></span>
                        </div>
                    </div> 
                <?php } ?>
                <?php if($izin_pegawai->JUMLAH!= ""){ ?>
                    <div class="control-group  col-sm-6">
                        <?php echo form_label(lang('izin_pegawai_field_JUMLAH'), 'JUMLAH', array('class' => 'control-label')); ?>
                        <div class="input-group date">
                            <?php echo set_value('JUMLAH', isset($izin_pegawai->JUMLAH) ? $izin_pegawai->JUMLAH : ''); ?>
                            <?php echo set_value('SATUAN', isset($izin_pegawai->SATUAN) ? $izin_pegawai->SATUAN : 'Hari'); ?>
                        </div>
                    </div>
                <?php } ?>
                <?php if($izin_pegawai->SELAMA_JAM != ""){ ?>
                    <div class="control-group  col-sm-3">
                        <?php echo form_label("SELAMA", 'JUMLAH', array('class' => 'control-label')); ?>
                        <div class="input-group date">
                            <?php echo set_value('JUMLAH', isset($izin_pegawai->SELAMA_JAM) ? $izin_pegawai->SELAMA_JAM : ''); ?>
                            JAM
                        </div>
                    </div>
                <?php } ?>
                <?php if($izin_pegawai->SELAMA_MENIT != ""){ ?>
                    <div class="control-group  col-sm-3">
                        <?php echo form_label("-", 'JUMLAH', array('class' => 'control-label')); ?>
                        <div class="input-group date">
                            <?php echo set_value('JUMLAH', isset($izin_pegawai->SELAMA_MENIT) ? $izin_pegawai->SELAMA_MENIT : ''); ?>
                            MENIT
                        </div>
                    </div>
                <?php } ?>
                <?php if($izin_pegawai->KETERANGAN != ""){ ?>
                    <div class="control-group<?php echo form_error('KETERANGAN') ? ' error' : ''; ?>  col-sm-12">
                        <?php echo form_label("KETERANGAN", 'KETERANGAN', array('class' => 'control-label')); ?>
                        <div class='controls'>
                            <?php echo set_value('KETERANGAN', isset($izin_pegawai->KETERANGAN) ? $izin_pegawai->KETERANGAN : ''); ?>
                            <span class='help-inline'><?php echo form_error('KETERANGAN'); ?></span>
                        </div>
                    </div>
                <?php } ?>
                <?php if($izin_pegawai->ALAMAT_SELAMA_CUTI != ""){ ?>
                    <div class="control-group<?php echo form_error('ALAMAT_SELAMA_CUTI') ? ' error' : ''; ?>  col-sm-12">
                        <?php echo form_label(lang('izin_pegawai_field_ALAMAT_SELAMA_CUTI'), 'KETERANGAN', array('class' => 'control-label')); ?>
                        <div class='controls'>
                            <?php echo set_value('ALAMAT_SELAMA_CUTI', isset($izin_pegawai->ALAMAT_SELAMA_CUTI) ? $izin_pegawai->ALAMAT_SELAMA_CUTI : ''); ?>
                            <span class='help-inline'><?php echo form_error('ALAMAT_SELAMA_CUTI'); ?></span>
                        </div>
                    </div>
                <?php } ?> 
                <?php if($izin_pegawai->TLP_SELAMA_CUTI != ""){ ?>
                    <div class="control-group<?php echo form_error('TLP_SELAMA_CUTI') ? ' error' : ''; ?>  col-sm-12">
                        <?php echo form_label(lang('izin_pegawai_field_TLP_SELAMA_CUTI'), 'TLP_SELAMA_CUTI', array('class' => 'control-label')); ?>
                        <div class="input-group">
                            <?php echo set_value('TLP_SELAMA_CUTI', isset($izin_pegawai->TLP_SELAMA_CUTI) ? $izin_pegawai->TLP_SELAMA_CUTI : ''); ?>
                          </div>

                    </div>
                <?php } ?> 
                    <div class="control-group<?php echo form_error('STATUS_ATASAN') ? ' error' : ''; ?>  col-sm-12">
                        <?php echo form_label("STATUS", 'STATUS_ATASAN', array('class' => 'control-label')); ?>
                        <div class="controls">
                            <select name="STATUS_ATASAN" id="STATUS_ATASAN" class="form-control">
                                <option value="">Silahkan Pilih</option>
                                <!--<option value="1" <?php echo $izin_pegawai->STATUS_ATASAN == "1" ? "selected" : ""; ?> >PENGAJUAN</option> -->
                                <option value="3" <?php echo $izin_pegawai->STATUS_VERIFIKASI == "3" ? "selected" : ""; ?> >DISETUJUI</option>
                                <option value="4" <?php echo $izin_pegawai->STATUS_VERIFIKASI == "4" ? "selected" : ""; ?> >PERUBAHAN</option>
                                <option value="5" <?php echo $izin_pegawai->STATUS_VERIFIKASI == "5" ? "selected" : ""; ?>>DITANGGUHKAN</option>
                                <option value="6" <?php echo $izin_pegawai->STATUS_VERIFIKASI == "6" ? "selected" : ""; ?>>TIDAK DISETUJUI</option>
                            </select>
                          </div>

                    </div>
                    <div class="control-group<?php echo form_error('CATATAN_ATASAN') ? ' error' : ''; ?>  col-sm-12 divcatatan_atasan">
                        <?php echo form_label("CATATAN", 'CATATAN_ATASAN', array('class' => 'control-label')); ?>
                        <div class="controls">
                            <textarea name="CATATAN_ATASAN" id="CATATAN_ATASAN" class="form-control"><?php echo set_value('CATATAN_ATASAN', isset($izin_pegawai->CATATAN_ATASAN) ? $izin_pegawai->CATATAN_ATASAN : ''); ?></textarea>
                          </div>

                    </div>
                </fieldset>
                </div>
                <div class="box-footer">
                    <a href="javascript:;" id="btnsave" class="btn green btn-primary button-submit"> 
                        <i class="fa fa-save"></i> 
                        Simpan dan Kirim
                    </a>
                    <?php echo lang('bf_or'); ?>
                    <a href="javascript:;" id="btncancel" class="btn green btn-warning button-submit"> 
                        <i class="fa fa-back"></i> 
                        Cancel
                    </a>
                    <?php echo lang('bf_or'); ?>
                    <a href="javascript:;" id="btn_sign" class="btn green btn-danger button-submit"> 
                        <i class="fa fa-key"></i> 
                        Tanda Tangan Dokumen
                    </a>
                    <p>
                    <div class="callout callout-danger">
                       <h4>Perhatian</h4>
                       <p>Silahkan klik tandatangan dokumen jika anda sudah mempunyai sertifikat elektronik dan sudah terdaftar pada aplikasi tandatangan elektronik</p>
                     </div>
                </div>
            <?php echo form_close(); ?>
        </div>
    </div>
    <div class="col-md-3">    
        <div class="box box-primary">
            <div class="box-body box-profile">

                <center><img src="<?php echo base_url(); ?><?php echo $foto_pegawai; ?>" class="img-responsive pic-bordered" id="photopegawai" alt="Photo" width="50%"></center>
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
            <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Profile Pegawai</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <strong><i class="fa fa-user margin-r-5"></i> Jabatan (Sesuai Peta Jabatan)</strong>
              <p class="text-muted">
                  <?php
                    echo isset($NAMA_JABATAN) ? $NAMA_JABATAN : "-";
                  ?>
              </p>
              <hr>
              <strong><i class="fa fa-users margin-r-5"></i> Masa Kerja</strong>
              <p class="text-muted">
                  <?php 
                    echo isset($recpns_aktif->masa_kerja_th) ? $recpns_aktif->masa_kerja_th  : ""; ?> 
                    Tahun 
                    <?php echo isset($recpns_aktif->masa_kerja_bl) ? $recpns_aktif->masa_kerja_bl  : ""; ?> Bulan
              </p>
              <hr>
              
               
            </div>
            <!-- /.box-body -->
          </div>
        </div>
    </div>

<script>
    $(".divcatatan_atasan").hide();
    $("#btnsave").click(function(){
        submitdata();
        return false; 
    }); 
    $("#btncancel").click(function(){
        $("#modal-global").modal("hide");
    });
    <?php if($izin_pegawai->STATUS_PENGAJUAN != "3" && $izin_pegawai->STATUS_PENGAJUAN != "1" && $izin_pegawai->STATUS_PENGAJUAN != "2"){?>
        $(".divcatatan_atasan").show();    
    <?php } ?>
    $("#STATUS_ATASAN").change(function(){
        var val_status_atasan = $("#STATUS_ATASAN").val();
        if(val_status_atasan != "3" && val_status_atasan != ""){
            $(".divcatatan_atasan").show();    
        }else{
            $(".divcatatan_atasan").hide();
        }
        
    });
    
    function submitdata(){
        var json_url = "<?php echo base_url() ?>admin/izin/izin_pegawai/saveverifikasi";
        event.preventDefault();

        // Get form
        var form = $('#frm')[0];

        // Create an FormData object 
        var param_data = new FormData(form);
        
         $.ajax({    
            type: "POST",
            enctype: 'multipart/form-data',
            url: json_url,
            data: param_data,
            dataType: "json",
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            success: function(data){ 
                if(data.success){
                    swal("Pemberitahuan!", data.msg, "success");
                    $table.ajax.reload(null,true);
                    $("#modal-global").modal("hide");
                }
                else {
                    $(".messages").empty().append(data.msg);
                }
            },
            error: function (e) {
                $(".messages").empty().append("Ada kesalahan, sialhkan hubungi admin");
                //console.log("ERROR : ", e);

            }});
        return false; 
    }
 $( "#btn_sign" ).click(function() {
    var val_status_atasan = $("#STATUS_ATASAN").val();
        swal({
        title: "Tandatangani Dokumen",
        //text: "Silahkan Masukan Pasphrase anda dengan NIK <?php echo isset($pegawai_login->NIK) ? $pegawai_login->NIK : ""; ?>",
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
        if (val_status_atasan === "") {
          swal.showInputError("Silahkan Tentukan status persetujuan!");
          return false
        }
        if (inputValue === "") {
          swal.showInputError("Silahkan masukan Pasphrase!");
          return false
        }
        
        swal("Perhatian", "Tunggu sebentar, tandatangan digital sedang proses....", "info");
        submitdatattd(inputValue);
      });

    }); 
 function submitdatattd(pass){
    var valid_file = "<?php echo $kode_pengajuan; ?>";
    var datas = "id_file="+valid_file+"&passphrase="+pass+"&username=<?php echo isset($pegawai_login->NIK) ? trim($pegawai_login->NIK) : ""; ?>&NIP=<?php echo isset($pegawai_login->NIP_BARU) ? trim($pegawai_login->NIP_BARU) : ""; ?>";
    var json_url = "<?php echo base_url() ?>admin/izin/izin_pegawai/tandatangansk";
     $.ajax({    
      type: "POST",
      url: json_url,
      data: datas,
            dataType: "json",
            success: function(data){ 
                if(data.success){
                    submitdata();
                    swal("Pemberitahuan!", data.msg, "success");
                    //location.reload();
                }
                else {
                  swal("Pemberitahuan!", data.msg, "error");    
                }
      }});
    return false; 
  }
</script>