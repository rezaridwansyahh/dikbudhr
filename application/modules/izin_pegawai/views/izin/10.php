<link href="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/css/fileinput.css" media="all" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/themes/explorer/theme.css" media="all" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/select2/select2.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/css/dropzone/dropzone.min.css">
<script src="<?php echo base_url(); ?>themes/admin/js/dropzone/dropzone.min.js"></script>
<script src="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/themes/explorer/theme.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/js/fileinput.js" type="text/javascript"></script>

<?php 
    $this->load->library('convert');
    $convert = new convert();
    $id = isset($izin_pegawai->ID) ? $izin_pegawai->ID : '';
?>
<div class="callout callout-success">
   <h4>Perhatian</h4>
   <p>Silahkan lengkapi formulir pengajuan "<?php echo $nama_izin; ?>"</p>
   <p><?php echo $keterangan_izin; ?></p>
 </div>
 <?php if($NIP_ATASAN == ""){ ?>
    <div class="callout callout-danger">
       <h4>Perhatian</h4>
       <p>Belum ada data atasan</p>
     </div>
<?php } ?>
<div class="row">
    <div class="col-md-9">     
        <div class='box box-warning' id="form-riwayat-assesmen-add">
            <div class="box-body">
                    <?php
                    //$this->load->view('izin/_lineapproval',array('level'=>$level,'NIP_ATASAN'=>$NIP_ATASAN,'NAMA_ATASAN'=>$NAMA_ATASAN,'NIP_PPK'=>$NIP_PPK,'NAMA_PPK'=>$NAMA_PPK,'NAMA_PEGAWAI'=>$pegawai->NAMA));
                    ?>
                <div class="messages">
                </div>
            <?php echo form_open($this->uri->uri_string(), 'id="frm"'); ?>
                <fieldset>
                    <input id='ID' type='hidden' name='ID' value="<?php echo set_value('ID', isset($izin_pegawai->ID) ? $izin_pegawai->ID : ""); ?>" />

                    <input id='NIP_PNS' type='hidden' required='required' name='NIP_PNS' maxlength='18' value="<?php echo set_value('NIP_PNS', isset($izin_pegawai->NIP_PNS) ? $izin_pegawai->NIP_PNS : TRIM($NIP_PNS)); ?>" />
                    <input id='NAMA' type='hidden' name='NAMA' maxlength='100' value="<?php echo set_value('NAMA', isset($izin_pegawai->NAMA) ? $izin_pegawai->NAMA : $pegawai->NAMA); ?>" />
                    <input id='JABATAN' type='hidden' name='JABATAN' maxlength='100' value="<?php echo set_value('JABATAN', isset($izin_pegawai->JABATAN) ? $izin_pegawai->JABATAN : $NAMA_JABATAN); ?>" />
                    <input id='UNIT_KERJA' type='hidden' name='UNIT_KERJA' maxlength='100' value="<?php echo set_value('UNIT_KERJA', isset($izin_pegawai->UNIT_KERJA) ? $izin_pegawai->UNIT_KERJA : $unor_induk_id); ?>" />
                    <input id='MASA_KERJA_TAHUN' type='hidden' name='MASA_KERJA_TAHUN'  value="<?php echo set_value('MASA_KERJA_TAHUN', isset($izin_pegawai->MASA_KERJA_TAHUN) ? $izin_pegawai->MASA_KERJA_TAHUN : $recpns_aktif->masa_kerja_th); ?>" />
                    <input id='MASA_KERJA_BULAN' type='hidden' name='MASA_KERJA_BULAN'  value="<?php echo set_value('MASA_KERJA_BULAN', isset($izin_pegawai->MASA_KERJA_BULAN) ? $izin_pegawai->MASA_KERJA_BULAN : $recpns_aktif->masa_kerja_bl); ?>" />
                    <input id='GAJI_POKOK' type='hidden' name='GAJI_POKOK' maxlength='10' value="<?php echo set_value('GAJI_POKOK', isset($izin_pegawai->GAJI_POKOK) ? $izin_pegawai->GAJI_POKOK : ''); ?>" />
                    <input id='KODE_IZIN' type='hidden' required='required' name='KODE_IZIN' maxlength='5' value="<?php echo set_value('KODE_IZIN', isset($izin_pegawai->KODE_IZIN) ? $izin_pegawai->KODE_IZIN : $kode_izin); ?>" />
                    <input id='SATUAN' type='hidden' name='SATUAN' maxlength='10' value="<?php echo set_value('SATUAN', isset($izin_pegawai->SATUAN) ? $izin_pegawai->SATUAN : 'Hari'); ?>" />

                    <input id='NIP_ATASAN' type='hidden' name='NIP_ATASAN' value="<?php echo set_value('NIP_ATASAN', isset($izin_pegawai->NIP_ATASAN) ? $izin_pegawai->NIP_ATASAN : $NIP_ATASAN); ?>" />
                    <input id='NAMA_ATASAN' type='hidden' name='NAMA_ATASAN' value="<?php echo set_value('NAMA_ATASAN', isset($izin_pegawai->NAMA_ATASAN) ? $izin_pegawai->NAMA_ATASAN : $NAMA_ATASAN); ?>" />
                    <input id='NIP_PYBMC' type='hidden' name='NIP_PYBMC' value="<?php echo set_value('NIP_PYBMC', isset($izin_pegawai->NIP_PYBMC) ? $izin_pegawai->NIP_PYBMC : $NIP_PPK); ?>" />
                    <input id='NAMA_PYBMC' type='hidden' name='NAMA_PYBMC' value="<?php echo set_value('NAMA_PYBMC', isset($izin_pegawai->NAMA_PYBMC) ? $izin_pegawai->NAMA_PYBMC : $NAMA_PPK); ?>" />
                     
            
                    <div class="control-group col-sm-12">
                        <label for="inputNAMA" class="control-label">TANGGAL</label>
                        <div class="input-group date">
                          <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                          </div>
                            <input type='text' class="form-control pull-right datepicker" name='DARI_TANGGAL' id='DARI_TANGGAL' value="<?php echo set_value('DARI_TANGGAL', isset($izin_pegawai->DARI_TANGGAL) ? $izin_pegawai->DARI_TANGGAL : ''); ?>" />
                            <span class='help-inline'><?php echo form_error('DARI_TANGGAL'); ?></span>
                        </div>
                    </div> 
                     <div class="control-group<?php echo form_error('KETERANGAN') ? ' error' : ''; ?>  col-sm-12">
                        <?php echo form_label("ALASAN", 'KETERANGAN', array('class' => 'control-label')); ?>
                        <div class='controls'>
                            <textarea id='KETERANGAN' type='text' class="form-control" name='KETERANGAN'><?php echo set_value('KETERANGAN', isset($izin_pegawai->KETERANGAN) ? $izin_pegawai->KETERANGAN : ''); ?></textarea>
                            <span class='help-inline'><?php echo form_error('KETERANGAN'); ?></span>
                        </div>
                    </div>          
                </fieldset>
                </div>
                <div class="box-footer">
                    <a href="javascript:;" id="btnsavecuti" class="btn <?php echo $NIP_ATASAN =="" ? "disabled" : ""; ?> green btn-primary button-submit"> 
                        <i class="fa fa-save"></i> 
                        Simpan
                    </a>
                    <?php echo lang('bf_or'); ?>
                    <button class="btn btn-warning" id="btn_cancel">Cancel</button>
                    
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

              <strong><i class="fa fa-map-marker margin-r-5"></i> Alamat</strong>

              <p class="text-muted"><?php echo set_value('ALAMAT', isset($pegawai->ALAMAT) ? $pegawai->ALAMAT : ''); ?></p>

              <hr>
              <strong><i class="fa fa-users margin-r-5"></i> Masa Kerja</strong>
              <p class="text-muted">
                  <?php 
                    echo isset($recpns_aktif->masa_kerja_th) ? $recpns_aktif->masa_kerja_th  : ""; ?> 
                    Tahun 
                    <?php echo isset($recpns_aktif->masa_kerja_bl) ? $recpns_aktif->masa_kerja_bl  : ""; ?> Bulan
              </p>
              <hr>
              
              <strong><i class="fa fa-file-text-o margin-r-5"></i> Unit Organisasi</strong>
              <p> 
                <ul>
                  <?php 
                      foreach($parent_path_array_unor as $node){
                          echo "<li><strong>".$node->NAMA_UNOR."</strong></li>";        
                      }
                  ?>
                </ul>
                </p>
            </div>
            <!-- /.box-body -->
          </div>
        </div>
    </div>

<script>
    $("#btnsavecuti").click(function(){
        submitdatacuti();
        return false; 
    }); 
    $("#btn_cancel").click(function(){
        $("#modal-global").modal("hide");
        return false; 
    }); 
    function submitdatacuti(){
        var json_url = "<?php echo base_url() ?>admin/izin/izin_pegawai/save";
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
                    $("#modal-global").modal("hide");
                    $table.ajax.reload(null,true);
                    url = "<?php echo base_url(); ?>admin/izin/izin_pegawai/index";
                    $(location).attr("href", url);
                }
                else {
                    $(".messages").empty().append(data.msg);
                }
            },
            error: function (e) {
                $(".messages").empty().append("Ada kesalahan, sialahkan hubungi admin");
                //console.log("ERROR : ", e);

            }});
        return false; 
    }
$('.datepicker').datepicker({
  autoclose: true,format: 'yyyy-mm-dd'
}).on("input change", function (e) {
    var date = $(this).datepicker('getDate');
});
</script>