<?php
    $this->load->library('Convert');
    $convert = new Convert;
?>

<div class="tab-pane active" id="<?php echo $TAB_ID;?>">
    <center><b>Transaksi lapor kehadiran Bekerja dari rumah (BDR), <?php echo $hari; ?> <?php echo $tanggal_indonesia; ?>, Waktu anda "<?php echo $WAKTU; ?>"</b></center>
    <hr>

    <div class="row">
        <div class="col-md-12">
        <center>    
            <?php echo form_open($this->uri->uri_string(), 'id="frm-kehadiran"'); ?> 
            <input type="hidden" name="username" value="<?php echo isset($pegawai->NIK) ? trim($pegawai->NIK) : ""; ?>">
            <input type="hidden" name="NIP" value="<?php echo isset($NIP_PNS) ? trim($NIP_PNS) : ""; ?>">
            
            <div id="external-events">
                <textarea class="form-control" id="jambox" readonly style="position: relative;width: 30%;height:auto;"><?php
                    if(isset($data_absen) && is_array($data_absen) && count($data_absen)):
                        foreach ($data_absen as $record) {
                            echo trim($record->JAM)."\n";
                        }
                    endif;
                    ?></textarea>
                <!--
                <div class="external-event bg-green ui-draggable ui-draggable-handle" style="position: relative;width: 20%"><i class="fa fa-clock-o"></i> <?php echo $jam_checkin != "" ? $jam_checkin : "Checkin belum ada"; ?></div>
                <div class="external-event bg-yellow ui-draggable ui-draggable-handle" style="position: relative;width: 20%"><i class="fa fa-clock-o"></i> <?php echo $jam_checkout != "" ? $jam_checkout : "Checkout belum ada"; ?></div>
                 -->
            </div>
            <br>
            Silahkan melakukan pengisian log harian melalui aplikasi <a href="http://skp.sdm.kemdikbud.go.id/skp/site/login.jsp" target="_blank">e-SKP</a>
                
                <a class="btn btn-block btn-social btn-dropbox" style="width: 20%" id="btn_lapor_kehadiran">
                      <i class="fa fa-save"></i> Lapor Kehadiran BDR
                    </a>
            </center>
            </form>
        </div>
    </div>
    
</div>
 
<script type="text/javascript">
 
   
function submitkehadiran(){
    $('#btn_lapor_kehadiran').html('<i class="fa fa-save"></i> Sedang Mengirim...');
    $('#btn_lapor_kehadiran').addClass('disabled');
    var the_data = $( "#frm-kehadiran" ).serialize();
    var json_url = "<?php echo base_url() ?>admin/izin/izin_pegawai/kirimkehadiran";
     $.ajax({    
      type: "POST",
      url: json_url,
      data: the_data,
            dataType: "json",
            success: function(data){ 
                if(data.success){
                    swal("Pemberitahuan!", data.msg, "success");
                    $('#jambox').append(data.jam); 
                    $('#btn_lapor_kehadiran').html('<i class="fa fa-save"></i> Lapor Kehadiran BDR');
                    $('#btn_lapor_kehadiran').removeClass('disabled');
                }
                else {
                    $(".messages").empty().append(data.msg);
                    $('#btn_lapor_kehadiran').html('<i class="fa fa-save"></i> Lapor Kehadiran BDR');
                    $('#btn_lapor_kehadiran').removeClass('disabled');
                    //swal("Pemberitahuan!", data.msg, "error");    
                }
      }});
    return false; 
  }
   
$( "#1btn_lapor_kehadiran" ).click(function() {
    submitkehadiran();
});
$( "#btn_lapor_kehadiran" ).click(function() {
    swal({
        title: "Anda Yakin?",
        text: "Saya benar sedang jadwal BDR dan bertanggungjawab atas pelaporan ini!",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: 'btn-success',
        confirmButtonText: 'Ya, Lapor BDR!',
        cancelButtonText: "Tidak, Batalkan!",
        closeOnConfirm: false,
        closeOnCancel: false
    },
    function (isConfirm) {
        if (isConfirm) {
           submitkehadiran();
            
        } else {
            swal("Batal", "", "error");
        }
    });
});
</script>