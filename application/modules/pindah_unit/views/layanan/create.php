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
   <p>Silahkan lengkapi data dan lengkapi berkas-berkas yang diperlukan seperti :<br>
    1. SURAT PERMOHONAN PINDAH<br>
    2. SURAT PERNYATAAN MELEPAS<br>
    3. SK KP TERAKHIR<br>
    4. SK JABATAN<br>
    5. PPK (PENILAIAN PRESTASI KERJA)<br>
    6. SK TUNKIN <br>
    7. SURAT PERNYATAAN MENERIMA<br>
   Pengisian Form Pengajuan Pindah Unit Kerja bisa dilakukan Unit kerja Tujuan pindah Pegawai<br>
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
                          <option value="">-- Silahkan Pilih --</option>
                            <?php 
                                if($selectedpegawai){
                                    echo "<option selected value='".$selectedpegawai->PNS_ID."'>".$selectedpegawai->NAMA."</option>";
                                }
                            ?>
                        </select>
                        <span class='help-inline'><?php echo form_error('NIP'); ?></span>
                    </div>
                </div>
                <div class="divinfo">
                </div>
                <div class="control-group<?php echo form_error('ID_SATUAN_KERJA') ? ' error' : ''; ?> col-sm-12">
                    <?php echo form_label('SATUAN KERJA', 'ID_SATUAN_KERJA', array('class' => 'control-label')); ?>
                    <div class='controls'>
                      <select name="ID_SATUAN_KERJA" id="ID_SATUAN_KERJA" class="form-control select2  col-sm-12" width="100%">
                          <option value="">-- Silahkan Pilih --</option>
                          <?php if (isset($recsatker) && is_array($recsatker) && count($recsatker)):?>
                          <?php foreach($recsatker as $record):?>
                            <option value="<?php echo $record->ID?>" <?php if(isset($detail_riwayat->ID_SATUAN_KERJA))  echo  (trim($detail_riwayat->ID_SATUAN_KERJA)==trim($record->ID)) ? "selected" : ""; ?>><?php echo $record->NAMA_UNOR; ?></option>
                            <?php endforeach;?>
                          <?php endif;?>
                          <option value="N">Tidak ada</option>
                        </select>
                        <span class='help-inline'><?php echo form_error('ID_SATUAN_KERJA'); ?></span>
                    </div>
                </div>
                <div class="control-group<?php echo form_error('UNIT_TUJUAN') ? ' error' : ''; ?> col-sm-12">
                    <?php echo form_label(lang('pindah_unit_field_UNIT_TUJUAN'), 'UNIT_TUJUAN', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <select name="UNIT_TUJUAN" id="UNIT_TUJUAN" class="form-control select2">
                          <option value="">-- Silahkan Pilih --</option>
                            <?php 
                                if($selectedUnitTujuan){
                                    echo "<option selected value='".$selectedUnitTujuan->ID."'>".$selectedUnitTujuan->NAMA_ESELON_II."</option>";
                                }
                            ?>
                        </select>
                        <span class='help-inline'><?php echo form_error('UNIT_TUJUAN'); ?></span>
                    </div>
                </div>
                <div class="control-group<?php echo form_error('JABATAN_ID') ? ' error' : ''; ?> col-sm-12">
                    <?php echo form_label("Jabatan Tujuan", 'JABATAN_ID', array('class' => 'control-label')); ?>
                    <div class='controls'>
                       <select name="JABATAN_ID" id="JABATAN_ID" class="form-control select2">
                          <option value="">-- Silahkan Pilih --</option>
                            <?php 
                                if($selectedJabatanInstansiID){
                                    echo "<option selected value='".$selectedJabatanInstansiID->KODE_JABATAN."'>".$selectedJabatanInstansiID->NAMA_JABATAN."</option>";
                                }
                            ?>
                           
                        </select>
                    </div>
                </div>
                <div class="divquota">
                </div>
            </fieldset>
            </div>
            <div class="box-footer">
                <input type='submit' name='save' id="btnsave" class='btn btn-primary' value="SIMPAN" />
                <?php echo lang('bf_or'); ?>
                <?php echo anchor(SITE_AREA . '/layanan/pindah_unit/unittujuan', lang('pindah_unit_cancel'), 'class="btn btn-warning"'); ?>
                
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
    $("#ID_SATUAN_KERJA1").select2({
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
    $("#JABATAN_ID").select2({
        placeholder: 'Cari Jabatan...',
        width: '100%',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: '<?php echo site_url("admin/masters/ref_jabatan/ajax");?>',
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
    $( "#JABATAN_ID").change(function() {
      cekquota();
    });
    $( "#UNIT_TUJUAN").change(function() {
      cekquota();
    });
    /*
    $( "#ID_SATUAN_KERJA").change(function() {
      cekquota();
    });
    */
    function cekquota(){
      var valinstansi_id = $("#UNIT_TUJUAN").val();
      if(valinstansi_id == ""){
        valinstansi_id = $("#ID_SATUAN_KERJA").val();  
      }
      
      
      var valJABATAN_ID = $("#JABATAN_ID").val();
      if(valinstansi_id != "" && valJABATAN_ID != ""){
        var json_url = "<?php echo base_url() ?>admin/reports/petajabatan/getquota/";
         $.ajax({    
            type: "post",
            url: json_url,
            data: "unitkerja="+valinstansi_id+"&jabatan_instansi="+valJABATAN_ID,
            dataType: "html",
            success: function(data){ 
                $(".divquota").empty().append(data);
        }});
      }
    }
    $( "#NIP").change(function() {
      var valPNS_ID = $("#NIP").val();
      var json_url = "<?php echo base_url() ?>admin/kepegawaian/pegawai/getinfounit";
       $.ajax({    
          type: "post",
          url: json_url,
          data: "PNS_ID="+valPNS_ID,
          dataType: "html",
          success: function(data){ 
              $(".divinfo").empty().append(data);
      }});
    });
    $('#ID_SATUAN_KERJA').change(function() {
      var valuesatker = $('#ID_SATUAN_KERJA').val();
          $("#UNIT_TUJUAN").empty().append("<option>loading...</option>"); //show loading...
          // ambil satker kode return kode internal
          var json_url = "<?php echo base_url(); ?>pegawai/manage_unitkerja/getbysatker?satker=" + encodeURIComponent(valuesatker);
          $.getJSON(json_url,function(data){
            $("#UNIT_TUJUAN").empty(); 
            if(data==""){
              $("#UNIT_TUJUAN").append("<option value=\"\">Silahkan Pilih </option>");
            }
            else{
              $("#UNIT_TUJUAN").append("<option value=\"\">Silahkan Pilih</option>");
              for(i=0; i<data.id.length; i++){
                $("#UNIT_TUJUAN").append("<option value=\"" + data.id[i]  + "\">" + data.nama[i] +"</option>");
              }
            }
            
          });
          //$("#UNIT_TUJUAN").select2("updateResults");
      });

    $(".btnsave").attr('disabled', true);
</script>