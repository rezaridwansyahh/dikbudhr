<div class="tab-pane" id="<?php echo $TAB_ID;?>">
	<?php 
    $id = isset($riwayat_pns_cpns->ID) ? $riwayat_pns_cpns->ID : '';
    $KARPEG = isset($riwayat_pns_cpns->KARPEG) && $riwayat_pns_cpns->KARPEG != "" ? $riwayat_pns_cpns->KARPEG : $NO_KARPEG;
?>
<div class='box box-warning' id="form-riwayat_pns_cpns">
	<div class="box-body">
        <div class="messages">
        </div>
    <?php echo form_open($this->uri->uri_string(), 'id="frm"'); ?>
        <fieldset>
            <input id='PNS_ID' type='hidden' class="form-control" name='PNS_ID' maxlength='32' value="<?php echo set_value('PNS_ID', isset($PNS_ID) ? trim($PNS_ID) : ''); ?>" />
            <input id='ID_PNS_CPNS' type='hidden' class="form-control" name='ID_PNS_CPNS' maxlength='32' value="<?php echo set_value('id', isset($id) ? trim($id) : ''); ?>" />
           
            <div class="control-group<?php echo form_error('STATUS_KEPEGAWAIAN') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label('STATUS KEPEG', 'STATUS_KEPEGAWAIAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                	<select name="STATUS_KEPEGAWAIAN" readonly id="STATUS_KEPEGAWAIAN" class="form-control">
						<option value="PNS" selected>PNS</option>
					</select>
                    <span class='help-inline'><?php echo form_error('JENIS_DIKLAT'); ?></span>
                </div>
            </div>               
			<div class="control-group col-sm-3">
				<label for="inputNAMA" class="control-label">TMT CPNS</label>
				<div class="input-group date">
				  <div class="input-group-addon">
					<i class="fa fa-calendar"></i>
				  </div>
					<input type='text' class="form-control pull-right datepicker" name='TMT_CPNS'  value="<?php echo set_value('TMT_CPNS', isset($riwayat_pns_cpns->TMT_CPNS) ? $riwayat_pns_cpns->TMT_CPNS : ''); ?>" />
					<span class='help-inline'><?php echo form_error('TMT_CPNS'); ?></span>
				</div>
			</div> 
			<div class="control-group col-sm-3">
				<label for="inputNAMA" class="control-label">TANGGAL SK CPNS</label>
				<div class="input-group date">
				  <div class="input-group-addon">
					<i class="fa fa-calendar"></i>
				  </div>
					<input type='text' class="form-control pull-right datepicker" name='TGL_SK_CPNS'  value="<?php echo set_value('TGL_SK_CPNS', isset($riwayat_pns_cpns->TGL_SK_CPNS) ? $riwayat_pns_cpns->TGL_SK_CPNS : ''); ?>" />
					<span class='help-inline'><?php echo form_error('TGL_SK_CPNS'); ?></span>
				</div>
			</div> 
            <div class="control-group col-sm-6">
				<label for="inputNAMA" class="control-label">NO SK CPNS</label>
				<div class='controls'>
					<input type='text' class="form-control pull-right " name='NO_SK_CPNS'  value="<?php echo set_value('NO_SK_CPNS', isset($riwayat_pns_cpns->NO_SK_CPNS) ? $riwayat_pns_cpns->NO_SK_CPNS : ''); ?>" />
					<span class='help-inline'><?php echo form_error('NO_SK_CPNS'); ?></span>
				</div>
			</div> 
			<div class="control-group<?php echo form_error('JENIS_PENGADAAN') ? ' error' : ''; ?> col-sm-3">
                <?php echo form_label("JENIS PENGADAAN", 'JENIS_PENGADAAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='JENIS_PENGADAAN' type='text' class="form-control" name='JENIS_PENGADAAN' maxlength='225' value="<?php echo set_value('JENIS_PENGADAAN', isset($riwayat_pns_cpns->JENIS_PENGADAAN) ? $riwayat_pns_cpns->JENIS_PENGADAAN : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('JENIS_PENGADAAN'); ?></span>
                </div>
            </div>
            <div class="control-group col-sm-3">
				<label for="inputNAMA" class="control-label">TANGGAL SPMT</label>
				<div class="input-group date">
				  <div class="input-group-addon">
					<i class="fa fa-calendar"></i>
				  </div>
					<input type='text' class="form-control pull-right datepicker" name='TGL_SPMT'  value="<?php echo set_value('TGL_SPMT', isset($riwayat_pns_cpns->TGL_SPMT) ? $riwayat_pns_cpns->TGL_SPMT : ''); ?>" />
					<span class='help-inline'><?php echo form_error('TGL_SPMT'); ?></span>
				</div>
			</div> 
            <div class="control-group<?php echo form_error('NO_SPMT') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label("NO SPMT", 'NO_SPMT', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='NO_SPMT' type='text' class="form-control" name='NO_SPMT' maxlength='225' value="<?php echo set_value('NO_SPMT', isset($riwayat_pns_cpns->NO_SPMT) ? $riwayat_pns_cpns->NO_SPMT : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('NO_SPMT'); ?></span>
                </div>
            </div>
            <div class="control-group col-sm-3">
				<label for="inputNAMA" class="control-label">TMT PNS</label>
				<div class="input-group date">
				  <div class="input-group-addon">
					<i class="fa fa-calendar"></i>
				  </div>
					<input type='text' class="form-control pull-right datepicker" name='TMT_PNS'  value="<?php echo set_value('TMT_PNS', isset($riwayat_pns_cpns->TMT_PNS) ? $riwayat_pns_cpns->TMT_PNS : ''); ?>" />
					<span class='help-inline'><?php echo form_error('TMT_PNS'); ?></span>
				</div>
			</div> 
			<div class="control-group col-sm-3">
				<label for="inputNAMA" class="control-label">TANGGAL SK PNS</label>
				<div class="input-group date">
				  <div class="input-group-addon">
					<i class="fa fa-calendar"></i>
				  </div>
					<input type='text' class="form-control pull-right datepicker" name='TGL_SK_PNS'  value="<?php echo set_value('TGL_SK_PNS', isset($riwayat_pns_cpns->TGL_SK_PNS) ? $riwayat_pns_cpns->TGL_SK_PNS : ''); ?>" />
					<span class='help-inline'><?php echo form_error('TGL_SK_PNS'); ?></span>
				</div>
			</div> 
            <div class="control-group col-sm-6">
				<label for="inputNAMA" class="control-label">NO SK PNS</label>
				<div class='controls'>
					<input type='text' class="form-control pull-right " name='N0_SK_PNS'  value="<?php echo set_value('N0_SK_PNS', isset($riwayat_pns_cpns->N0_SK_PNS) ? $riwayat_pns_cpns->N0_SK_PNS : ''); ?>" />
					<span class='help-inline'><?php echo form_error('N0_SK_PNS'); ?></span>
				</div>
			</div>
			<div class="control-group col-sm-3">
				<label for="inputNAMA" class="control-label">TGL PERTEK C2TH</label>
				<div class="input-group date">
				  <div class="input-group-addon">
					<i class="fa fa-calendar"></i>
				  </div>
					<input type='text' class="form-control pull-right datepicker" name='TGL_PERTEK_C2TH'  value="<?php echo set_value('TGL_PERTEK_C2TH', isset($riwayat_pns_cpns->TGL_PERTEK_C2TH) ? $riwayat_pns_cpns->TGL_PERTEK_C2TH : ''); ?>" />
					<span class='help-inline'><?php echo form_error('TGL_PERTEK_C2TH'); ?></span>
				</div>
			</div> 
			<div class="control-group col-sm-3">
				 
			</div> 
            <div class="control-group col-sm-6">
				<label for="inputNAMA" class="control-label">NO PERTEK C2TH</label>
				<div class='controls'>
					<input type='text' class="form-control pull-right " name='NO_PERTEK_C2TH'  value="<?php echo set_value('NO_PERTEK_C2TH', isset($riwayat_pns_cpns->NO_PERTEK_C2TH) ? $riwayat_pns_cpns->NO_PERTEK_C2TH : ''); ?>" />
					<span class='help-inline'><?php echo form_error('NO_PERTEK_C2TH'); ?></span>
				</div>
			</div>

			<div class="control-group col-sm-3">
				<label for="inputNAMA" class="control-label">TGL. KEP PNS HONORER >= 2TH</label>
				<div class="input-group date">
				  <div class="input-group-addon">
					<i class="fa fa-calendar"></i>
				  </div>
					<input type='text' class="form-control pull-right datepicker" name='TGL_KEP_HONORER_2TAHUN'  value="<?php echo set_value('TGL_KEP_HONORER_2TAHUN', isset($riwayat_pns_cpns->TGL_KEP_HONORER_2TAHUN) ? $riwayat_pns_cpns->TGL_KEP_HONORER_2TAHUN : ''); ?>" />
					<span class='help-inline'><?php echo form_error('TGL_KEP_HONORER_2TAHUN'); ?></span>
				</div>
			</div> 
			<div class="control-group col-sm-3">
				 
			</div> 
            <div class="control-group col-sm-6">
				<label for="inputNAMA" class="control-label">NO KEP PNS HONORER >= 2TH</label>
				<div class='controls'>
					<input type='text' class="form-control pull-right " name='NO_PERTEK_KEP_HONORER_2TAHUN'  value="<?php echo set_value('NO_PERTEK_KEP_HONORER_2TAHUN', isset($riwayat_pns_cpns->NO_PERTEK_KEP_HONORER_2TAHUN) ? $riwayat_pns_cpns->NO_PERTEK_KEP_HONORER_2TAHUN : ''); ?>" />
					<span class='help-inline'><?php echo form_error('NO_PERTEK_KEP_HONORER_2TAHUN'); ?></span>
				</div>
			</div>

			<div class="control-group col-sm-6">
				<label for="inputNAMA" class="control-label">KARIS/KARSU</label>
				<div class='controls'>
					<input type='text' class="form-control pull-right" name='KARIS_KARSU'  value="<?php echo set_value('KARIS_KARSU', isset($riwayat_pns_cpns->KARIS_KARSU) ? $riwayat_pns_cpns->KARIS_KARSU : ''); ?>" />
					<span class='help-inline'><?php echo form_error('KARIS_KARSU'); ?></span>
				</div>
			</div> 
			 
            <div class="control-group col-sm-6">
				<label for="inputNAMA" class="control-label">KARPEG</label>
				<div class='controls'>
					<input type='text' class="form-control pull-right " name='KARPEG'  value="<?php echo set_value('KARPEG', isset($KARPEG) ? $KARPEG : ''); ?>" />
					<span class='help-inline'><?php echo form_error('KARPEG'); ?></span>
				</div>
			</div>

			<div class="control-group col-sm-3">
				<label for="inputNAMA" class="control-label">TGL. STTPL</label>
				<div class="input-group date">
				  <div class="input-group-addon">
					<i class="fa fa-calendar"></i>
				  </div>
					<input type='text' class="form-control pull-right datepicker" name='TGL_STTPL'  value="<?php echo set_value('TGL_STTPL', isset($riwayat_pns_cpns->TGL_STTPL) ? $riwayat_pns_cpns->TGL_STTPL : ''); ?>" />
					<span class='help-inline'><?php echo form_error('TGL_STTPL'); ?></span>
				</div>
			</div> 
			<div class="control-group col-sm-3">
				 
			</div> 
            <div class="control-group col-sm-6">
				<label for="inputNAMA" class="control-label">NO STTPL</label>
				<div class='controls'>
					<input type='text' class="form-control pull-right " name='NO_STTPL'  value="<?php echo set_value('NO_STTPL', isset($riwayat_pns_cpns->NO_STTPL) ? $riwayat_pns_cpns->NO_STTPL : ''); ?>" />
					<span class='help-inline'><?php echo form_error('NO_STTPL'); ?></span>
				</div>
			</div>

			<div class="control-group col-sm-3">
				<label for="inputNAMA" class="control-label">TGL. DOKTER</label>
				<div class="input-group date">
				  <div class="input-group-addon">
					<i class="fa fa-calendar"></i>
				  </div>
					<input type='text' class="form-control pull-right datepicker" name='TGL_DOKTER'  value="<?php echo set_value('TGL_DOKTER', isset($riwayat_pns_cpns->TGL_DOKTER) ? $riwayat_pns_cpns->TGL_DOKTER : ''); ?>" />
					<span class='help-inline'><?php echo form_error('TGL_DOKTER'); ?></span>
				</div>
			</div> 
			<div class="control-group col-sm-3">
				 
			</div> 
            <div class="control-group col-sm-6">
				<label for="inputNAMA" class="control-label">NO SURAT DOKTER</label>
				<div class='controls'>
					<input type='text' class="form-control pull-right " name='NO_SURAT_DOKTER'  value="<?php echo set_value('NO_SURAT_DOKTER', isset($riwayat_pns_cpns->NO_SURAT_DOKTER) ? $riwayat_pns_cpns->NO_SURAT_DOKTER : ''); ?>" />
					<span class='help-inline'><?php echo form_error('NO_SURAT_DOKTER'); ?></span>
				</div>
			</div>
			<div class="control-group col-sm-12">
				<label for="inputNAMA" class="control-label">NAMA JABATAN YANG MENGANGKAT CPNS</label>
				<div class='controls'>
					<input type='text' class="form-control pull-right " name='NAMA_JABATAN_ANGKAT_CPNS'  value="<?php echo set_value('NAMA_JABATAN_ANGKAT_CPNS', isset($riwayat_pns_cpns->NAMA_JABATAN_ANGKAT_CPNS) ? $riwayat_pns_cpns->NAMA_JABATAN_ANGKAT_CPNS : ''); ?>" />
					<span class='help-inline'><?php echo form_error('NAMA_JABATAN_ANGKAT_CPNS'); ?></span>
				</div>
			</div>
			
        </div>
  		<div class="box-footer">
  			<a href="javascript:;" id="btnsave_pns"  class="btn green btn-primary button-submit"> 
  				<i class="fa fa-save"></i> 
  				Simpan
            </a>
        </div>
    <?php echo form_close(); ?>
</div>
<script src="<?php echo base_url(); ?>themes/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
<script>
    //Date picker
    $('.datepicker').datepicker({
      autoclose: true,format: 'yyyy-mm-dd'
    });
</script>
<script>
	$("#btnsave_pns").click(function(){
		submitdatariwayatpns();
		return false; 
	});	
	function submitdatariwayatpns(){
		$('#btnsave_pns').addClass('disabled');
		var json_url = "<?php echo base_url() ?>pegawai/riwayat_pns/save";
		 $.ajax({    
		 	type: "POST",
			url: json_url,
			data: $("#frm").serialize(),
            dataType: "json",
			success: function(data){ 
                if(data.success){
                    swal("Pemberitahuan!", data.msg, "success");
                    $("#ID_PNS_CPNS").val(data.kode);
                    $('#btnsave_pns').removeClass('disabled');
                }
                else {
                    $("#form-riwayat_pns_cpns .messages").empty().append(data.msg);
                }
			}});
		return false; 
	}
</script>
</div>