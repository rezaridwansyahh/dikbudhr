<?php
    $this->load->library('Convert');
    $convert = new Convert;
?>

<div class="tab-pane active" id="<?php echo $TAB_ID;?>">
    <div class="row">
        <div class="col-md-12">
            <div class="callout callout-info">
                <h5>Informasi!</h5>

                <p>Silahkan Klik pada tombol "Lapor Kehadiran KDR" untuk melaporkan kehadiran kerja dari rumah (KDR)</p>
              </div>
              <div class="messages"></div>
        </div>
        <div class="col-md-6">
            <b>Atasan Langsung</b> : <?php echo $nama_atasan_langsung; ?><p>
            <b>Pejabat yang berwenang memberikan cuti </b> : <?php echo $nama_ppk; ?><p>
            <b>Jenis pengajuan </b> : Lapor Kehadiran KDR<p>
             
        </div>
        <!-- /.col -->
        <div class="col-md-6">
          
            <h5><b>Informasi Hari Libur</b></h5>
            <!-- /.box-header -->
            <?php
            if(isset($record_hari_libur_bulan_ini) && is_array($record_hari_libur_bulan_ini) && count($record_hari_libur_bulan_ini)):
                $index = 1;
                foreach ($record_hari_libur_bulan_ini as $record) {
                    $tanggal_indo = "";
                    if($record->START_DATE == $record->END_DATE){
                        $tanggal_indo = $convert->fmtDate($record->START_DATE,"dd month yyyy");
                    }else{
                        $tanggal_indo = $convert->fmtDate($record->START_DATE,"dd month yyyy")." - ".$convert->fmtDate($record->END_DATE,"dd month yyyy");
                    }
                    echo $index.". ".$record->INFO." <i>Tanggal</i> ". $tanggal_indo." <br>";
                    $index++;
                }
            // hapus data lama terlebih dahulu yang tahun dan bulan yang sama
            ?>
            <?php
            else:
            ?>
            Tidak ada libur pada bulan ini
            <?php
            endif;
            ?>
        </div>
        <!-- /.col -->
    </div>
    <center><b>Transaksi lapor kehadiran kerja dari rumah (KDR), <?php echo $hari; ?> <?php echo $tanggal_indonesia; ?></b></center>
    <hr>

    <div class="row">
        <div class="col-md-12">
        <center>    
            <?php echo form_open($this->uri->uri_string(), 'id="frm-kehadiran"'); ?> 
            <input type="hidden" name="username" value="<?php echo isset($pegawai_login->NIK) ? trim($pegawai_login->NIK) : ""; ?>">
            <input type="hidden" name="NIP" value="<?php echo isset($nip) ? trim($nip) : ""; ?>">
            
            <div id="external-events">
                <div class="external-event bg-green ui-draggable ui-draggable-handle" style="position: relative;width: 20%"><i class="fa fa-clock-o"></i> <?php echo $jam_checkin != "" ? $jam_checkin : "Checkin belum ada"; ?></div>
                <div class="external-event bg-yellow ui-draggable ui-draggable-handle" style="position: relative;width: 20%"><i class="fa fa-clock-o"></i> <?php echo $jam_checkout != "" ? $jam_checkout : "Checkout belum ada"; ?></div>
                 
            </div>
            <br>
            Silahkan melakukan pengisian log harian melalui aplikasi
                
                <a class="btn btn-block btn-social btn-dropbox" style="width: 20%" id="btn_lapor_kehadiran">
                      <i class="fa fa-save"></i> Lapor Kehadiran KDR
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
                    location.reload();
                }
                else {
                    $(".messages").empty().append(data.msg);
                    $('#btn_lapor_kehadiran').html('<i class="fa fa-save"></i> Lapor Kehadiran KDR');
                    $('#btn_lapor_kehadiran').removeClass('disabled');
                    //swal("Pemberitahuan!", data.msg, "error");    
                }
      }});
    return false; 
  }
   
$( "#btn_lapor_kehadiran" ).click(function() {
    submitkehadiran();
});
</script>
