<link href="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/css/fileinput.css" media="all" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/themes/explorer/theme.css" media="all" rel="stylesheet" type="text/css"/>


<?php

if (validation_errors()) :
?>
<div class='alert alert-block alert-error fade in'>
    <a class='close' data-dismiss='alert'>&times;</a>
    <h4 class='alert-heading'>
        <?php echo lang('tte_errors_message'); ?>
    </h4>
    <?php echo validation_errors(); ?>
</div>
<?php
endif;

$id = isset($tte->id) ? $tte->id : '';

?>
<div class='admin-box box box-primary'>
    <div class="box-header">
        <h3>Tambah Master Proses TTE</h3>
    </div>        
           
    <div class="box-body">
    <?php echo form_open($this->uri->uri_string(),"id=submit_form","form"); ?>
        <fieldset>
            

            <div class="control-group<?php echo form_error('nama_proses') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label(lang('tte_field_nama_proses') . lang('bf_form_label_required'), 'nama_proses', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='id' type='hidden' class="form-control" name='id' maxlength='100' value="<?php echo set_value('id', isset($tte->id) ? $tte->id : ''); ?>" />
                    <input id='nama_proses' type='text' required='required' class="form-control" name='nama_proses' maxlength='100' value="<?php echo set_value('nama_proses', isset($tte->nama_proses) ? $tte->nama_proses : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('nama_proses'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('penandatangan_sk') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label(lang('tte_field_penandatangan_sk'), 'penandatangan_sk', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select id="penandatangan_sk" name="penandatangan_sk" width="100%" class="form-control select2">
                        <?php
                        if($apenandatangan_sk){
                            echo "<option selected value='".$apenandatangan_sk->PNS_ID."'>".$apenandatangan_sk->NAMA."</option>";
                        }
                        ?>
                    </select>
                    <span class='help-inline'>Default Penandatangan</span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('keterangan_proses') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label(lang('tte_field_keterangan_proses'), 'keterangan_proses', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <textarea name='keterangan_proses' class="form-control"><?php echo set_value('keterangan_proses', isset($tte->keterangan_proses) ? $tte->keterangan_proses : ''); ?></textarea> 
                    <span class='help-inline'><?php echo form_error('keterangan_proses'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('template_sk') ? ' error' : ''; ?> col-sm-10">
                <?php echo form_label(lang('tte_field_template_sk'), 'template_sk', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <div id="form_upload">
                        <input id="template_sk" name="template_sk" class="file" type="file" data-preview-file-type="pdf" title="Silahkan Pilih file">
                      </div>
                    
                    <span class='help-inline'>Template file dokumen (.doc)</span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('template_sk') ? ' error' : ''; ?> col-sm-2">
                <?php echo form_label("<br>", 'template_sk', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <a href="<?php echo trim($this->settings_lib->item('site.urlsk'))."template/".$tte->template_sk; ?>" class="btn btn-warning">Lihat dokumen</a>
                </div>
            </div>
        </fieldset>
        <br>
        <fieldset id="formvariable">
            <legend>
                Variable 
            </legend>
            <?php if (isset($proses_variable) && is_array($proses_variable) && count($proses_variable)):?>
                <?php $no = 1; ?>
                <?php foreach($proses_variable as $recordvar):?>
                    <div class="control-group <?php echo form_error('variable') ? ' error' : ''; ?> col-sm-8" id="div_<?php echo $no; ?>">
                        <?php echo form_label("Variable ".$no, 'variable1', array('class' => 'control-label')); ?>
                        <div class='controls'>
                            <select name="variable[]" width="100%" class="form-control select2">
                                <option value="">-- Silahkan Pilih --</option>
                                <?php if (isset($recordvariables) && is_array($recordvariables) && count($recordvariables)):?>
                                <?php foreach($recordvariables as $record):?>
                                    <option value="<?php echo $record->id?>" <?php if(isset($recordvar->id_variable))  echo  ($recordvar->id_variable == $record->id) ? "selected" : ""; ?>><?php echo $record->label_variable; ?></option>
                                    <?php endforeach;?>
                                <?php endif;?>
                            </select>
                        </div>
                    </div>
                    <div class="control-group<?php echo form_error('variable') ? ' error' : ''; ?> col-sm-2" id="div2_<?php echo $no; ?>">
                        <?php echo form_label("<br>", 'variable1', array('class' => 'control-label')); ?>
                        <div class='controls'>
                             <span class='btn btn-danger' kodediv="<?php echo $no; ?>" id="delvariable" href="#"><i class="fa fa-min"></i>Delete</span>
                            <?php if($no==1){ ?>
                             <span class='btn btn-warning pull-right' id="addvariable" href="#"><i class="fa fa-plus"></i>Tambah</span>
                             <?php } ?>
                        </div>
                    </div>
                <?php 
                $no++;
                endforeach;?>
            <?php 
            else:
            ?>
            <div class="control-group<?php echo form_error('variable') ? ' error' : ''; ?> col-sm-8">
                <?php echo form_label("Variable ".$no, 'variable1', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select name="variable[]" id="initselect" width="100%" class="form-control select2">
                        <option value="">-- Silahkan Pilih --</option>
                        <?php if (isset($recordvariables) && is_array($recordvariables) && count($recordvariables)):?>
                        <?php foreach($recordvariables as $record):?>
                            <option value="<?php echo $record->id?>" <?php if(isset($recordvar->id_variable))  echo  ($recordvar->id_variable == $record->id) ? "selected" : ""; ?>><?php echo $record->label_variable; ?></option>
                            <?php endforeach;?>
                        <?php endif;?>
                    </select>
                </div>
            </div>
            <div class="control-group<?php echo form_error('variable') ? ' error' : ''; ?> col-sm-2">
                <?php echo form_label("<br>", 'variable1', array('class' => 'control-label')); ?>
                <div class='controls'>
                     <span class='btn btn-warning' id="addvariable" href="#"><i class="fa fa-plus"></i>Tambah</span>
                </div>
            </div>
            <?php
            endif;?>
             
        </fieldset>
        <br>
        <fieldset id="formveririkator">
            <legend>
                Verifikator
            </legend>
            <?php $no = 1; ?>
            <?php if (isset($verifikators) && is_array($verifikators) && count($verifikators)):?>
                
                <?php foreach($verifikators as $recordvar):?>
                    <div class="control-group <?php echo form_error('verifikator') ? ' error' : ''; ?> col-sm-8" id="divv_<?php echo $no; ?>">
                        <?php echo form_label("Verifikator ".$no, 'verifikator', array('class' => 'control-label')); ?>
                        <div class='controls'>
                            <select name="verifikator[]" width="100%" class="form-control select2 class_verifikator">
                                <option value="">-- Silahkan Pilih --</option>
                                 <?php
                                    echo "<option selected value='".$recordvar->PNS_ID."'>".TRIM($recordvar->NAMA)."</option>";
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="control-group<?php echo form_error('verifikator') ? ' error' : ''; ?> col-sm-2" id="divv2_<?php echo $no; ?>">
                        <?php echo form_label("<br>", 'verifikator', array('class' => 'control-label')); ?>
                        <div class='controls'>
                             <span class='btn btn-danger' kodediv="<?php echo $no; ?>" id="delverifikator" href="#"><i class="fa fa-min"></i>Delete</span>
                            <?php if($no==1){ ?>
                             <span class='btn btn-warning pull-right' id="addverifikator" href="#"><i class="fa fa-plus"></i>Tambah</span>
                             <?php } ?>
                        </div>
                    </div>
                <?php 
                $no++;
                endforeach;?>
            <?php 
            else:
            ?>
            <div class="control-group<?php echo form_error('verifikator') ? ' error' : ''; ?> col-sm-8">
                <?php echo form_label("verifikator ".$no, 'verifikator', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select name="verifikator[]" width="100%" class="form-control select2 class_verifikator">
                        <option value="">-- Silahkan Pilih --</option>
                        
                    </select>
                </div>
            </div>
            <div class="control-group<?php echo form_error('verifikator') ? ' error' : ''; ?> col-sm-2">
                <?php echo form_label("<br>", 'verifikator', array('class' => 'control-label')); ?>
                <div class='controls'>
                     <span class='btn btn-warning' id="addverifikator" href="#"><i class="fa fa-plus"></i>Tambah</span>
                </div>
            </div>
            <?php
            endif;?>
        </fieldset>
        </div>
            <div class="box-footer">
            <input type='button' name='save' id="btn_save" class='btn btn-primary' value="Simpan Proses TTE" />
            <?php echo lang('bf_or'); ?>
            <?php echo anchor(SITE_AREA . '/masters/tte', lang('tte_cancel'), 'class="btn btn-warning"'); ?>
            
        </div>
    <?php echo form_close(); ?>
</div>
<script src="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/themes/explorer/theme.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/js/fileinput.js" type="text/javascript"></script>

<script type="text/javascript">
  var nomor  = 1;
  var nomorv  = 1;
  function submitdata(){
    var the_data = new FormData(document.getElementById("submit_form"));
    $.ajax({
        url: "<?php echo base_url('admin/masters/tte/act_saveproses'); ?>",
        type: "POST",
        data: the_data,
        enctype: 'multipart/form-data',
        processData: false, // tell jQuery not to process the data
        contentType: false, // tell jQuery not to set contentType
        dataType: 'JSON',

        beforeSend: function (xhr) {
            //$("#loading-all").show();
        },
        success: function (response) {
            if(response.status){
                swal("Sukses",response.msg,"success");
                //window.location.href = "<?php echo base_url(); ?>admin/masters/tte";
            }else{
                swal("Ada kesalahan",response.msg,"error");
            }
        }
    });
    
    return false; 
  } 
$('body').on('click','#btn_save',function () { 
  submitdata();
});
 $("#penandatangan_sk").select2({
        placeholder: 'Cari Penandatangan.....',
        width: '100%',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: '<?php echo site_url("pegawai/kepegawaian/ajax_nama_pejabat");?>',
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
$(".class_verifikator").select2({
        placeholder: 'Cari Pegawai.....',
        width: '100%',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: '<?php echo site_url("pegawai/kepegawaian/ajax_nama_pejabat");?>',
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
$('body').on('click','#addvariable',function () { 

  var s = "<div class=\"control-group col-sm-8\"><label for=\"variable1\" class=\"control-label\">Variable </label><div class=\"controls\"><select name=\"variable[]\" id=\"select_"+nomor+"\" width=\"100%\" class=\"form-control select2\"></select></div></div>";
    $("#formvariable").append(s);

  addselect("select_"+nomor);
  nomor++;
});
$('body').on('click','#addverifikator',function () { 

  var s = "<div class=\"control-group col-sm-8\"><label for=\"variable1\" class=\"control-label\">Verifikator </label><div class=\"controls\"><select name=\"verifikator[]\" id=\"selectv_"+nomorv+"\" width=\"100%\" class=\"form-control select2 class_verifikator\"></select></div></div>";
    $("#formveririkator").append(s);

  $(".class_verifikator").select2({
        placeholder: 'Cari Pegawai.....',
        width: '100%',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: '<?php echo site_url("pegawai/kepegawaian/ajax_nama_pejabat");?>',
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
  nomorv++;
});
$('body').on('click','#delvariable',function () { 
    var kodediv =$(this).attr("kodediv");
    $("#div_"+kodediv).remove();
    $("#div2_"+kodediv).remove();

});
$('body').on('click','#delverifikator',function () { 
    var kodediv =$(this).attr("kodediv");
    $("#divv_"+kodediv).remove();
    $("#divv2_"+kodediv).remove();

});

addselect("initselect");
function addselect(idselect){
    $("#"+idselect).empty().append("<option>loading...</option>");
     
    var json_url = "<?php echo base_url(); ?>admin/masters/tte/getdatavariable";
    $.getJSON(json_url,function(data){
        $("#"+idselect).empty(); 
        if(data==""){
            $("#"+idselect).append("<option value=\"\">Pilih</option>");
        }
        else{
            $("#"+idselect).append("<option value=\"\">-- Pilih --</option>");
            for(i=0; i<data.id.length; i++){
                $("#"+idselect).append("<option value=\"" + data.id[i]  + "\">" + data.text[i] +"</option>");
            }
        }
        
    });
}

</script>