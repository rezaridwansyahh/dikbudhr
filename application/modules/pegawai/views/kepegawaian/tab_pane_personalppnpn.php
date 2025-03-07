<script src="<?php echo base_url(); ?>themes/admin/js/jqueryvalidation/jquery.validate.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/daterangepicker/daterangepicker.css">
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/datepicker/datepicker3.css">
<?php
	$this->load->library('convert');
 	$convert = new convert();
?>
<div class="tab-pane active" id="<?php echo $TAB_ID;?>">
    <div class="callout callout-success">
       <h4>Perhatian</h4>
       <P>Silahkan lakukan perubahan data, jika ada kesalahan atau ada perubahan</P>
       <p>Perubahan data yang anda lakukan akan diverifikasi oleh kepegawaian Satker/Upt anda, setelah itu data akan diverifikasi ulang oleh Biro SDM</p>
     </div>
	<form role="form" action="#" id="frmprofile">
	<input id='ID' type='hidden' class="form-control" name='ID' maxlength='25' value="<?php echo set_value('ID', isset($pegawai->ID) ? $pegawai->ID : ''); ?>" />
	<div class="box box-info">
            <div class="box-header no-border">
              <h3 class="box-title">Data Pribadi</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            <fieldset>
                <div class="control-group col-sm-12">
                     <div class="row">
                         <div class="col-sm-4">
                             KODE
                         </div>
                         <div class="form-group col-sm-8">
                            <b><?php echo isset($pegawai->PNS_ID) ? $pegawai->PNS_ID : ''; ?></b>
                         </div>
                     </div>
                 </div>
			  <div class="form-group col-sm-12">
				  <div class="row">
					  <div class="col-sm-4">
						  Tempat/Tanggal Lahir
					  </div>
					  <div class="control-group col-sm-4">
                        <select name="TEMPAT_LAHIR_ID" id="TEMPAT_LAHIR_ID" required="" class="form-control select2">
                            <?php 
                                if($selectedTempatLahirPegawai){
                                    echo "<option selected value='".$selectedTempatLahirPegawai->ID."'>".$selectedTempatLahirPegawai->NAMA."</option>";
                                }
                            ?>
                        </select>
					  </div>
                      <div class="control-group col-sm-4">
                        <div class="input-group date">
                          <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                          </div>
                            <input id='TGL_LAHIRUP' type='text' class="form-control pull-right" disabled="" name='TGL_LAHIRUP' maxlength='25' value="<?php echo isset($pegawai->TGL_LAHIR) ? $convert->fmtDate($pegawai->TGL_LAHIR,"dd month yyyy") : 'TGL_LAHIR'; ?>" />
                            <span class='help-inline'><?php echo form_error('TGL_LAHIR'); ?></span>
                        </div>
                    </div> 
                      
				  </div>
			  </div>
			 <div class="control-group col-sm-12">
				 <div class="row">
					 <div class="col-sm-4">
						 EMAIL
					 </div>
					 <div class="form-group col-sm-8">
					   <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                            <input id='EMAIL' type='text' class="form-control" name='EMAIL' maxlength='200' value="<?php echo set_value('EMAIL', isset($pegawai->EMAIL) ? $pegawai->EMAIL : ''); ?>" />
					   </div>
                     </div>
				 </div>
			 </div>
             <div class="form-group col-sm-12">
                 <div class="row">
                     <div class="col-sm-4">
                         ALAMAT
                     </div>
                     <div class="col-sm-8">
                        <textarea name="ALAMAT" cols="40" rows="3" id="ALAMAT" style="width:100%"><?php echo set_value('ALAMAT', isset($pegawai->ALAMAT) ? $pegawai->ALAMAT : ''); ?></textarea>
                     </div>
                 </div>
             </div>
			 
			 <div class="control-group col-sm-12">
				 <div class="row">
					 <div class="col-sm-4">
						 NO HP
					 </div>
                     <div class="form-group col-sm-8">
                        <div class="input-group">
                          <div class="input-group-addon">
                            <i class="fa fa-phone"></i>
                          </div>
                          <input id='NOMOR_HP' type='text' class="form-control" name='NOMOR_HP' maxlength='32' value="<?php echo set_value('NOMOR_HP', isset($pegawai->NOMOR_HP) ? $pegawai->NOMOR_HP : ''); ?>" />
                        </div>
                        <!-- /.input group -->
                      </div>
					 
				 </div>
			 </div>
             <div class="control-group col-sm-6">
                 <div class="row">
                     <div class="control-group col-sm-8">
                         Agama
                     </div>
                     <div class="control-group col-sm-4">
                        <select name="AGAMA_ID" id="AGAMA_ID" required class="form-control">
                            <option value="">-- Silahkan Pilih --</option>
                            <?php if (isset($agamas) && is_array($agamas) && count($agamas)):?>
                            <?php foreach($agamas as $record):?>
                                <option value="<?php echo $record->ID?>" <?php if(isset($pegawai->AGAMA_ID))  echo  ($pegawai->AGAMA_ID==$record->ID) ? "selected" : ""; ?>><?php echo $record->NAMA; ?></option>
                                <?php endforeach;?>
                            <?php endif;?>
                        </select>
                     </div>
                 </div>
             </div>
             <div class="control-group col-sm-6">
                 <div class="row">
                     <div class="form-group col-sm-4">
                         Jenis Kelamin
                     </div>
                     <div class="form-group col-sm-8">
                        <select class="validate[required] text-input form-control" required name="JENIS_KELAMIN" id="JENIS_KELAMIN" class="chosen-select-deselect">
                            <option value="">-- Pilih  --</option>
                            <option value="M" <?php if(isset($pegawai->JENIS_KELAMIN))  echo  ("M"==$pegawai->JENIS_KELAMIN) ? "selected" : ""; ?>> Laki-laki</option>
                            <option value="F" <?php if(isset($pegawai->JENIS_KELAMIN))  echo  ("F"==$pegawai->JENIS_KELAMIN) ? "selected" : ""; ?>> Perempuan</option>
                        </select>
                     </div>
                 </div>
             </div>

			 <div class="control-group col-sm-6">
				 <div class="row">
					 <div class="form-group col-sm-8">
						 Tingkat Pendidikan
					 </div>
					 <div class="form-group col-sm-4">
						 <select name="TK_PENDIDIKAN" id="TK_PENDIDIKAN" class="form-control select2">
                            <option value="">-- Silahkan Pilih --</option>
                            <?php if (isset($tkpendidikans) && is_array($tkpendidikans) && count($tkpendidikans)):?>
                            <?php foreach($tkpendidikans as $record):?>
                                <option value="<?php echo $record->ID?>" <?php if(isset($pegawai->TK_PENDIDIKAN))  echo  (TRIM($pegawai->TK_PENDIDIKAN)==$record->ID) ? "selected" : ""; ?>><?php echo $record->NAMA; ?></option>
                                <?php endforeach;?>
                            <?php endif;?>
                        </select>
					 </div>
				 </div>
			 </div>
             <div class="control-group col-sm-6">
                <div class="row">
                    <div class="col-sm-4">
                        Pendidikan
                    </div>
                    <div class="col-sm-8">
                        <select name="PENDIDIKAN_ID" id="PENDIDIKAN_ID" class="form-control select2">
                            <option value="">-- Silahkan Pilih --</option>
                            <?php 
                                if($selectedPendidikanID){
                                    echo "<option selected value='".$selectedPendidikanID->ID."'>".$selectedPendidikanID->NAMA."</option>";
                                }
                            ?>
                             
                        </select>
                    </div>
                </div>
            </div>
             
            </fieldset>
            <fieldset>
                <legend>Lainnya</legend>
                    <div class="control-group<?php echo form_error('JENIS_KAWIN_ID') ? ' error' : ''; ?> col-sm-12">
                       <?php echo form_label("STATUS PERKAWINAN", 'JENIS_KAWIN_ID', array('class' => 'control-label')); ?>
                       <div class='controls'>
                           <select name="JENIS_KAWIN_ID" id="JENIS_KAWIN_ID" class="form-control select2">
                               <option value="">-- Silahkan Pilih --</option>
                               <?php if (isset($jenis_kawins) && is_array($jenis_kawins) && count($jenis_kawins)):?>
                               <?php foreach($jenis_kawins as $record):?>
                                   <option value="<?php echo $record->ID?>" <?php if(isset($pegawai->JENIS_KAWIN_ID))  echo  ($pegawai->JENIS_KAWIN_ID==$record->ID) ? "selected" : ""; ?>><?php echo $record->NAMA; ?></option>
                                   <?php endforeach;?>
                               <?php endif;?>
                           </select>
                           <span class='help-inline'><?php echo form_error('JENIS_KAWIN_ID'); ?></span>
                       </div>
                    </div>
                   <div class="control-group<?php echo form_error('NO_SURAT_DOKTER') ? ' error' : ''; ?> col-sm-6">
                       <?php echo form_label("NO SURAT KETERANGAN SEHAT DOKTER", 'NO_SURAT_DOKTER', array('class' => 'control-label')); ?>
                       <div class='controls'>
                           <input id='NO_SURAT_DOKTER' type='text' class="form-control" name='NO_SURAT_DOKTER' maxlength='25' value="<?php echo set_value('NO_SURAT_DOKTER', isset($pegawai->NO_SURAT_DOKTER) ? trim($pegawai->NO_SURAT_DOKTER) : ''); ?>" />
                           <span class='help-inline'><?php echo form_error('NO_SURAT_DOKTER'); ?></span>
                       </div>
                   </div>
                   <div class="control-group col-sm-6">
                       <label for="inputNAMA" class="control-label">TANGGAL</label>
                       <div class="input-group date">
                         <div class="input-group-addon">
                           <i class="fa fa-calendar"></i>
                         </div>
                           <input id='TGL_SURAT_DOKTER' type='text' class="form-control pull-right datepicker" name='TGL_SURAT_DOKTER' maxlength='25' value="<?php echo set_value('TGL_SURAT_DOKTER', isset($pegawai->TGL_SURAT_DOKTER) ? trim($pegawai->TGL_SURAT_DOKTER) : ''); ?>" />
                           <span class='help-inline'><?php echo form_error('TGL_SURAT_DOKTER'); ?></span>
                       </div>
                   </div> 
                   <div class="control-group <?php echo form_error('NO_BEBAS_NARKOBA') ? ' error' : ''; ?> col-sm-6">
                       <?php echo form_label("NO SURAT BEBAS NARKOBA", 'NO_BEBAS_NARKOBA', array('class' => 'control-label')); ?>
                       <div class='controls'>
                           <input id='NO_BEBAS_NARKOBA' type='text' class="form-control" name='NO_BEBAS_NARKOBA' maxlength='25' value="<?php echo set_value('NO_BEBAS_NARKOBA', isset($pegawai->NO_BEBAS_NARKOBA) ? trim($pegawai->NO_BEBAS_NARKOBA) : ''); ?>" />
                           <span class='help-inline'><?php echo form_error('NO_BEBAS_NARKOBA'); ?></span>
                       </div>
                   </div>
                   <div class="control-group col-sm-6">
                       <label for="inputNAMA" class="control-label">TANGGAL</label>
                       <div class="input-group date">
                         <div class="input-group-addon">
                           <i class="fa fa-calendar"></i>
                         </div>
                           <input id='TGL_BEBAS_NARKOBA' type='text' class="form-control pull-right datepicker" name='TGL_BEBAS_NARKOBA' maxlength='25' value="<?php echo set_value('TGL_BEBAS_NARKOBA', isset($pegawai->TGL_BEBAS_NARKOBA) ? $pegawai->TGL_BEBAS_NARKOBA : ''); ?>" />
                           <span class='help-inline'><?php echo form_error('TGL_BEBAS_NARKOBA'); ?></span>
                       </div>
                   </div> 
                   <div class="control-group<?php echo form_error('NO_CATATAN_POLISI') ? ' error' : ''; ?> col-sm-6">
                       <?php echo form_label("NO CATATAN KEPOLISIAN", 'NO_CATATAN_POLISI', array('class' => 'control-label')); ?>
                       <div class='controls'>
                           <input id='NO_CATATAN_POLISI' type='text' class="form-control" name='NO_CATATAN_POLISI' maxlength='25' value="<?php echo set_value('NO_CATATAN_POLISI', isset($pegawai->NO_CATATAN_POLISI) ? trim($pegawai->NO_CATATAN_POLISI) : ''); ?>" />
                           <span class='help-inline'><?php echo form_error('NO_CATATAN_POLISI'); ?></span>
                       </div>
                   </div>
                   <div class="control-group col-sm-6">
                       <label for="inputNAMA" class="control-label">TANGGAL</label>
                       <div class="input-group date">
                         <div class="input-group-addon">
                           <i class="fa fa-calendar"></i>
                         </div>
                           <input id='TGL_CATATAN_POLISI' type='text' class="form-control pull-right datepicker" name='TGL_CATATAN_POLISI' maxlength='25' value="<?php echo set_value('TGL_CATATAN_POLISI', isset($pegawai->TGL_CATATAN_POLISI) ? $pegawai->TGL_CATATAN_POLISI : ''); ?>" />
                           <span class='help-inline'><?php echo form_error('TGL_CATATAN_POLISI'); ?></span>
                       </div>
                   </div> 
                   <div class="control-group<?php echo form_error('AKTE_KELAHIRAN') ? ' error' : ''; ?> col-sm-12">
                       <?php echo form_label("AKTE KELAHIRAN", 'AKTE_KELAHIRAN', array('class' => 'control-label')); ?>
                       <div class='controls'>
                           <input id='AKTE_KELAHIRAN' type='text' class="form-control" name='AKTE_KELAHIRAN' maxlength='25' value="<?php echo set_value('AKTE_KELAHIRAN', isset($pegawai->AKTE_KELAHIRAN) ? trim($pegawai->AKTE_KELAHIRAN) : ''); ?>" />
                           <span class='help-inline'><?php echo form_error('AKTE_KELAHIRAN'); ?></span>
                       </div>
                   </div>
                    
                    <div class="control-group col-sm-6">
                       <?php echo form_label("NO BPJS", 'BPJS', array('class' => 'control-label')); ?>
                       <div class='controls'>
                           <input id='BPJS' type='text' class="form-control" name='BPJS' maxlength='25' value="<?php echo set_value('BPJS', isset($pegawai->BPJS) ? trim($pegawai->BPJS) : ''); ?>" />
                       </div>
                   </div>
                   <div class="control-group<?php echo form_error('NO_TASPEN') ? ' error' : ''; ?> col-sm-6">
                       <?php echo form_label("NO TASPEN", 'NO_TASPEN', array('class' => 'control-label')); ?>
                       <div class='controls'>
                           <input id='NO_TASPEN' type='text' class="form-control" name='NO_TASPEN' maxlength='50' value="<?php echo set_value('NO_TASPEN', isset($pegawai->NO_TASPEN) ? trim($pegawai->NO_TASPEN) : ''); ?>" />
                           <span class='help-inline'><?php echo form_error('NO_TASPEN'); ?></span>
                       </div>
                   </div>
                   <div class="control-group<?php echo form_error('NPWP') ? ' error' : ''; ?> col-sm-6">
                       <?php echo form_label(lang('pegawai_field_NPWP'), 'NPWP', array('class' => 'control-label')); ?>
                       <div class='controls'>
                           <input id='NPWP' type='text' class="form-control" name='NPWP' maxlength='25' value="<?php echo set_value('NPWP', isset($pegawai->NPWP) ? $pegawai->NPWP : ''); ?>" />
                           <span class='help-inline'><?php echo form_error('NPWP'); ?></span>
                       </div>
                   </div>
                   <div class="control-group col-sm-6">
                       <label for="inputNAMA" class="control-label">TGL NPWP</label>
                       <div class="input-group date">
                         <div class="input-group-addon">
                           <i class="fa fa-calendar"></i>
                         </div>
                           <input id='TGL_NPWP' type='text' class="form-control pull-right datepicker" name='TGL_NPWP' maxlength='25' value="<?php echo set_value('TGL_NPWP', isset($pegawai->TGL_NPWP) ? $pegawai->TGL_NPWP : ''); ?>" />
                           <span class='help-inline'><?php echo form_error('TGL_NPWP'); ?></span>
                       </div>
                   </div> 
            </fieldset>
              <!-- /.table-responsive -->
             
            <!-- /.box-body -->
            
            <!-- /.box-footer -->
          </div>
    </div>
	 
    <div class="box-footer">
        <?php if ($this->auth->has_permission('Pegawai.Kepegawaian.Updatemandiri')) : ?>
            <input type='submit' name='save' id="btnsaveprofile" class='btn btn-warning' value="Simpan Perubahan" />
            <?PHP endif; ?>
      </div>
    </form>
</div>
<script>
    //Date picker
    $('.datepicker').datepicker({
      autoclose: true,format: 'yyyy-mm-dd'
    });
</script>

<script>
    $("#frmprofile").validate({
        submitHandler: function(form) {
        $("#btnsaveprofile").val('Menyimpan Perubahan......').attr('disabled', true).addClass('bt-hud').unbind('click');
        submitdata();
      },
      rules: {
        NOMOR_HP: {
          required: false,
          number: true
        },
        EMAIL: {
            required: true,
            email: true
        },
        BPJS: {
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
    $("#TEMPAT_LAHIR_ID").select2({
        placeholder: 'Cari Tempat Lahir...',
        width: '100%',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: '<?php echo site_url("admin/lokasi/pegawai/ajax");?>',
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
    $('#TK_PENDIDIKAN').change(function() {
        /* 
        var valuetingkat = $('#TK_PENDIDIKAN').val();
            $("#PENDIDIKAN_ID").empty().append("<option>loading...</option>"); //show loading...
            
            var json_url = "<?php echo base_url(); ?>pegawai/pendidikan/getbytingkat?tingkat=" + encodeURIComponent(valuetingkat);
            //alert(json_url);
            $.getJSON(json_url,function(data){
                $("#PENDIDIKAN_ID").empty(); 
                if(data==""){
                    $("#PENDIDIKAN_ID").append("<option value=\"\">Silahkan Pilih </option>");
                }
                else{
                    $("#PENDIDIKAN_ID").append("<option value=\"\">Silahkan Pilih</option>");
                    for(i=0; i<data.id.length; i++){
                        $("#PENDIDIKAN_ID").append("<option value=\"" + data.id[i]  + "\">" + data.nama[i] +"</option>");
                    }
                }
                
            });
            */
            //$("#PENDIDIKAN_ID").select2("updateResults");
            return false;
    });
    $("#PENDIDIKAN_ID").select2({
        placeholder: 'Cari Jabatan...',
        width: '100%',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: '<?php echo site_url("pegawai/masterpendidikan/ajax");?>',
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
	function submitdata(){
		
		var json_url = "<?php echo base_url() ?>admin/kepegawaian/pegawai/updatemandiri";
		 $.ajax({    
		 	type: "post",
			url: json_url,
			data: $("#frmprofile").serialize(),
            dataType: "json",
			success: function(data){ 
                if(data.success){
                    swal("Pemberitahuan!", data.msg, "success");
                    $("#btnsaveprofile").val('Simpan Perubahan').attr('disabled', false).addClass('bt-hud').unbind('click');
                }
                else {
                    $(".messages").empty().append(data.msg);
                }
			}});
		return false; 
	}
</script>
