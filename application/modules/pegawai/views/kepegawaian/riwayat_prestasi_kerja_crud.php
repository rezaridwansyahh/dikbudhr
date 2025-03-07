<?php 
    $id = isset($detail_riwayat->ID) ? $detail_riwayat->ID : '';
?>
<div class='box box-warning' id="form-diklat-fungsional-add">
	<div class="box-body">
        <div class="messages">
        </div>
    <?php echo form_open($this->uri->uri_string(), 'id="frmskp"'); ?>
        <fieldset>
            <input id='ID' type='hidden' class="form-control" name='ID' maxlength='32' value="<?php echo set_value('ID', isset($detail_riwayat->ID) ? trim($detail_riwayat->ID) : ''); ?>" />
            <input id='PNS_ID' type='hidden' class="form-control" name='PNS_ID' maxlength='32' value="<?php echo set_value('PNS_ID', isset($PNS_ID) ? trim($PNS_ID) : ''); ?>" />
          <div class="control-group<?php echo form_error('TAHUN') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("TAHUN SKP", 'TAHUN SKP', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='TAHUN' type='text' class="form-control" name='TAHUN' maxlength='32' value="<?php echo set_value('TAHUN', isset($detail_riwayat->TAHUN) ? $detail_riwayat->TAHUN : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('TAHUN'); ?></span>
                </div>
            </div>
          <div class="control-group<?php echo form_error('JABATAN_TIPE') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label('JENIS JABATAN', 'JENIS JABATAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                	<select name="JABATAN_TIPE" id="JABATAN_TIPE" class="form-control">
						<?php 
                            foreach($jenis_jabatans as $record){
                                $selected = "";
                                if($record->ID == $detail_riwayat->JABATAN_TIPE) $selected = ' selected="selected"';
                                echo "<option  value='".$record->ID."' ".$selected.">".$record->NAMA."</option> ";
                            }
                        ?>
					</select>
                    <span class='help-inline'><?php echo form_error('JABATAN_TIPE'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('JABATAN_NAMA') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label("NAMA JABATAN", 'NAMA JABATAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <!--<input id='JABATAN_NAMA' type='text' class="form-control" name='JABATAN_NAMA' value="<?php //echo set_value('JABATAN_NAMA', isset($detail_riwayat->JABATAN_NAMA) ? $detail_riwayat->JABATAN_NAMA : ''); ?>" />-->
                    <textarea id='JABATAN_NAMA' class="form-control" maxlength='255' name='JABATAN_NAMA'><?php echo set_value('JABATAN_NAMA', isset($detail_riwayat->JABATAN_NAMA) ? $detail_riwayat->JABATAN_NAMA : ''); ?></textarea>
                    <span class='help-inline'><?php echo form_error('JABATAN_NAMA'); ?></span>
                </div>
            </div>
           
            <div class="control-group<?php echo form_error('NILAI_SKP') ? ' error' : ''; ?> col-sm-4">
                <?php echo form_label("NILAI SKP", 'NILAI SKP', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='NILAI_SKP'  class="form-control" name='NILAI_SKP' maxlength='32' value="<?php echo set_value('NILAI_PPK', isset($detail_riwayat->NILAI_SKP) ? $detail_riwayat->NILAI_SKP : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('NILAI_SKP'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('TAHUN') ? ' error' : ''; ?> col-sm-4">
                <?php echo form_label("Prosentase (%)", 'Prosentase (%)', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='NILAI_PROSENTASE_SKP' type='text' readonly class="form-control" name='NILAI_PROSENTASE_SKP' maxlength='32' value="<?php echo set_value('NILAI_PROSENTASE_SKP', isset($NILAI_PROSENTASE_SKP) ? $NILAI_PROSENTASE_SKP : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('NILAI_PROSENTASE_SKP'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('NILAI_SKP_AKHIR') ? ' error' : ''; ?> col-sm-4">
                <?php echo form_label("NILAI SKP AKHIR", 'NILAI_SKP AKHIR', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='NILAI_SKP_AKHIR' readonly type='text' class="form-control" name='NILAI_SKP_AKHIR' maxlength='32' value="<?php echo set_value('NILAI_SKP_AKHIR', isset($detail_riwayat->NILAI_SKP_AKHIR) ? $detail_riwayat->NILAI_SKP_AKHIR : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('NILAI_SKP_AKHIR'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('PERILAKU_KOMITMEN') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label("PERILAKU KOMITMEN", 'PERILAKU KOMITMEN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='PERILAKU_KOMITMEN' type='text' class="form-control" name='PERILAKU_KOMITMEN' maxlength='32' value="<?php echo set_value('PERILAKU_KOMITMEN', isset($detail_riwayat->NILAI_PPK) ? $detail_riwayat->PERILAKU_KOMITMEN : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('PERILAKU_KOMITMEN'); ?></span>
                </div>
            </div>
             <div class="control-group<?php echo form_error('PERILAKU_INTEGRITAS') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label("PERILAKU INTEGRITAS", 'PERILAKU INTEGRITAS', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='PERILAKU_INTEGRITAS' type='text' class="form-control" name='PERILAKU_INTEGRITAS' maxlength='32' value="<?php echo set_value('PERILAKU_INTEGRITAS', isset($detail_riwayat->PERILAKU_INTEGRITAS) ? $detail_riwayat->PERILAKU_INTEGRITAS : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('PERILAKU_INTEGRITAS'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('PERILAKU_DISIPLIN') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label("PERILAKU DISIPLIN", 'PERILAKU DISIPLIN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='PERILAKU_DISIPLIN' type='text' class="form-control" name='PERILAKU_DISIPLIN' maxlength='32' value="<?php echo set_value('PERILAKU_DISIPLIN', isset($detail_riwayat->PERILAKU_DISIPLIN) ? $detail_riwayat->PERILAKU_DISIPLIN : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('PERILAKU_DISIPLIN'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('PERILAKU_KERJASAMA') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label("PERILAKU KERJASAMA", 'PERILAKU KERJASAMA', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='PERILAKU_KERJASAMA' type='text' class="form-control" name='PERILAKU_KERJASAMA' maxlength='32' value="<?php echo set_value('PERILAKU_KERJASAMA', isset($detail_riwayat->PERILAKU_KERJASAMA) ? $detail_riwayat->PERILAKU_KERJASAMA : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('PERILAKU_KERJASAMA'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('PERILAKU_ORIENTASI_PELAYANAN') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label("PERILAKU ORIENTASI PELAYANAN", 'PERILAKU ORIENTASI PELAYANAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='PERILAKU_ORIENTASI_PELAYANAN' type='text' class="form-control" name='PERILAKU_ORIENTASI_PELAYANAN' maxlength='32' value="<?php echo set_value('PERILAKU_ORIENTASI_PELAYANAN', isset($detail_riwayat->PERILAKU_ORIENTASI_PELAYANAN) ? $detail_riwayat->PERILAKU_ORIENTASI_PELAYANAN : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('PERILAKU_ORIENTASI_PELAYANAN'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('PERILAKU_KEPEMIMPINAN') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label("PERILAKU KEPEMIMPINAN", 'PERILAKU KEPEMIMPINAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='PERILAKU_KEPEMIMPINAN' type='text' class="form-control" name='PERILAKU_KEPEMIMPINAN' maxlength='32' value="<?php echo set_value('PERILAKU_KEPEMIMPINAN', isset($detail_riwayat->PERILAKU_KEPEMIMPINAN) ? $detail_riwayat->PERILAKU_KEPEMIMPINAN : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('PERILAKU_KEPEMIMPINAN'); ?></span>
                </div>
            </div>
            <div class="row"></div>
             <div class="control-group<?php echo form_error('NILAI_PERILAKU') ? ' error' : ''; ?> col-sm-4">
                <?php echo form_label("NILAI_PERILAKU", 'NILAI_PERILAKU', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='NILAI_PERILAKU' type='text' class="form-control" name='NILAI_PERILAKU' maxlength='32' value="<?php echo set_value('NILAI_PERILAKU', isset($detail_riwayat->NILAI_PERILAKU) ? $detail_riwayat->NILAI_PERILAKU : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('NILAI_PERILAKU'); ?></span>
                </div>
            </div>
             <div class="control-group<?php echo form_error('TAHUN') ? ' error' : ''; ?> col-sm-4">
                <?php echo form_label("Prosentase (%)", 'Prosentase (%)', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='NILAI_PROSENTASE_PERILAKU' type='text' readonly class="form-control" name='NILAI_PROSENTASE_PERILAKU' maxlength='32' value="<?php echo set_value('NILAI_PROSENTASE_PERILAKU', isset($NILAI_PROSENTASE_PERILAKU) ? $NILAI_PROSENTASE_PERILAKU : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('NILAI_PROSENTASE_PERILAKU'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('NILAI_PERILAKU_AKHIR') ? ' error' : ''; ?> col-sm-4">
                <?php echo form_label("NILAI_PERILAKU_AKHIR", 'NILAI_PERILAKU_AKHIR', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='NILAI_PERILAKU_AKHIR' readonly type='text' class="form-control" name='NILAI_PERILAKU_AKHIR' maxlength='32' value="<?php echo set_value('NILAI_PERILAKU_AKHIR', isset($detail_riwayat->NILAI_PERILAKU_AKHIR) ? $detail_riwayat->NILAI_PERILAKU_AKHIR : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('NILAI_PERILAKU_AKHIR'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('NILAI_PPK') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("NILAI PPK", 'NILAI PPK', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='NILAI_PPK' type='text' class="form-control" readonly name='NILAI_PPK' maxlength='32' value="<?php echo set_value('NILAI_PPK', isset($detail_riwayat->NILAI_PPK) ? $detail_riwayat->NILAI_PPK : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('NILAI_PPK'); ?></span>
                </div>
            </div>	
            <div class="nav-tabs-custom control-group col-sm-12" style="margin-top:5px">
                <ul id="tab-insides-here" class="nav nav-tabs">
                    <li class="active">
                        <a href="#atasan-langsung" data-toggle="tab" aria-expanded="true"> Atasan Langsung </a>
                    </li>
                    <li class="">
                        <a href="#atasan-atasan-langsung" data-toggle="tab" aria-expanded="false"> Atasan Atasan Langsung </a>
                    </li>
                </ul>    
                <style>
                    .tab-content > .active {
                        display: table;                       
                    }    
                </style>    
                <div class="tab-content">
                    <div class="tab-pane active" id="atasan-langsung">
                    
                        <div class="control-group col-sm-12">
                            <label for="ATASAN_LANGSUNG_PNS_ID" class="control-label">CARI DARI DAFTAR PEGAWAI</label>
                            <div class='controls'>
                                <select id="ATASAN_LANGSUNG_PNS_ID" name="ATASAN_LANGSUNG_PNS_ID" width="100%" class=" col-md-10 format-control"></select>
                                <span class='help-inline'><?php echo form_error('ATASAN_LANGSUNG_PNS_ID'); ?></span>
                            </div>
                        </div>
                        <div class="control-group col-sm-12">
                            <label for="ATASAN_LANGSUNG_PNS_NIP" class="control-label">NIP ATASAN LANGSUNG</label>
                            <div class='controls'>
                                <input id='ATASAN_LANGSUNG_PNS_NIP' type='text' class="form-control" name='ATASAN_LANGSUNG_PNS_NIP' maxlength='32' value="<?php echo set_value('ATASAN_LANGSUNG_PNS_NIP', isset($detail_riwayat->ATASAN_LANGSUNG_PNS_NIP) ? $detail_riwayat->ATASAN_LANGSUNG_PNS_NIP : ''); ?>" />
                                <span class='help-inline'><?php echo form_error('ATASAN_LANGSUNG_PNS_NIP'); ?></span>
                            </div>
                        </div>
                        <div class="control-group col-sm-12">
                            <label for="ATASAN_LANGSUNG_PNS_NAMA" class="control-label">NAMA ATASAN LANGSUNG</label>
                            <div class='controls'>
                                <input id='ATASAN_LANGSUNG_PNS_NAMA' type='text' class="form-control" name='ATASAN_LANGSUNG_PNS_NAMA' maxlength='32' value="<?php echo set_value('ATASAN_LANGSUNG_PNS_NAMA', isset($detail_riwayat->ATASAN_LANGSUNG_PNS_NAMA) ? $detail_riwayat->ATASAN_LANGSUNG_PNS_NAMA : ''); ?>" />
                                <span class='help-inline'><?php echo form_error('ATASAN_LANGSUNG_PNS_NAMA'); ?></span>
                            </div>
                        </div>
                        <div class="control-group col-sm-12">
                            <label for="ATASAN_LANGSUNG_PNS_JABATAN" class="control-label">JABATAN ATASAN LANGSUNG</label>
                            <div class='controls'>
                                <!--<input id='ATASAN_LANGSUNG_PNS_JABATAN' type='text' class="form-control" name='ATASAN_LANGSUNG_PNS_JABATAN' maxlength='32' value="<?php //echo set_value('NILAI_PERILAKU_AKHIR', isset($detail_riwayat->ATASAN_LANGSUNG_PNS_JABATAN) ? $detail_riwayat->ATASAN_LANGSUNG_PNS_JABATAN : ''); ?>" />-->
                                <textarea id='ATASAN_LANGSUNG_PNS_JABATAN' class="form-control" maxlength='255' name='ATASAN_LANGSUNG_PNS_JABATAN'><?php echo set_value('NILAI_PERILAKU_AKHIR', isset($detail_riwayat->ATASAN_LANGSUNG_PNS_JABATAN) ? $detail_riwayat->ATASAN_LANGSUNG_PNS_JABATAN : ''); ?></textarea>
                                <span class='help-inline'><?php echo form_error('ATASAN_LANGSUNG_PNS_JABATAN'); ?></span>
                            </div>
                        </div>
                    </div>  
                    <div class="tab-pane " id="atasan-atasan-langsung">
                        <div class="control-group col-sm-12">
                            <label for="ATASAN_ATASAN_LANGSUNG_PNS_ID" class="control-label">CARI DARI DAFTAR PEGAWAI</label>
                            <div class='controls'>
                                <select id="ATASAN_ATASAN_LANGSUNG_PNS_ID" name="ATASAN_ATASAN_LANGSUNG_PNS_ID" width="100%" class=" col-md-10 format-control"></select>
                                <span class='help-inline'><?php echo form_error('ATASAN_ATASAN_LANGSUNG_PNS_ID'); ?></span>
                            </div>
                        </div>
                         <div class="control-group col-sm-12">
                                <label for="ATASAN_ATASAN_LANGSUNG_PNS_NIP" class="control-label">NIP ATASAN ATASAN LANGSUNG</label>
                                <div class='controls'>
                                    <input id='ATASAN_ATASAN_LANGSUNG_PNS_NIP' type='text' class="form-control" name='ATASAN_ATASAN_LANGSUNG_PNS_NIP' maxlength='32' value="<?php echo set_value('ATASAN_ATASAN_LANGSUNG_PNS_NIP', isset($detail_riwayat->ATASAN_ATASAN_LANGSUNG_PNS_NIP) ? $detail_riwayat->ATASAN_ATASAN_LANGSUNG_PNS_NIP : ''); ?>" />
                                    <span class='help-inline'><?php echo form_error('ATASAN_ATASAN_LANGSUNG_PNS_NIP'); ?></span>
                                </div>
                            </div>
                            <div class="control-group col-sm-12">
                                <label for="ATASAN_ATASAN_LANGSUNG_PNS_NAMA" class="control-label">NAMA ATASAN ATASAN LANGSUNG</label>
                                <div class='controls'>
                                    <input id='ATASAN_ATASAN_LANGSUNG_PNS_NAMA' type='text' class="form-control" name='ATASAN_ATASAN_LANGSUNG_PNS_NAMA' maxlength='32' value="<?php echo set_value('ATASAN_ATASAN_LANGSUNG_PNS_NAMA', isset($detail_riwayat->ATASAN_ATASAN_LANGSUNG_PNS_NAMA) ? $detail_riwayat->ATASAN_ATASAN_LANGSUNG_PNS_NAMA : ''); ?>" />
                                    <span class='help-inline'><?php echo form_error('ATASAN_ATASAN_LANGSUNG_PNS_NAMA'); ?></span>
                                </div>
                            </div>
                            <div class="control-group col-sm-12">
                                <label for="ATASAN_ATASAN_LANGSUNG_PNS_JABATAN" class="control-label">JABATAN ATASAN ATASAN LANGSUNG</label>
                                <div class='controls'>
                                    <!--<input id='ATASAN_ATASAN_LANGSUNG_PNS_JABATAN' type='text' class="form-control" name='ATASAN_ATASAN_LANGSUNG_PNS_JABATAN' maxlength='32' value="<?php //echo set_value('ATASAN_ATASAN_LANGSUNG_PNS_JABATAN', isset($detail_riwayat->ATASAN_ATASAN_LANGSUNG_PNS_JABATAN) ? $detail_riwayat->ATASAN_ATASAN_LANGSUNG_PNS_JABATAN : ''); ?>" />-->
                                    <textarea id='ATASAN_ATASAN_LANGSUNG_PNS_JABATAN' class="form-control" maxlength='255' name='ATASAN_ATASAN_LANGSUNG_PNS_JABATAN'><?php echo set_value('ATASAN_ATASAN_LANGSUNG_PNS_JABATAN', isset($detail_riwayat->ATASAN_ATASAN_LANGSUNG_PNS_JABATAN) ? $detail_riwayat->ATASAN_ATASAN_LANGSUNG_PNS_JABATAN : ''); ?></textarea>
                                    <span class='help-inline'><?php echo form_error('ATASAN_ATASAN_LANGSUNG_PNS_JABATAN'); ?></span>
                                </div>
                            </div>
                    </div>  
                </div>      
            </div>    	
        </div>
  		<div class="box-footer">
            <input type='submit' name='save' id="btnsaveskp" class='btn btn-primary' value="Simpan Data" /> 
        </div>
    <?php echo form_close(); ?>
</div>
<script src="<?php echo base_url(); ?>themes/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
<script>
    var form = $("#form-diklat-fungsional-add");
    //Date picker
    $('.datepicker').datepicker({
      autoclose: true,format: 'yyyy-mm-dd'
    }).on("input change", function (e) {
        var date = $(this).datepicker('getDate');
        if(date)$("[name=TAHUN]",form).val(date.getFullYear());
    });
</script>
<script>
    $.fn.modal.Constructor.prototype.enforceFocus = function() {};
	var pembagi = 6;
    $("#JABATAN_TIPE").change(function(){
        if($(this).val()==1){
        	pembagi = 6;
            $("#PERILAKU_KEPEMIMPINAN").val(0);
            $("#PERILAKU_KEPEMIMPINAN").attr("READONLY",false);
        }
        else {
        	pembagi = 5;
            $("#PERILAKU_KEPEMIMPINAN").val(0);
            $("#PERILAKU_KEPEMIMPINAN").attr("READONLY",true);
        }
        calcPerilaku();
    });
    
    function calcPerilaku(){
    	var nilaiskp = Number($("#NILAI_SKP_AKHIR").val());
         var total = Number($("#PERILAKU_KOMITMEN").val())+
         Number($("#PERILAKU_INTEGRITAS").val())+
         Number($("#PERILAKU_DISIPLIN").val())+
         Number($("#PERILAKU_KERJASAMA").val())+
         Number($("#PERILAKU_ORIENTASI_PELAYANAN").val())+
         Number($("#PERILAKU_KEPEMIMPINAN").val());
         var totalperilaku = (total/pembagi);
         $("#NILAI_PERILAKU").val(totalperilaku);
         var NILAI_PERILAKU_AKHIR = totalperilaku*40/100;
         $("#NILAI_PERILAKU_AKHIR").val(NILAI_PERILAKU_AKHIR);
         var totalall = Number(NILAI_PERILAKU_AKHIR) + Number(nilaiskp);
         $("#NILAI_PPK").val(totalall);
         
    }
     $("#PERILAKU_KOMITMEN").keyup(function(){
         calcPerilaku();
     });
     $("#PERILAKU_INTEGRITAS").keyup(function(){
         calcPerilaku();
     });
     $("#PERILAKU_DISIPLIN").keyup(function(){
         calcPerilaku();
     });
     $("#PERILAKU_KERJASAMA").keyup(function(){
         calcPerilaku();
     });
     $("#PERILAKU_ORIENTASI_PELAYANAN").keyup(function(){
         calcPerilaku();
     });
     $("#PERILAKU_KEPEMIMPINAN").keyup(function(){
         calcPerilaku();
     });
    $("#NILAI_SKP").keyup(function(){
        $("#NILAI_SKP_AKHIR").val($("#NILAI_SKP").val()*60/100);
    });
    
    $("#ATASAN_LANGSUNG_PNS_ID").select2({
        placeholder: 'Cari Atasan Langsung...',
        width: '100%',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: '<?php echo site_url("pegawai/riwayatprestasikerja/get_atasan_langsung_ajax");?>',
            dataType: 'json',
            data: function(params) {
                return {
                    term: params.term || '',
                    page: params.page || 1
                }
            },
            cache: true
        }
    }).change(function(){
        if($(this).select2('data').length==1){
            $("#ATASAN_LANGSUNG_PNS_NIP").val($(this).select2('data')[0]['NIP_BARU']);
            $("#ATASAN_LANGSUNG_PNS_JABATAN").val($(this).select2('data')[0]['JABATAN_NAMA']);
            $("#ATASAN_LANGSUNG_PNS_NAMA").val($(this).select2('data')[0]['NAMA']);
        }
        else {
            $("#ATASAN_LANGSUNG_PNS_NIP").val('');
            $("#ATASAN_LANGSUNG_PNS_JABATAN").val('');
            $("#ATASAN_LANGSUNG_PNS_NAMA").val('');
        }
       
    });
    $("#ATASAN_ATASAN_LANGSUNG_PNS_ID").select2({
        placeholder: 'Cari Atasan Atasan Langsung...',
        width: '100%',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: '<?php echo site_url("pegawai/riwayatprestasikerja/get_atasan_langsung_ajax");?>',
            dataType: 'json',
            data: function(params) {
                return {
                    term: params.term || '',
                    page: params.page || 1
                }
            },
            cache: true
        }
    }).change(function(){
       if($(this).select2('data').length==1){
            $("#ATASAN_ATASAN_LANGSUNG_PNS_NIP").val($(this).select2('data')[0]['NIP_BARU']);
            $("#ATASAN_ATASAN_LANGSUNG_PNS_JABATAN").val($(this).select2('data')[0]['JABATAN_NAMA']);
            $("#ATASAN_ATASAN_LANGSUNG_PNS_NAMA").val($(this).select2('data')[0]['NAMA']);
        }
        else {
            $("#ATASAN_ATASAN_LANGSUNG_PNS_NIP").val('');
            $("#ATASAN_ATASAN_LANGSUNG_PNS_JABATAN").val('');
            $("#ATASAN_ATASAN_LANGSUNG_PNS_NAMA").val('');
        }
       
    });
    
    $("#ID_INSTANSI").select2({
        placeholder: 'Cari Data Instansi..',
        width: '100%',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: '<?php echo site_url("pegawai/riwayatprestasikerja/get_instansi_ajax");?>',
            dataType: 'json',
            data: function(params) {
                return {
                    term: params.term || '',
                    page: params.page || 1
                }
            },
            cache: true
        }
    });
	$("#btnsaveskp").click(function(){
		submitdataskp();
		return false; 
	});	
	$("#frmskp").submit(function(){
		submitdataskp();
		return false; 
	});	
	function submitdataskp(){
		
		var json_url = "<?php echo base_url() ?>pegawai/riwayatprestasikerja/save";
		 $.ajax({    
		 	type: "POST",
			url: json_url,
			data: $("#frmskp").serialize(),
            dataType: "json",
			success: function(data){ 
                if(data.success){
                    swal("Pemberitahuan!", data.msg, "success");
                    $("#modal-custom-global").trigger("sukses-tambah-riwayat-pindah_unit_kerja");
					 $("#modal-custom-global").modal("hide");
                }
                else {
                    $("#form-diklat-fungsional-add .messages").empty().append(data.msg);
                }
			}});
		return false; 
	}
</script>