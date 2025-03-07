<link href="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/css/fileinput.css" media="all" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/themes/explorer/theme.css" media="all" rel="stylesheet" type="text/css"/>

<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/daterangepicker/daterangepicker.css">
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/datepicker/datepicker3.css">

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
<div class='alert alert-block alert-info fade in'>
    <a class='close' data-dismiss='alert'>&times;</a>
    <h4 class='alert-heading'>
        Perhatian
    </h4>
    <p>Untuk mengupload file langsung ke penandatangan(Tidak membuat draft), silahkan pilih file pdf yang akan dikirimkan untuk ditandatangani</p>
    <p>Jika pegawai diluar pegawai kementerian pendidikan dan kebudayaan, silahkan langsung diinputkan NIP dan Nama Pegawai pada kolom nip pemilik sk dan nama pemilik SK</p>
</div>
<div class='admin-box box box-primary'>
    <div class="box-header">
        <h3>Buat Dokumen <?php echo $judul_ttd; ?></h3>
    </div>        
           
    <div class="box-body">
    <div class="message"></div>
    <?php echo form_open($this->uri->uri_string(),"id=submit_form","form"); ?>
        <fieldset>
            <div class="control-group<?php echo form_error('pegawai') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Jenis Dokumen", 'id_master_proses', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select id="id_master_proses" name="id_master_proses" width="100%" class="form-control select2">
                        <option value="">Silahkan Pilih</option>
                        <?php if (isset($reckategori_ds) && is_array($reckategori_ds) && count($reckategori_ds)):?>
                        <?php foreach($reckategori_ds as $record):?>
                            <option value="<?php echo $record->id;?>" <?php if(isset($id_master_proses))  echo  ($id_master_proses==$record->id) ? "selected" : ""; ?>><?php echo $record->nama_proses; ?></option>
                            <?php endforeach;?>
                        <?php endif;?>
                    </select>
                </div>
            </div>
            <div class="control-group<?php echo form_error('pegawai') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Pegawai", 'pegawai', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select id="nip_sk" name="nip_sk" width="100%" class="form-control select2 class_pegawai">
                        <?php
                        if($pegawai){
                            echo "<option selected value='".$pegawai->PNS_ID."'>".$pegawai->NAMA."</option>";
                        }
                        ?>
                    </select>
                    <span class='help-inline'>Pegawai Kemendikbud</span>
                </div>
            </div>
            <div class="control-group col-sm-12">
                <label for="inputNAMA" class="control-label">Nip Pemilik SK</label>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-user"></i>
                  </div>
                    <input type='text' class="form-control pull-right" id='nip_pemilik_sk' name='nip_pemilik_sk'  value="" />
                </div>
                <span class='help-inline'></span>
            </div> 
            <div class="control-group col-sm-12">
                <label for="inputNAMA" class="control-label">Nama Pemilik SK</label>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-user"></i>
                  </div>
                    <input type='text' class="form-control pull-right" id='nama_pemilik_sk' name='nama_pemilik_sk'  value="" />
                </div>
                <span class='help-inline'></span>
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
                </div>
            </div>

            
            <div class="control-group col-sm-6">
                <label for="inputNAMA" class="control-label">Tanggal Dokumen</label>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                    <input type='text' class="form-control pull-right datepicker" name='tgl_sk'  value="<?=$tanggal['tanggal_dokumen']?>" />
                </div>
                <span class='help-inline'></span>
            </div> 
            <div class="control-group col-sm-6">
                <label for="inputNAMA" class="control-label">Nomor Dokumen/Nomor SK</label>
                <div class="input-group ">
                  <div class="input-group-addon">
                    <i class="fa fa-edit"></i>
                  </div>
                    <input type='text' class="form-control pull-right" name='nomor_sk'  value="" />
                </div>
                <span class='help-inline'></span>
            </div> 
            <div class="control-group col-sm-12">
                <label for="inputNAMA" class="control-label">TMT Dokumen</label>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                    <input type='text' class="form-control pull-right datepicker" name='tmt_sk'  value="<?=$tanggal['tmt_dokumen']?>" />
                </div>
                <span class='help-inline'>Jika ada</span>
            </div> 

            <?php if (isset($proses_variable) && is_array($proses_variable) && count($proses_variable)):?>
                <?php $no = 1; ?>
                <?php foreach($proses_variable as $recordvar):?>
                    <?php if($recordvar->tipe == "Char"){ ?>
                        <div class="control-group col-sm-12">
                            <label for="inputNAMA" class="control-label"><?php echo $recordvar->label_variable; ?></label>
                            <div class="input-group ">
                              <div class="input-group-addon">
                                <i class="fa fa-edit"></i>
                              </div>
                                <input type='text' class="form-control pull-right" name='<?php echo $recordvar->nama_variable; ?>'  value="" />
                            </div>
                            <span class='help-inline'></span>
                        </div> 
                     
                    <?php } ?>
                    <?php if($recordvar->tipe == "Date"){ ?>
                    <div class="control-group col-sm-6">
                        <label for="inputNAMA" class="control-label"><?php echo $recordvar->label_variable; ?></label>
                        <div class="input-group date">
                          <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                          </div>
                            <input type='text' class="form-control pull-right datepicker" name='<?php echo $recordvar->nama_variable; ?>'  value="" />
                        </div>
                        <span class='help-inline'><?php echo $recordvar->keterangan_variable; ?></span>
                    </div> 
                    <?php } ?>
                     
                <?php 
                $no++;
                endforeach;
            endif;
            ?>
            <div class="control-group<?php echo form_error('dokumen') ? ' error' : ''; ?> col-sm-8">
                <?php echo form_label("Dokumen", 'dokumen', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <div id="form_upload">
                        <input id="nama_sk" name="nama_sk" class="file" type="file" data-show-upload="false"  data-preview-file-type="pdf" title="Silahkan Pilih file">
                      </div>
            
                    <span class='help-inline'>silahkan pilih dokumen pdf jika dokumen sudah dibuat sebelumnya</span>
                </div>
            </div>
            <?php if($tte->template_sk != ""){ ?>
            <div class="control-group<?php echo form_error('template_sk') ? ' error' : ''; ?> col-sm-2">
                <?php echo form_label("<br>", 'template_sk', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <a href="<?php echo trim($this->settings_lib->item('site.urlsk'))."template/".trim($tte->template_sk); ?>" class="btn btn-warning"> <i class="fa fa-download"></i> Template SK</a>
                </div>
            </div>
            <?php } ?>
        </fieldset>
        <fieldset id="formveririkator">
            <legend>
                Tandatangan Digital
            </legend>
            <div class="control-group <?php echo form_error('verifikator') ? ' error' : ''; ?> col-sm-8" id="divv_<?php echo $no; ?>">
                <?php echo form_label("Halaman TTD", 'halaman_ttd', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select name="halaman_ttd" width="100%" class="form-control select2">
                        <option value="1">PERTAMA</option>
                        <option value="2">TERAKHIR</option>
                    </select>
                </div>
            </div>
            <div class="control-group <?php echo form_error('verifikator') ? ' error' : ''; ?> col-sm-8" id="divv_<?php echo $no; ?>">
                <?php echo form_label("Tampilkan Qrcode BSSN", 'show_qrcode', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select name="show_qrcode" width="100%" class="form-control select2">
                        <option value="0">Tidak</option>
                        <option value="1">Tampilkan</option>
                    </select>
                </div>
            </div>
            <div class="control-group <?php echo form_error('letak_ttd') ? ' error' : ''; ?> col-sm-8" id="divv_<?php echo $no; ?>">
                <?php echo form_label("Letak Tampilan Qr/TTD", 'letak_ttd', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select name="letak_ttd" width="100%" class="form-control select2">
                        <option value="0">Kanan Bawah</option>
                        <option value="1">Tengah Bawah</option>
                        <option value="2">Kiri Bawah</option>
                    </select>
                </div>
            </div>

            <div class="control-group <?php echo form_error('letak_ttd') ? ' error' : ''; ?> col-sm-8" id="divv_<?php echo $no; ?>">
                <?php echo form_label("Mode", 'mode_usul', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select name="mode_usul" width="100%" class="form-control select2">
                        <option value="0">Default</option>
                        <option value="1">Nota Usul</option>
                        <option value="2">Surat Usul</option>
                    </select>
                </div>
            </div>
        </fieldset>
        <br>
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
                             <span class='btn btn-danger ' kodediv="<?php echo $no; ?>" id="delverifikator" href="#"><i class="fa fa-trash-o"></i></span>
                            <?php if($no==1){ ?>
                             <span class='btn btn-warning' id="addverifikator" href="#"><i class="fa fa-plus"></i></span>
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
                <div class="message"></div>
            <input type='button' name='save' id="btn_save" class='btn btn-primary' value="Simpan Proses TTE" />
            
        </div>
    <?php echo form_close(); ?>
</div>
<script src="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/themes/explorer/theme.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/js/fileinput.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>themes/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
<script type="text/javascript">
    $("#id_master_proses").change(function(){
        if($(this).val()){
            window.location = "<?php echo base_url(); ?>admin/tte/tte/create/"+$(this).val();
        }
        else window.location = "<?php echo base_url(); ?>admin/tte/tte/create/";
    });
</script>   
<script>
    //Date picker
    $('.datepicker').datepicker({
      autoclose: true,format: 'yyyy-mm-dd'
    });
</script>
<script type="text/javascript">
  var nomor  = 1;
  var nomorv  = 1;
  function submitdata(){
    var the_data = new FormData(document.getElementById("submit_form"));
    $.ajax({
        url: "<?php echo base_url('admin/tte/tte/act_savedraft'); ?>",
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
                window.location.href = "<?php echo base_url(); ?>admin/tte/tte";
            }else{
                $(".message").html(response.msg);
                //swal("Ada kesalahan",response.msg,"error");
            }
        }
    });
    
    return false; 
  } 
$('body').on('click','#btn_save',function () { 
  submitdata();
});
 $(".select2").select2();
$("#nip_sk").change(function(){
    $("#nip_pemilik_sk").val($(this).val());
    var text_pegawai = $("#nip_sk option:selected" ).text();;
    var result = text_pegawai.split('-');
    var nama_pegawai = result[1];
    $("#nama_pemilik_sk").val($.trim(nama_pegawai));
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
$(".class_pegawai").select2({
        placeholder: 'Cari Pegawai.....',
        width: '100%',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: '<?php echo site_url("pegawai/kepegawaian/ajaxnip");?>',
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