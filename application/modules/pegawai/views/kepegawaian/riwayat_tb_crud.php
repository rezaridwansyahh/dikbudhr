<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/select2/select2.min.css">
<link href="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/css/fileinput.css" media="all" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/themes/explorer/theme.css" media="all" rel="stylesheet" type="text/css"/>
<?php 
    $id = isset($detail_riwayat->ID) ? $detail_riwayat->ID : '';
?>
<div class='box box-warning' id="form-riwayat-tb-add">
	<div class="box-body">
        <div class="messages">
        </div>
    <?php echo form_open($this->uri->uri_string(), 'id="frmtugasbelajar"'); ?>
    <fieldset>
       
            <input id='id' type='hidden' class="form-control" name='id' maxlength='32' value="<?php echo set_value('ID', isset($detail_riwayat->ID) ? trim($detail_riwayat->ID) : ''); ?>" />
            <input id='NIP' type='hidden' class="form-control" name='NIP' maxlength='32' value="<?php echo set_value('NIP_BARU', isset($NIP_BARU) ? trim($NIP_BARU) : ''); ?>" />
                <div class="control-group<?php echo form_error('n_gol_ruang') ? ' error' : ''; ?> col-sm-8">
                    <?php echo form_label("NOMOR SK", 'Golongan Ruang', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <input id='NOMOR_SK' type='text' class="form-control" name='NOMOR_SK' maxlength='200' value="<?php echo set_value('NOMOR_SK', isset($detail_riwayat->NOMOR_SK) ? trim($detail_riwayat->NOMOR_SK) : ''); ?>" />
                        <span class='help-inline'><?php echo form_error('NOMOR_SK'); ?></span>
                    </div>
                </div>
                <div class="control-group col-sm-4">
                    <label for="inputNAMA" class="control-label">TANGGAL SK</label>
                    <div class="input-group date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                        <input type='text' class="form-control pull-right datepicker" name='TANGGAL_SK'  value="<?php echo set_value('TANGGAL_SK', isset($detail_riwayat->TANGGAL_SK) ? $detail_riwayat->TANGGAL_SK : ''); ?>" />
                        <span class='help-inline'><?php echo form_error('TANGGAL_SK'); ?></span>
                    </div>
                </div> 
                <div class="control-group<?php echo form_error('UNIVERSITAS') ? ' error' : ''; ?> col-sm-12">
                    <?php echo form_label("UNIVERSITAS", 'UNIVERSITAS', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <input id='UNIVERSITAS' type='text' class="form-control" name='UNIVERSITAS' maxlength='200' value="<?php echo set_value('UNIVERSITAS', isset($detail_riwayat->UNIVERSITAS) ? trim($detail_riwayat->UNIVERSITAS) : ''); ?>" />
                        <span class='help-inline'><?php echo form_error('UNIVERSITAS'); ?></span>
                    </div>
                </div>
                 
                <div class="control-group<?php echo form_error('FAKULTAS') ? ' error' : ''; ?> col-sm-12">
                    <?php echo form_label("FAKULTAS", 'FAKULTAS', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <input id='FAKULTAS' type='text' class="form-control" name='FAKULTAS' maxlength='200' value="<?php echo set_value('FAKULTAS', isset($detail_riwayat->FAKULTAS) ? trim($detail_riwayat->FAKULTAS) : ''); ?>" />
                        <span class='help-inline'><?php echo form_error('FAKULTAS'); ?></span>
                    </div>
                </div>
                 
                <div class="control-group<?php echo form_error('PROGRAM_STUDI') ? ' error' : ''; ?> col-sm-12">
                    <?php echo form_label("PROGRAM STUDI", 'PROGRAM_STUDI', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <input id='PROGRAM_STUDI' type='text' class="form-control" name='PROGRAM_STUDI' maxlength='200' value="<?php echo set_value('PROGRAM_STUDI', isset($detail_riwayat->PROGRAM_STUDI) ? trim($detail_riwayat->PROGRAM_STUDI) : ''); ?>" />
                        <span class='help-inline'><?php echo form_error('PROGRAM_STUDI'); ?></span>
                    </div>
                </div>
                 
                 
                <div class="control-group col-sm-6">
                    <label for="inputNAMA" class="control-label">DARI TANGGAL</label>
                    <div class="input-group date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                        <input type='text' class="form-control pull-right datepicker" name='MULAI_BELAJAR'  value="<?php echo set_value('MULAI_BELAJAR', isset($detail_riwayat->MULAI_BELAJAR) ? $detail_riwayat->MULAI_BELAJAR : ''); ?>" />
                        <span class='help-inline'><?php echo form_error('MULAI_BELAJAR'); ?></span>
                    </div>
                </div> 
                <div class="control-group col-sm-6">
                    <label class="control-label">SAMPAI TANGGAL</label>
                    <div class="input-group date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                        <input type='text' class="form-control pull-right datepicker" name='AKHIR_BELAJAR'  value="<?php echo set_value('AKHIR_BELAJAR', isset($detail_riwayat->AKHIR_BELAJAR) ? $detail_riwayat->AKHIR_BELAJAR : ''); ?>" />
                        <span class='help-inline'><?php echo form_error('AKHIR_BELAJAR'); ?></span>
                    </div>
                </div>   
            <div class="control-group col-sm-9">
                <label for="inputNAMA" class="control-label">Berkas</label>
                <div class='controls'>
                    <div id="form_upload">
                      <input id="file_dokumen" name="file_dokumen" class="file" type="file" data-preview-file-type="pdf">
                    </div>
                    
                </div>
            </div> 
            <div class="control-group col-sm-3">
                <label for="inputNAMA" class="control-label"><br></label>
                <div class='controls'>
                    
                    <?php if(isset($detail_riwayat->FILE_BASE64) && $detail_riwayat->FILE_BASE64 != ""){ ?>
                        <a href="<?php echo base_url(); ?>pegawai/riwayat_tugasbelajar/viewdoc/<?php echo $detail_riwayat->ID; ?>" class="btn btn-warning show-modal" tooltip="Lihat dokumen <?php echo isset($detail_riwayat->NOMOR_SK) ? $detail_riwayat->NOMOR_SK : ''; ?>" ><span class="fa fa-file-o"></span> Lihat</a>
                    <?php } ?>  
                </div>
            </div>
            <div class="control-group<?php echo form_error('STATUS_TB') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("STATUS TB", 'STATUS_TB', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select name="STATUS_TB" class="form-control">
                        <option value="">Silahkan Pilih</option>
                        <option value="1" <?php echo $pengajuan_tubel->STATUS_TB == "1" ? "selected" : ""; ?>>Aktif Kembali</option>
                        <option value="2" <?php echo $pengajuan_tubel->STATUS_TB == "2" ? "selected" : ""; ?>>Aktif Tugas Belajar</option>
                    </select>
                    <span class='help-inline'><?php echo form_error('STATUS_TB'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('STATUS_TB') ? ' error' : ''; ?> col-sm-12">
                <div class='alert alert-block alert-warning fade in'>
                    <a class='close' data-dismiss='alert'>&times;</a>
                    <h4 class='alert-heading'>
                        Perhatian
                    </h4>
                    Pilih status "Aktif Kembali" untuk merubah status pegawai menjadi <b>Aktif</b>, Pilih status "Aktif Tugas Belajar" untuk merubah status pegawai menjadi <b>Tugas Belajar</b>.
                </div>    
            </div>    
            </fieldset>
        </div>
  		<div class="box-footer">
            <input type='submit' name='save' id="btnsavetb" class='btn btn-primary' value="Simpan Data" /> 
        </div>
    <?php echo form_close(); ?>
</div>
<script src="<?php echo base_url(); ?>themes/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/themes/explorer/theme.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/js/fileinput.js" type="text/javascript"></script>
<script>
	 $(".select2").select2({width: '100%'});
</script>
<script>
  
    var form = $("#form-riwayat-kgb-add");
    //Date picker
    $('.datepicker').datepicker({
      autoclose: true,format: 'yyyy-mm-dd'
    }).on("input change", function (e) {
        var date = $(this).datepicker('getDate');
    });
</script>
<script>
	$("#btnsavetb").click(function(){
		submitdatatb();
		return false; 
	});	
	$("#frmA").submit(function(){
		submitdatatb();
		return false; 
	});	
    $(document).ready(function(){
        $(".lazy-select2").each(function(i,o){
            $(this).select2(
                {
                    placeholder: $(this).data('placeholder'),
                    width: '100%',
                    minimumInputLength: $(this).data('minimum'),
                    allowClear: true,
                    ajax: {
                        url: $(this).data('url'),
                        dataType: 'json',
                        data: function(params) {
                            return {
                                term: params.term || '',
                                page: params.page || 1
                            }
                        },
                        cache: true
                    }
                }
            );
        });
    });
	function submitdatatb(){
		var the_data = new FormData(document.getElementById("frmtugasbelajar"));
		var json_url = "<?php echo base_url() ?>pegawai/riwayat_tugasbelajar/save";
		 $.ajax({    
		 	type: "POST",
			url: json_url,
			data: the_data,
            dataType: "json",
            processData: false, // tell jQuery not to process the data
            contentType: false, // tell jQuery not to set contentType
			success: function(data){ 
                if(data.success){
                    swal("Pemberitahuan!", data.msg, "success");
                    $("#modal-custom-global").trigger("sukses-tambah-riwayat-tb");
					$("#modal-custom-global").modal("hide");
                }
                else {
                    $("#form-riwayat-tb-add .messages").empty().append(data.msg);
                }
			}});
		return false; 
	}
</script>