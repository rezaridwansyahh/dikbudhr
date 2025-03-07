<link href="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/css/fileinput.css" media="all" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/themes/explorer/theme.css" media="all" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/select2/select2.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/css/dropzone/dropzone.min.css">
<script src="<?php echo base_url(); ?>themes/admin/js/dropzone/dropzone.min.js"></script>
<script src="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/themes/explorer/theme.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/js/fileinput.js" type="text/javascript"></script>

<?php 
    $this->load->library('convert');
    $convert = new convert();
    $id = isset($izin_pegawai->ID) ? $izin_pegawai->ID : '';
?>
<div class="callout callout-success">
   <h4>Perhatian</h4>
   <p>Silahkan lengkapi formulir pengajuan "<?php echo $nama_izin; ?>"</p>
   <p><?php echo $keterangan_izin; ?></p>
 </div>
 <?php if($NIP_ATASAN == ""){ ?>
    <div class="callout callout-danger">
       <h4>Perhatian</h4>
       <p>Belum ada data atasan</p>
     </div>
<?php } ?>
<div class="row">
    <div class="col-md-12">     
        <div class='box box-warning' id="form-riwayat-assesmen-add">
            <div class="box-body">
                    <?php
                    // $this->load->view('izin/_lineapproval',array('level'=>$level,'NIP_ATASAN'=>$NIP_ATASAN,'NAMA_ATASAN'=>$NAMA_ATASAN,'NIP_PPK'=>$NIP_PPK,'NAMA_PPK'=>$NAMA_PPK,'NAMA_PEGAWAI'=>$pegawai->NAMA));
                    ?>
                <div class="messages">
                </div>
            <?php echo form_open($this->uri->uri_string(), 'id="frm"'); ?>
                <fieldset>
                    <input id='ID' type='hidden' name='ID' value="<?php echo set_value('ID', isset($izin_pegawai->ID) ? $izin_pegawai->ID : ""); ?>" />

                    <input id='NIP_PNS' type='hidden' required='required' name='NIP_PNS' maxlength='18' value="<?php echo set_value('NIP_PNS', isset($izin_pegawai->NIP_PNS) ? $izin_pegawai->NIP_PNS : TRIM($NIP_PNS)); ?>" />
                    <input id='NAMA' type='hidden' name='NAMA' maxlength='100' value="<?php echo set_value('NAMA', isset($izin_pegawai->NAMA) ? $izin_pegawai->NAMA : $pegawai->NAMA); ?>" />
                    <input id='JABATAN' type='hidden' name='JABATAN' maxlength='100' value="<?php echo set_value('JABATAN', isset($izin_pegawai->JABATAN) ? $izin_pegawai->JABATAN : $NAMA_JABATAN); ?>" />
                    <input id='UNIT_KERJA' type='hidden' name='UNIT_KERJA' maxlength='100' value="<?php echo set_value('UNIT_KERJA', isset($izin_pegawai->UNIT_KERJA) ? $izin_pegawai->UNIT_KERJA : $unor_induk_id); ?>" />
                    <input id='MASA_KERJA_TAHUN' type='hidden' name='MASA_KERJA_TAHUN'  value="<?php echo set_value('MASA_KERJA_TAHUN', isset($izin_pegawai->MASA_KERJA_TAHUN) ? $izin_pegawai->MASA_KERJA_TAHUN : $recpns_aktif->masa_kerja_th); ?>" />
                    <input id='MASA_KERJA_BULAN' type='hidden' name='MASA_KERJA_BULAN'  value="<?php echo set_value('MASA_KERJA_BULAN', isset($izin_pegawai->MASA_KERJA_BULAN) ? $izin_pegawai->MASA_KERJA_BULAN : $recpns_aktif->masa_kerja_bl); ?>" />
                    <input id='GAJI_POKOK' type='hidden' name='GAJI_POKOK' maxlength='10' value="<?php echo set_value('GAJI_POKOK', isset($izin_pegawai->GAJI_POKOK) ? $izin_pegawai->GAJI_POKOK : ''); ?>" />
                    <input id='KODE_IZIN' type='hidden' required='required' name='KODE_IZIN' maxlength='5' value="<?php echo set_value('KODE_IZIN', isset($izin_pegawai->KODE_IZIN) ? $izin_pegawai->KODE_IZIN : $kode_izin); ?>" />
                    <input id='SATUAN' type='hidden' name='SATUAN' maxlength='10' value="<?php echo set_value('SATUAN', isset($izin_pegawai->SATUAN) ? $izin_pegawai->SATUAN : 'Hari'); ?>" />

                    <input id='NIP_ATASAN' type='hidden' name='NIP_ATASAN' value="<?php echo set_value('NIP_ATASAN', isset($izin_pegawai->NIP_ATASAN) ? $izin_pegawai->NIP_ATASAN : $NIP_ATASAN); ?>" />
                    <input id='NAMA_ATASAN' type='hidden' name='NAMA_ATASAN' value="<?php echo set_value('NAMA_ATASAN', isset($izin_pegawai->NAMA_ATASAN) ? $izin_pegawai->NAMA_ATASAN : $NAMA_ATASAN); ?>" />
                    <input id='NIP_PYBMC' type='hidden' name='NIP_PYBMC' value="<?php echo set_value('NIP_PYBMC', isset($izin_pegawai->NIP_PYBMC) ? $izin_pegawai->NIP_PYBMC : $NIP_PPK); ?>" />
                    <input id='NAMA_PYBMC' type='hidden' name='NAMA_PYBMC' value="<?php echo set_value('NAMA_PYBMC', isset($izin_pegawai->NAMA_PYBMC) ? $izin_pegawai->NAMA_PYBMC : $NAMA_PPK); ?>" />
                    
                    <div class="control-group col-sm-5">
                        <label for="inputNAMA" class="control-label">DARI TANGGAL</label>
                        <div class="input-group date">
                          <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                          </div>
                            <input type='text' class="form-control pull-right datepicker" name='DARI_TANGGAL' id='DARI_TANGGAL' value="<?php echo set_value('DARI_TANGGAL', isset($izin_pegawai->DARI_TANGGAL) ? $izin_pegawai->DARI_TANGGAL : ''); ?>" />
                            <span class='help-inline'><?php echo form_error('DARI_TANGGAL'); ?></span>
                        </div>
                    </div> 
                    <div class="control-group col-sm-5">
                        <label for="inputNAMA" class="control-label">SAMPAI TANGGAL</label>
                        <div class="input-group date">
                          <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                          </div>
                            <input type='text' class="form-control pull-right datepicker" name='SAMPAI_TANGGAL' id='SAMPAI_TANGGAL' value="<?php echo set_value('SAMPAI_TANGGAL', isset($izin_pegawai->SAMPAI_TANGGAL) ? $izin_pegawai->SAMPAI_TANGGAL : ''); ?>" />
                            <span class='help-inline'><?php echo form_error('SAMPAI_TANGGAL'); ?></span>
                        </div>
                    </div> 
                    <div class="control-group  col-sm-2">
                        <?php echo form_label(lang('izin_pegawai_field_JUMLAH'), 'JUMLAH', array('class' => 'control-label')); ?>
                        <div class="input-group date">
                            <input id='JUMLAH' type='text' name='JUMLAH' class="form-control"  value="<?php echo set_value('JUMLAH', isset($izin_pegawai->JUMLAH) ? $izin_pegawai->JUMLAH : ''); ?>" />
                            <span class="input-group-addon">Hari</i></span>
                        </div>
                    </div>
                    
                    <div class="control-group<?php echo form_error('KETERANGAN') ? ' error' : ''; ?>  col-sm-12">
                        <?php echo form_label("ALASAN CUTI", 'KETERANGAN', array('class' => 'control-label')); ?>
                        <div class='controls'>
                            <textarea id='KETERANGAN' type='text' class="form-control" name='KETERANGAN'><?php echo set_value('KETERANGAN', isset($izin_pegawai->KETERANGAN) ? $izin_pegawai->KETERANGAN : ''); ?></textarea>
                            <span class='help-inline'><?php echo form_error('KETERANGAN'); ?></span>
                        </div>
                    </div>
                    <div class="control-group<?php echo form_error('ALAMAT_SELAMA_CUTI') ? ' error' : ''; ?>  col-sm-12">
                        <?php echo form_label(lang('izin_pegawai_field_ALAMAT_SELAMA_CUTI'), 'KETERANGAN', array('class' => 'control-label')); ?>
                        <div class='controls'>
                            <textarea id='ALAMAT_SELAMA_CUTI' type='text' class="form-control" name='ALAMAT_SELAMA_CUTI'><?php echo set_value('ALAMAT_SELAMA_CUTI', isset($izin_pegawai->ALAMAT_SELAMA_CUTI) ? $izin_pegawai->ALAMAT_SELAMA_CUTI : $pegawai->ALAMAT); ?></textarea>
                            <span class='help-inline'><?php echo form_error('ALAMAT_SELAMA_CUTI'); ?></span>
                        </div>
                    </div>
                    

                    <div class="control-group<?php echo form_error('TLP_SELAMA_CUTI') ? ' error' : ''; ?>  col-sm-12">
                        <?php echo form_label(lang('izin_pegawai_field_TLP_SELAMA_CUTI'), 'TLP_SELAMA_CUTI', array('class' => 'control-label')); ?>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                            <input id='TLP_SELAMA_CUTI' type='text' class="form-control" name='TLP_SELAMA_CUTI' maxlength='20' value="<?php echo set_value('TLP_SELAMA_CUTI', isset($izin_pegawai->TLP_SELAMA_CUTI) ? $izin_pegawai->TLP_SELAMA_CUTI : ''); ?>" />
                          </div>

                    </div>
                    <div class="control-group<?php echo form_error('LAMPIRAN_FILE') ? ' error' : ''; ?>  col-sm-12">
                        <?php echo form_label("Lampiran", 'LAMPIRAN_FILE', array('class' => 'control-label')); ?>
                        <div class='controls'>
                                <input id="LAMPIRAN_FILE" name="LAMPIRAN_FILE" class="file" type="file" data-show-upload="false" data-preview-file-type="pdf" title="Silahkan Pilih file pdf">
                            <span class='help-inline'><?php echo form_error('LAMPIRAN_FILE'); ?></span>
                        </div>
                    </div>
                </fieldset>
                </div>
                <div class="box-footer">
                    <a href="javascript:;" id="btnsavecuti" class="btn <?php echo $NIP_ATASAN =="" ? "disabled" : ""; ?> green btn-primary button-submit"> 
                        Simpan
                        <i class="fa fa-save"></i> 
                    </a>
                    <?php echo lang('bf_or'); ?>
                    <?php echo anchor(SITE_AREA . '/izin/izin_pegawai', lang('izin_pegawai_cancel'), 'class="btn btn-warning"'); ?>
                    
                </div>
            <?php echo form_close(); ?>
        </div>
    </div>
    
<script>
    $("#btnsavecuti").click(function(){
        $('#btnsavecuti').text('Sedang Mengirim...');
        $('#btnsavecuti').addClass('disabled');
        $('#btnsavecuti').append('<i class="fa fa-spinner"></i> ');
        submitdatacuti();
        return false; 
    }); 

    function submitdatacuti(){
        var json_url = "<?php echo base_url() ?>admin/izin/izin_pegawai/save";
        event.preventDefault();

        // Get form
        var form = $('#frm')[0];

        // Create an FormData object 
        var param_data = new FormData(form);
        
         $.ajax({    
            type: "POST",
            enctype: 'multipart/form-data',
            url: json_url,
            data: param_data,
            dataType: "json",
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            success: function(data){ 
                if(data.success){
                    swal("Pemberitahuan!", data.msg, "success");
                    url = "<?php echo base_url(); ?>admin/izin/izin_pegawai/index";
                    $(location).attr("href", url);
                }
                else {
                    $(".messages").empty().append(data.msg);

                    $('#btnsavecuti').text('Simpan ');
                    $('#btnsavecuti').removeClass('disabled');
                    $('#btnsavecuti').append('<i class="fa fa-save"></i> ');
                }
            },
            error: function (e) {
                $(".messages").empty().append("<div class='alert alert-block alert-error fade in'><a class='close' data-dismiss='alert'>&times;</a><h4 class='alert-heading'>Error</h4>Ada kesalahan, sialahkan hubungi admin</div>");
                $('#btnsavecuti').text('Simpan ');
                $('#btnsavecuti').removeClass('disabled');
                $('#btnsavecuti').append('<i class="fa fa-save"></i> ');

            }});
        return false; 
    }
$('.datepicker').datepicker({
  autoclose: true,format: 'yyyy-mm-dd'
}).on("input change", function (e) {
    var date = $(this).datepicker('getDate');
});
$("#DARI_TANGGAL").change(function(){
        var date1 = $('#DARI_TANGGAL').datepicker({ dateFormat: 'yy-mm-dd' }).val();
        var date2 = $('#SAMPAI_TANGGAL').datepicker({ dateFormat: 'yy-mm-dd' }).val();

        if(date1 != "" && date2 != ""){
            var jumlahhari = workingDaysBetweenDates(date1,date2);  
            //jumlahhari = jumlahhari + 1;
            $("#JUMLAH").val(jumlahhari);
        }
        if(date2 == ""){
            $("#SAMPAI_TANGGAL").val(date1);    
        }
        return false; 
    }); 
    $("#SAMPAI_TANGGAL").change(function(){
        var date1 = $('#DARI_TANGGAL').datepicker({ dateFormat: 'yy-mm-dd' }).val();
        var date2 = $('#SAMPAI_TANGGAL').datepicker({ dateFormat: 'yy-mm-dd' }).val();
        if(date1 != "" && date2 != ""){
            var jumlahhari = workingDaysBetweenDates(date1,date2);  
            //jumlahhari = jumlahhari + 1;
            $("#JUMLAH").val(jumlahhari);
        }
        return false; 
    }); 
function getdays(date1,date2){
  // Here are the two dates to compare
  // First we split the values to arrays date1[0] is the year, [1] the month and [2] the day
  date1 = date1.split('-');
  date2 = date2.split('-');
  // Now we convert the array to a Date object, which has several helpful methods
  date1 = new Date(date1[0], date1[1], date1[2]);
  date2 = new Date(date2[0], date2[1], date2[2]);
  // We use the getTime() method and get the unixtime (in milliseconds, but we want seconds, therefore we divide it through 1000)
  date1_unixtime = parseInt(date1.getTime() / 1000);
  date2_unixtime = parseInt(date2.getTime() / 1000);
  // This is the calculated difference in seconds
  var timeDifference = date2_unixtime - date1_unixtime;
  // in Hours
  var timeDifferenceInHours = timeDifference / 60 / 60;

  // and finaly, in days :)
  var timeDifferenceInDays = timeDifferenceInHours  / 24;

  return timeDifferenceInDays;
}

$(document).ready(() => {
  $('#calc').click(() => {
  var d1 = $('#d1').val();
  var d2 = $('#d2').val();
    $('#dif').text(workingDaysBetweenDates(d1,d2));
  });
});

let workingDaysBetweenDates = (d0, d1) => {
  /* Two working days and an sunday (not working day) */
  var holidays = <?php echo count($ahari_libur) > 0 ? json_decode($ahari_libur) : "null"; ?>;
  var startDate = parseDate(d0);
  var endDate = parseDate(d1);  

// Validate input
  if (endDate < startDate) {
    return 0;
  }

// Calculate days between dates
  var millisecondsPerDay = 86400 * 1000; // Day in milliseconds
  startDate.setHours(0, 0, 0, 1);  // Start just after midnight
  endDate.setHours(23, 59, 59, 999);  // End just before midnight
  var diff = endDate - startDate;  // Milliseconds between datetime objects    
  var days = Math.ceil(diff / millisecondsPerDay);

  // Subtract two weekend days for every week in between
  var weeks = Math.floor(days / 7);
  days -= weeks * 2;

  // Handle special cases
  var startDay = startDate.getDay();
  var endDay = endDate.getDay();
    
  // Remove weekend not previously removed.   
  if (startDay - endDay > 1) {
    days -= 2;
  }
  // Remove start day if span starts on Sunday but ends before Saturday
  if (startDay == 0 && endDay != 6) {
    days--;  
  }
  // Remove end day if span ends on Saturday but starts after Sunday
  if (endDay == 6 && startDay != 0) {
    days--;
  }
  /* Here is the code */
  holidays.forEach(day => {

    if ((day >= d0) && (day <= d1)) {
      /* If it is not saturday (6) or sunday (0), substract it */
      if ((parseDate(day).getDay() % 6) != 0) {
        days--;
      }
    }
  });
  return days;
}
           
function parseDate(input) {
    // Transform date from text to date
  var parts = input.match(/(\d+)/g);
  // new Date(year, month [, date [, hours[, minutes[, seconds[, ms]]]]])
  return new Date(parts[0], parts[1]-1, parts[2]); // months are 0-based
}
</script>