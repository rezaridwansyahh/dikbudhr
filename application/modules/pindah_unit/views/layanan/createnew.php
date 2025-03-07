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
   <p>Pengisian Form Pengajuan Pindah Unit Kerja bisa dilakukan oleh Unit Asal Pegawai atau Unit Tujuan Pegawai</p>
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
                                    echo "<option selected value='".$selectedpegawai->NIP."'>".$selectedpegawai->NAMA."</option>";
                                }
                            ?>
                        </select>
                        <span class='help-inline'><?php echo form_error('NIP'); ?></span>
                    </div>
                </div>

                <div class="control-group<?php echo form_error('UNIT_TUJUAN') ? ' error' : ''; ?> col-sm-12">
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
                <div class="control-group<?php echo form_error('SURAT_PERMOHONAN_PINDAH') ? ' error' : ''; ?> col-sm-6">
                    <?php echo form_label("SURAT PERMOHONAN PINDAH", 'SURAT_PERMOHONAN_PINDAH', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <div class="dropzone well well-sm drsuratpermohonan">
                        </div>
                    </div>
                    <div class="filesuratpermohonan">
                        <div class="input-group divsuratpermohonanpindah">
                           <div class="input-group-addon">File</div>
                           <input type="text" name="SURAT_PERMOHONAN_PINDAH" readonly value="<?php echo $pindah_unit->SURAT_PERMOHONAN_PINDAH;?>" id="SURAT_PERMOHONAN_PINDAH" class="form-control just-upload-field" />
                           <div class="input-group-addon"><a href="<?php echo $this->settings_lib->item('site.urluploaded'); ?><?php echo $pindah_unit->SURAT_PERMOHONAN_PINDAH;?>" target="_blank"><i class="fa fa-mail-forward"></i></a></div>
                           <span class="input-group-btn">
                             <button type="button" kode="<?php echo $id; ?>" kolom="SURAT_PERMOHONAN_PINDAH" kodediv="divsuratpermohonanpindah" class="btn btn-danger btn-flat alertdelete">X</button>
                           </span>
                        </div>
                   </div>
                </div>
                <div class="control-group<?php echo form_error('SURAT_PERMOHONAN_PINDAH') ? ' error' : ''; ?> col-sm-6">
                    <?php echo form_label(lang('pindah_unit_field_SURAT_PERNYATAAN_MELEPAS'), 'SURAT_PERNYATAAN_MELEPAS', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <div class="dropzone well well-sm drsuratmelepas">
                        </div>
                    </div>
                    <div class="filesuratmelepas">
                        <div class="input-group divsuratsiapmelepas">
                           <div class="input-group-addon">File</div>
                           <input type="text" name="SURAT_PERNYATAAN_MELEPAS" readonly value="<?php echo $pindah_unit->SURAT_PERNYATAAN_MELEPAS;?>" id="SURAT_PERNYATAAN_MELEPAS" class="form-control just-upload-field" />
                           <div class="input-group-addon"><a href="<?php echo $this->settings_lib->item('site.urluploaded'); ?><?php echo $pindah_unit->SURAT_PERNYATAAN_MELEPAS;?>" target="_blank"><i class="fa fa-mail-forward"></i></a></div>
                           <span class="input-group-btn">
                             <button type="button" kode="<?php echo $id; ?>" kolom="SURAT_PERNYATAAN_MELEPAS" kodediv="divsuratpermohonanpindah" class="btn btn-danger btn-flat alertdelete">X</button>
                           </span>
                        </div>
                   </div>
                </div>
                <div class="control-group<?php echo form_error('SK_KP_TERAKHIR') ? ' error' : ''; ?> col-sm-6">
                    <?php echo form_label(lang('pindah_unit_field_SK_KP_TERAKHIR'), 'SK_KP_TERAKHIR', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <div class="dropzone well well-sm drskkpterakhir">
                        </div>
                    </div>
                    <div class="fileskkpterakhir">
                        <div class="input-group divskkpterakhir">
                           <div class="input-group-addon">File</div>
                           <input type="text" name="SK_KP_TERAKHIR" readonly value="<?php echo $pindah_unit->SK_KP_TERAKHIR;?>" id="SK_KP_TERAKHIR" class="form-control just-upload-field" />
                           <div class="input-group-addon"><a href="<?php echo $this->settings_lib->item('site.urluploaded'); ?><?php echo $pindah_unit->SK_KP_TERAKHIR;?>" target="_blank"><i class="fa fa-mail-forward"></i></a></div>
                           <span class="input-group-btn">
                             <button type="button" kode="<?php echo $id; ?>" kolom="SK_KP_TERAKHIR" kodediv="divskkpterakhir" class="btn btn-danger btn-flat alertdelete">X</button>
                           </span>
                        </div>
                   </div>
                </div>
                <div class="control-group<?php echo form_error('SK_JABATAN') ? ' error' : ''; ?> col-sm-6">
                    <?php echo form_label(lang('pindah_unit_field_SK_JABATAN'), 'SK_JABATAN', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <div class="dropzone well well-sm drskjabatan">
                        </div>
                    </div>
                    <div class="fileskjabatan">
                        <div class="input-group divskjabatan">
                           <div class="input-group-addon">File</div>
                           <input type="text" name="SK_JABATAN" readonly value="<?php echo $pindah_unit->SK_JABATAN;?>" id="SK_JABATAN" class="form-control just-upload-field" />
                           <div class="input-group-addon"><a href="<?php echo $this->settings_lib->item('site.urluploaded'); ?><?php echo $pindah_unit->SK_JABATAN;?>" target="_blank"><i class="fa fa-mail-forward"></i></a></div>
                           <span class="input-group-btn">
                             <button type="button" kode="<?php echo $id; ?>" kolom="SK_JABATAN" kodediv="divskjabatan" class="btn btn-danger btn-flat alertdelete">X</button>
                           </span>
                        </div>
                   </div>
                </div>

                <div class="control-group<?php echo form_error('SK_TUNKIN') ? ' error' : ''; ?> col-sm-6">
                    <?php echo form_label(lang('pindah_unit_field_SK_TUNKIN'), 'SK_TUNKIN', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <div class="dropzone well well-sm drsktunkin">
                        </div>
                    </div>
                    <div class="filesktunkin">
                        <div class="input-group divsktunkin">
                           <div class="input-group-addon">File</div>
                           <input type="text" name="SK_TUNKIN" readonly value="<?php echo $pindah_unit->SK_TUNKIN;?>" id="SK_TUNKIN" class="form-control just-upload-field" />
                           <div class="input-group-addon"><a href="<?php echo $this->settings_lib->item('site.urluploaded'); ?><?php echo $pindah_unit->SK_TUNKIN;?>" target="_blank"><i class="fa fa-mail-forward"></i></a></div>
                           <span class="input-group-btn">
                             <button type="button" kode="<?php echo $id; ?>" kolom="SK_TUNKIN" kodediv="divsktunkin" class="btn btn-danger btn-flat alertdelete">X</button>
                           </span>
                        </div>
                   </div>
                </div>
                <div class="control-group<?php echo form_error('SURAT_PERNYATAAN_MENERIMA') ? ' error' : ''; ?> col-sm-6">
                    <?php echo form_label(lang('pindah_unit_field_SURAT_PERNYATAAN_MENERIMA'), 'SURAT_PERNYATAAN_MENERIMA', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <div class="dropzone well well-sm drspmenerima">
                        </div>
                    </div>
                    <div class="filespmenerima">
                        <div class="input-group divsktunkin">
                           <div class="input-group-addon">File</div>
                           <input type="text" name="SURAT_PERNYATAAN_MENERIMA" readonly value="<?php echo $pindah_unit->SURAT_PERNYATAAN_MENERIMA;?>" id="SURAT_PERNYATAAN_MENERIMA" class="form-control just-upload-field" />
                           <div class="input-group-addon"><a href="<?php echo $this->settings_lib->item('site.urluploaded'); ?><?php echo $pindah_unit->SURAT_PERNYATAAN_MENERIMA;?>" target="_blank"><i class="fa fa-mail-forward"></i></a></div>
                           <span class="input-group-btn">
                             <button type="button" kode="<?php echo $id; ?>" kolom="SURAT_PERNYATAAN_MENERIMA" kodediv="divspmenerima" class="btn btn-danger btn-flat alertdelete">X</button>
                           </span>
                        </div>
                   </div>
                </div>
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
    var drsuratpermohonan= new Dropzone(".drsuratpermohonan",{
     autoProcessQueue: true,
     url: "<?php echo base_url() ?>admin/layanan/pindah_unit/uploadberkas",
     maxFilesize: 20,
     parallelUploads : 10,
     method:"post",
     acceptedFiles:"application/pdf",
     paramName:"userfile",
     createImageThumbnails: false,
     dictDefaultMessage:"<img src='<?php echo base_url(); ?>assets/images/dropico.png' width='50px'/><br>Drop surat permohonan disini",
     dictInvalidFileType:"Type file ini tidak dizinkan",
     addRemoveLinks:true
     });
     drsuratpermohonan.on("sending",function(a,b,c){
         a.token=Math.random();
         c.append('kolom',"SURAT_PERMOHONAN_PINDAH");
         c.append('kode',"<?php echo isset($id) ? $id : ''; ?>");
         //console.log('mengirim');           
     });
     drsuratpermohonan.on("success",function(a,response){
        var obj = jQuery.parseJSON(response)
        $("#SURAT_PERMOHONAN_PINDAH").val(obj.namafile);
        drsuratpermohonan.removeAllFiles(true);
     });

     var drsuratmelepas= new Dropzone(".drsuratmelepas",{
     autoProcessQueue: true,
     url: "<?php echo base_url() ?>admin/layanan/pindah_unit/uploadberkas",
     maxFilesize: 20,
     parallelUploads : 10,
     method:"post",
     acceptedFiles:"application/pdf",
     paramName:"userfile",
     dictDefaultMessage:"<img src='<?php echo base_url(); ?>assets/images/dropico.png' width='50px'/><br>Drop surat Pernyataan kesediaan melepas",
     dictInvalidFileType:"Type file ini tidak dizinkan",
     addRemoveLinks:true
     });
     drsuratmelepas.on("sending",function(a,b,c){
         a.token=Math.random();
         c.append('kolom',"SURAT_PERNYATAAN_MELEPAS");
         c.append('kode',"<?php echo isset($id) ? $id : ''; ?>");
         //console.log('mengirim');           
     });
     drsuratmelepas.on("success",function(a,response){
        var obj = jQuery.parseJSON(response)
        $("#SURAT_PERNYATAAN_MELEPAS").val(obj.namafile);
         drsuratmelepas.removeAllFiles(true);
     });

     var drskkpterakhir= new Dropzone(".drskkpterakhir",{
     autoProcessQueue: true,
     url: "<?php echo base_url() ?>admin/layanan/pindah_unit/uploadberkas",
     maxFilesize: 20,
     parallelUploads : 10,
     method:"post",
     acceptedFiles:"application/pdf",
     paramName:"userfile",
     dictDefaultMessage:"<img src='<?php echo base_url(); ?>assets/images/dropico.png' width='50px'/><br>Drop SK KP Terakhir",
     dictInvalidFileType:"Type file ini tidak dizinkan",
     addRemoveLinks:true
     });
     drskkpterakhir.on("sending",function(a,b,c){
         a.token=Math.random();
         c.append('kolom',"SK_KP_TERAKHIR");
         c.append('kode',"<?php echo isset($id) ? $id : ''; ?>");
         //console.log('mengirim');           
     });
     drskkpterakhir.on("success",function(a,response){
        var obj = jQuery.parseJSON(response)
        $("#SK_KP_TERAKHIR").val(obj.namafile);
         drskkpterakhir.removeAllFiles(true);
     });

     var drskjabatan = new Dropzone(".drskjabatan",{
     autoProcessQueue: true,
     url: "<?php echo base_url() ?>admin/layanan/pindah_unit/uploadberkas",
     maxFilesize: 20,
     parallelUploads : 10,
     method:"post",
     acceptedFiles:"application/pdf",
     paramName:"userfile",
     dictDefaultMessage:"<img src='<?php echo base_url(); ?>assets/images/dropico.png' width='50px'/><br>Drop SK Jabatan Terakhir",
     dictInvalidFileType:"Type file ini tidak dizinkan",
     addRemoveLinks:true
     });
     drskjabatan.on("sending",function(a,b,c){
         a.token=Math.random();
         c.append('kolom',"SK_JABATAN");
         c.append('kode',"<?php echo isset($id) ? $id : ''; ?>");
         //console.log('mengirim');           
     });
     drskjabatan.on("success",function(a,response){
        var obj = jQuery.parseJSON(response)
        $("#SK_JABATAN").val(obj.namafile);
         drskjabatan.removeAllFiles(true);
     });

     var drsktunkin = new Dropzone(".drsktunkin",{
     autoProcessQueue: true,
     url: "<?php echo base_url() ?>admin/layanan/pindah_unit/uploadberkas",
     maxFilesize: 20,
     parallelUploads : 10,
     method:"post",
     acceptedFiles:"application/pdf",
     paramName:"userfile",
     dictDefaultMessage:"<img src='<?php echo base_url(); ?>assets/images/dropico.png' width='50px'/><br>Drop SK Tunkin",
     dictInvalidFileType:"Type file ini tidak dizinkan",
     addRemoveLinks:true
     });
     drsktunkin.on("sending",function(a,b,c){
         a.token=Math.random();
         c.append('kolom',"SK_TUNKIN");
         c.append('kode',"<?php echo isset($id) ? $id : ''; ?>");
         //console.log('mengirim');           
     });
     drsktunkin.on("success",function(a,response){
        var obj = jQuery.parseJSON(response)
        $("#SK_TUNKIN").val(obj.namafile);
         drsktunkin.removeAllFiles(true);
     });

     var drspmenerima = new Dropzone(".drspmenerima",{
     autoProcessQueue: true,
     url: "<?php echo base_url() ?>admin/layanan/pindah_unit/uploadberkas",
     maxFilesize: 20,
     parallelUploads : 10,
     method:"post",
     acceptedFiles:"application/pdf",
     paramName:"userfile",
     dictDefaultMessage:"<img src='<?php echo base_url(); ?>assets/images/dropico.png' width='50px'/><br>Drop Surat Pernyataan menerima dari unit tujuan",
     dictInvalidFileType:"Type file ini tidak dizinkan",
     addRemoveLinks:true
     });
     drspmenerima.on("sending",function(a,b,c){
         a.token=Math.random();
         c.append('kolom',"SURAT_PERNYATAAN_MENERIMA");
         c.append('kode',"<?php echo isset($id) ? $id : ''; ?>");
         //console.log('mengirim');           
     });
     drspmenerima.on("success",function(a,response){
        var obj = jQuery.parseJSON(response)
        $("#SURAT_PERNYATAAN_MENERIMA").val(obj.namafile);
         drspmenerima.removeAllFiles(true);
     });

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