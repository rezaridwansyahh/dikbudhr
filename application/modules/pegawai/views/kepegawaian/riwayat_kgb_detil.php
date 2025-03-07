<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/select2/select2.min.css">

<?php 
    $id = isset($detail_riwayat->ID) ? $detail_riwayat->ID : '';
?>
<div class='box box-warning' id="form-riwayat-kgb-add">
	<div class="box-body">
        <div class="messages">
        </div>
    <?php echo form_open($this->uri->uri_string(), 'id="frm"'); ?>
    <fieldset>
       
            <input id='id' type='hidden' class="form-control" name='id' maxlength='32' value="<?php echo set_value('id', isset($detail_riwayat->id) ? trim($detail_riwayat->id) : ''); ?>" />
            <input id='PNS_ID' type='hidden' class="form-control" name='PNS_ID' maxlength='32' value="<?php echo set_value('PNS_ID', isset($PNS_ID) ? trim($PNS_ID) : ''); ?>" />
            <div class="row">   
                <div class="control-group<?php echo form_error('n_gol_ruang') ? ' error' : ''; ?> col-sm-6">
                    <?php echo form_label("Golongan Ruang", 'Golongan Ruang', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <select name='n_golongan_id' class='select2 form-control'>
                            <?php 
                                foreach($list_golongan as $row){
                                    $selected = '';
                                    if($row->ID == $detail_riwayat->n_golongan_id){
                                        $selected = ' SELECTED ';
                                    }
                                    echo "<option $selected value='$row->ID'>$row->NAMA_PANGKAT - $row->NAMA</option>";
                                }
                            ?>
                        </select>
                        <span class='help-inline'><?php echo form_error('n_gol_ruang'); ?></span>
                    </div>
                </div>
                <div class="control-group col-sm-4">
                    <label for="inputNAMA" class="control-label">TMT Golongan</label>
                    <div class="input-group date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                        <input type='text' class="form-control pull-right datepicker" name='n_gol_tmt'  value="<?php echo set_value('n_gol_tmt', isset($detail_riwayat->n_gol_tmt) ? $detail_riwayat->n_gol_tmt : ''); ?>" />
                        <span class='help-inline'><?php echo form_error('n_gol_tmt'); ?></span>
                    </div>
                </div> 
            </div>
            <div class="row">   
                <div class="control-group<?php echo form_error('n_masakerja_thn') ? ' error' : ''; ?> col-sm-6">
                    <?php echo form_label("Masa Kerja (Thn)", 'Masa Kerja (Thn)', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <input id='n_masakerja_thn' type='text' class="form-control" name='n_masakerja_thn' maxlength='200' value="<?php echo set_value('n_masakerja_thn', isset($detail_riwayat->n_masakerja_thn) ? trim($detail_riwayat->n_masakerja_thn) : ''); ?>" />
                        <span class='help-inline'><?php echo form_error('n_masakerja_thn'); ?></span>
                    </div>
                </div>
                <div class="control-group<?php echo form_error('n_masakerja_bln') ? ' error' : ''; ?> col-sm-6">
                    <?php echo form_label("Masa Kerja (Bln)", 'Masa Kerja (Bln)', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <input id='n_masakerja_bln' type='text' class="form-control" name='n_masakerja_bln' maxlength='200' value="<?php echo set_value('n_masakerja_bln', isset($detail_riwayat->n_masakerja_bln) ? trim($detail_riwayat->n_masakerja_bln) : ''); ?>" />
                        <span class='help-inline'><?php echo form_error('n_masakerja_bln'); ?></span>
                    </div>
                </div>
            </div>
            <div class="row">   
                <div class="control-group<?php echo form_error('n_gapok') ? ' error' : ''; ?> col-sm-6">
                    <?php echo form_label("Gaji Pokok Baru", 'Gaji Pokok Baru', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <input id='n_gapok' type='text' class="form-control" name='n_gapok' maxlength='200' value="<?php echo set_value('n_gapok', isset($detail_riwayat->n_gapok) ? trim($detail_riwayat->n_gapok) : ''); ?>" />
                        <span class='help-inline'><?php echo form_error('n_gapok'); ?></span>
                    </div>
                </div>
            </div>    
            <div class="row"> 
                <div class="control-group<?php echo form_error('no_sk') ? ' error' : ''; ?> col-sm-4">
                    <?php echo form_label("NO SK", 'NO SK', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <input id='no_sk' type='text' class="form-control" name='no_sk' maxlength='200' value="<?php echo set_value('no_sk', isset($detail_riwayat->no_sk) ? trim($detail_riwayat->no_sk) : ''); ?>" />
                        <span class='help-inline'><?php echo form_error('no_sk'); ?></span>
                    </div>
                </div>
                <div class="control-group col-sm-4">
                    <label for="inputNAMA" class="control-label">TANGGAL SK</label>
                    <div class="input-group date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                        <input type='text' class="form-control pull-right datepicker" name='tgl_sk'  value="<?php echo set_value('tgl_sk', isset($detail_riwayat->tgl_sk) ? $detail_riwayat->tgl_sk : ''); ?>" />
                        <span class='help-inline'><?php echo form_error('tgl_sk'); ?></span>
                    </div>
                </div> 
                <div class="control-group col-sm-4">
                    <label class="control-label">TMT KGB</label>
                    <div class="input-group date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                        <input type='text' class="form-control pull-right datepicker" name='tmt_sk'  value="<?php echo set_value('tmt_sk', isset($detail_riwayat->tmt_sk) ? $detail_riwayat->tmt_sk : ''); ?>" />
                        <span class='help-inline'><?php echo form_error('tmt_sk'); ?></span>
                    </div>
                </div>   
            </div>    
            <div class="row">   
                <div class="control-group<?php echo form_error('n_jabatan_text') ? ' error' : ''; ?> col-sm-6">
                    <?php echo form_label("Jabatan", 'Jabatan', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <input id='n_jabatan_text' type='text' class="form-control" name='n_jabatan_text' maxlength='200' value="<?php echo set_value('n_jabatan_text', isset($detail_riwayat->n_jabatan_text) ? trim($detail_riwayat->n_jabatan_text) : ''); ?>" />
                        <span class='help-inline'><?php echo form_error('n_jabatan_text'); ?></span>
                    </div>
                </div>
                <div class="control-group col-sm-4">
                    <label class="control-label">TMT Jabatan</label>
                    <div class="input-group date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                        <input type='text' class="form-control pull-right datepicker" name='n_tmt_jabatan'  value="<?php echo set_value('n_tmt_jabatan', isset($detail_riwayat->n_tmt_jabatan) ? $detail_riwayat->n_tmt_jabatan : ''); ?>" />
                        <span class='help-inline'><?php echo form_error('n_tmt_jabatan'); ?></span>
                    </div>
                </div>   
            </div> 
            <div class="row">   
                <div class="control-group<?php echo form_error('last_education') ? ' error' : ''; ?> col-sm-6">
                    <?php echo form_label("Pendidikan", 'Pendidikan', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <input id='last_education' type='text' class="form-control" name='last_education' maxlength='200' value="<?php echo set_value('last_education', isset($detail_riwayat->last_education) ? trim($detail_riwayat->last_education) : ''); ?>" />
                        <span class='help-inline'><?php echo form_error('last_education'); ?></span>
                    </div>
                </div>
                <div class="control-group col-sm-4">
                    <label class="control-label">TGL LULUS</label>
                    <div class="input-group date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                        <input type='text' class="form-control pull-right datepicker" name='last_education_date'  value="<?php echo set_value('last_education_date', isset($detail_riwayat->last_education_date) ? $detail_riwayat->last_education_date : ''); ?>" />
                        <span class='help-inline'><?php echo form_error('last_education_date'); ?></span>
                    </div>
                </div>   
            </div> 
            <div class="row"> 
                <div class="control-group<?php echo form_error('pejabat') ? ' error' : ''; ?> col-sm-4">
                    <?php echo form_label("Pejabat", 'Pejabat', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <input id='pejabat' type='text' class="form-control" name='pejabat' maxlength='200' value="<?php echo set_value('pejabat', isset($detail_riwayat->pejabat) ? trim($detail_riwayat->pejabat) : ''); ?>" />
                        <span class='help-inline'><?php echo form_error('pejabat'); ?></span>
                    </div>
                </div>
                <div class="control-group<?php echo form_error('kantor_pembayaran') ? ' error' : ''; ?> col-sm-4">
                    <?php echo form_label("Kantor Pembayaran", 'Kantor Pembayaran', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <input id='kantor_pembayaran' type='text' class="form-control" name='kantor_pembayaran' maxlength='200' value="<?php echo set_value('kantor_pembayaran', isset($detail_riwayat->kantor_pembayaran) ? trim($detail_riwayat->kantor_pembayaran) : ''); ?>" />
                        <span class='help-inline'><?php echo form_error('kantor_pembayaran'); ?></span>
                    </div>
                </div>
            </div>  
            <div class="row"> 
                <div class="control-group<?php echo form_error('unit_kerja_induk_id') ? ' error' : ''; ?> col-sm-6">
                    <?php echo form_label("Unit Kerja Induk", 'Unit Kerja Induk', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <select name="unit_kerja_induk_id" class="select2 form-control lazy-select2" data-minimum=0 data-placeholder='Cari Unit Kerja' data-url='<?php echo base_url('pegawai/riwayat_kgb/list_satker');?>'>
                                <?php 
                                    if($selectedUnitKerjaInduk){
                                        echo "<option value='{$selectedUnitKerjaInduk->ID}'>{$selectedUnitKerjaInduk->NAMA_UNOR}</option>";
                                    }
                                ?>
                        </select>        
                        <span class='help-inline'><?php echo form_error('unit_kerja_induk_id'); ?></span>
                    </div>
                </div>
            </div>  
            </fieldset>
        </div>
  		<div class="box-footer">
            <input type='submit' name='save' id="btnsave" class='btn btn-primary' value="Simpan Data" /> 
        </div>
    <?php echo form_close(); ?>
</div>
<script src="<?php echo base_url(); ?>themes/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
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
        if(date)$("[name=TAHUN]",form).val(date.getFullYear());
    });
</script>
<script>
	$("#btnsave").click(function(){
		submitdata();
		return false; 
	});	
	$("#frmA").submit(function(){
		submitdata();
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
</script>