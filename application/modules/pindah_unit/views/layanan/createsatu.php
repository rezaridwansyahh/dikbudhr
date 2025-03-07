<script src="<?php echo base_url(); ?>themes/admin/js/jqueryvalidation/jquery.validate.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/css/dropzone/dropzone.min.css">
<script src="<?php echo base_url(); ?>themes/admin/js/dropzone/dropzone.min.js"></script>
<?php

if (validation_errors()) :
?>
<div class='alert alert-block alert-error fade in'>
    <a class='close' data-dismiss='alert'>&times;</a>
    <h4 class='alert-heading'>
        <?php echo lang('pindah_unit_errors_message'); ?>
    </h4>
    <?php echo validation_errors(); ?>
</div>
<?php
endif;

$id = isset($pindah_unit->ID) ? $pindah_unit->ID : '';

?>
<div class="callout callout-info">
   <h4>Perhatian!</h4>
   <p>
   Perpindahan pegawai dari bagian satu kebagian lain dalam satu SATKER
 </p>
 </div>
<div class='messages'></div>
<div class='box box-primary float-e-margins'>
    <div class="box-body ibox-content">
        <?php echo form_open($this->uri->uri_string(), 'id="frmpindah"'); ?>
            <fieldset>
                <div class="control-group<?php echo form_error('NIP') ? ' error' : ''; ?> col-sm-12">
                    <?php echo form_label("Pegawai" . lang('bf_form_label_required'), 'NIP', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <select name="NIP" id="NIP" class="form-control select2">
                            <?php 
                                if($selectedpegawai){
                                    echo "<option selected value='".$selectedpegawai->PNS_ID."'>".$selectedpegawai->NAMA."</option>";
                                }
                            ?>
                        </select>
                        <span class='help-inline'><?php echo form_error('NIP'); ?></span>
                    </div>
                </div>
                <div class="control-group<?php echo form_error('UNIT_TUJUAN') ? ' error' : ''; ?> col-sm-6">
                    <?php echo form_label("Dari", 'UNIT_TUJUAN', array('class' => 'control-label')); ?>
                    <div class='controls'>
                         
                    </div>
                </div>
                <div class="control-group<?php echo form_error('UNIT_TUJUAN') ? ' error' : ''; ?> col-sm-6">
                    <?php echo form_label(lang('pindah_unit_field_UNIT_TUJUAN'), 'UNIT_TUJUAN', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <select name="UNIT_TUJUAN" id="UNIT_TUJUAN" class="form-control select2">
                            <?php 
                                if($selectedUnitTujuan){
                                    echo "<option selected value='".$selectedUnitTujuan->ID."'>".$selectedUnitTujuan->NAMA_ESELON_II."</option>";
                                }
                            ?>
                        </select>
                        <span class='help-inline'><?php echo form_error('UNIT_TUJUAN'); ?></span>
                    </div>
                </div>
                 
                <?php if ($this->auth->has_permission('Pindah_unit.Layanan.Biro') or $this->auth->has_permission('Pindah_unit.Layanan.UnitTujuan')) { ?>
                <div class="control-group<?php echo form_error('NO_SK_PINDAH') ? ' error' : ''; ?> col-sm-6">
                    <?php echo form_label(lang('pindah_unit_field_NO_SK_PINDAH'), 'NO_SK_PINDAH', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <input id='NO_SK_PINDAH' class="form-control" type='text' name='NO_SK_PINDAH' maxlength='100' value="<?php echo set_value('NO_SK_PINDAH', isset($pindah_unit->NO_SK_PINDAH) ? $pindah_unit->NO_SK_PINDAH : ''); ?>" />
                        <span class='help-inline'><?php echo form_error('NO_SK_PINDAH'); ?></span>
                    </div>
                </div>
                <div class="control-group col-sm-6">
                    <label for="inputNAMA" class="control-label">TANGGAL SK PINDAH</label>
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                        <input type='text' class="form-control pull-right datepicker" name='TANGGAL_SK_PINDAH'  value="<?php echo set_value('TANGGAL_SK_PINDAH', isset($detail_riwayat->TANGGAL_SK_PINDAH) ? $detail_riwayat->TANGGAL_SK_PINDAH : ''); ?>" />
                        <span class='help-inline'><?php echo form_error('TANGGAL_SK_PINDAH'); ?></span>
                    </div>
                </div> 
                <div class="control-group<?php echo form_error('FILE_SK') ? ' error' : ''; ?> col-sm-12">
                    <?php echo form_label(lang('pindah_unit_field_FILE_SK'), 'SURAT_PERNYATAAN_MENERIMA', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <div class="dropzone well well-sm drfilesk">
                        </div>
                    </div>
                    <div class="filefilesk">
                        <div class="input-group divfilesk">
                           <div class="input-group-addon">File</div>
                           <input type="text" name="FILE_SK" value="<?php echo $pindah_unit->FILE_SK;?>" id="FILE_SK" class="form-control just-upload-field" />
                           <div class="input-group-addon"><a href="<?php echo $this->settings_lib->item('site.urluploaded'); ?><?php echo $pindah_unit->FILE_SK;?>" target="_blank"><i class="fa fa-mail-forward"></i></a></div>
                           <span class="input-group-btn">
                             <button type="button" kode="<?php echo $id; ?>" kolom="FILE_SK" kodediv="divfilesk" class="btn btn-danger btn-flat alertdelete">X</button>
                           </span>
                        </div>
                   </div>
                </div>
                <?php } ?>
            </fieldset>
            </div>
            <div class="box-footer">
                <input type='submit' name='save' id="btnsave" class='btn btn-primary' value="SIMPAN" />
                <?php echo lang('bf_or'); ?>
                <?php echo anchor(SITE_AREA . '/layanan/pindah_unit', lang('pindah_unit_cancel'), 'class="btn btn-warning"'); ?>
                
            </div>
        <?php echo form_close(); ?>
    </div>
</div>
<script src="<?php echo base_url(); ?>themes/admin/plugins/select2/select2.full.min.js"></script>

<script>
     $(".select2").select2();
</script>
<script>
    $('.datepicker').datepicker({
      autoclose: true,format: 'yyyy-mm-dd'
    });
    Dropzone.autoDiscover = false;
    

     var drfilesk = new Dropzone(".drfilesk",{
     autoProcessQueue: true,
     url: "<?php echo base_url() ?>admin/layanan/pindah_unit/uploadberkas",
     maxFilesize: 20,
     parallelUploads : 10,
     method:"post",
     acceptedFiles:"application/pdf",
     paramName:"userfile",
     dictDefaultMessage:"<img src='<?php echo base_url(); ?>assets/images/dropico.png' width='50px'/><br>Drop File SK pindah unit (jika diterima)",
     dictInvalidFileType:"Type file ini tidak dizinkan",
     addRemoveLinks:true
     });
     drfilesk.on("sending",function(a,b,c){
         a.token=Math.random();
         c.append('kolom',"FILE_SK");
         c.append('kode',"<?php echo isset($id) ? $id : ''; ?>");
         //console.log('mengirim');           
     });
     drfilesk.on("success",function(a,response){
        var obj = jQuery.parseJSON(response)
        $("#FILE_SK").val(obj.namafile);
        $('#FILE_SK').attr('readonly', true);

         drfilesk.removeAllFiles(true);
     });

    $("#NIP").select2({
        placeholder: 'Cari Pegawai...',
        width: '100%',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: '<?php echo site_url("admin/kepegawaian/pegawai/ajax");?>',
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
    $("#UNIT_TUJUAN").select2({
        placeholder: 'Cari Unit Kerja...',
        width: '100%',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: '<?php echo site_url("pegawai/manage_unitkerja/ajax");?>',
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
    $("#frmpindah").validate({
        submitHandler: function(form) {
        //$("#btnsave").val('Menyimpan Data......').attr('disabled', true).addClass('bt-hud').unbind('click');
        submitdata();
      },
      rules: {
        NIP: {
          required: false
        },
        UNIT_TUJUAN: {
            required: true
        },
        

      },errorElement: "span",
        errorPlacement: function ( error, element ) {
            // Add the `help-block` class to the error element
            error.addClass( "help-block" );

            // Add `has-feedback` class to the parent div.form-group
            // in order to add icons to inputs

            if ( element.prop( "type" ) === "checkbox" ) {
                error.insertAfter( element.parent( "label" ) );
            } else {
                error.insertAfter( element );
            }

            // Add the span element, if doesn't exists, and apply the icon classes to it.
            if ( !element.next( "span" )[ 0 ] ) {
            }
        },
        success: function ( label, element ) {
            // Add the span element, if doesn't exists, and apply the icon classes to it.
            if ( !$( element ).next( "span" )[ 0 ] ) {
            }
        },
        highlight: function ( element, errorClass, validClass ) {
            $( element ).parents( ".control-group" ).addClass( "has-error" ).removeClass( "has-success" );
        },
        unhighlight: function ( element, errorClass, validClass ) {
            $( element ).parents( ".col-sm-5" ).addClass( "has-success" ).removeClass( "has-error" );
        }


    });
    function submitdata(){
        
        var json_url = "<?php echo base_url() ?>admin/layanan/pindah_unit/saveajuan";
         $.ajax({    
            type: "post",
            url: json_url,
            data: $("#frmpindah").serialize(),
            dataType: "json",
            success: function(data){ 
                if(data.success){
                    swal("Pemberitahuan!", data.msg, "success");
                    $("#btnsave").val('SIMPAN').attr('disabled', false).addClass('bt-hud').unbind('click');
                }
                else {
                    $(".messages").empty().append(data.msg);
                }
            }});
        return false; 
    }
</script>