<style type="text/css">
  .abu{
    background-color: #e6e0e0;
  }
</style>
<div class="callout callout-success">
 <h4>Perhatian</h4>
 <P>dibawah ini merupakan data dari aplikasi mutasi</p>
  <p>Silahkan klik tombol <button class="btn btn-warning sinkron" kode="<?php echo $nip; ?>" tooltip="Sinkron data" ><i class="fa fa-refresh" aria-hidden="true"></i> Sinkron</button> untuk sinkronisasi data dengan aplikasi dikbudhr</p>
</div>
<?php if(!$rec_jabatan){ ?>
  <div class="callout callout-danger">
   <h4>data jabatan tidak ditemukan</h4>
 </div>
<?php }?>    
 <?php if(!$rec_unitkerja){ ?>
  <div class="callout callout-danger">
   <h4>unit kerja tidak ditemukan</h4>
 </div>
<?php }else{ ?>
  
<?php }?>
<div class="box box-info">
  <div class="box-body">
    <div class="col-md-12">
      <?php
        // echo "<pre>";
        // echo print_r($rec_unitkerja);
        // echo "</pre>";
        if($personal == null){
        ?>
          <div class="callout callout-danger">
             <h4>data tidak ditemukan</h4>
           </div>
        <?php }else{ ?>      
          <br>
          <table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-data table-hover" border="0">
            <tr>
              <td width="50%" class="abu">NIP</td><td><?php e($personal[0]->NIP); ?></td>
            </tr>
            <tr>
              <td class="abu">NAMA</td><td><?php e($personal[0]->NAMA); ?></td>
            </tr>
            <tr>
              <td class="abu">STATUS</td><td><?php e($personal[0]->STATUS_KEAKTIFAN); ?></td>
            </tr>
            <tr>
              <td class="abu">JABATAN</td><td><?php e($personal[0]->JABATAN); ?></td>
            </tr>
            <tr>
              <td class="abu">KATEGORI JABATAN</td><td><?php e($personal[0]->KATEGORI_JABATAN); ?></td>
            </tr>
            <tr>
              <td class="abu">KELAS JABATAN</td><td><?php e($personal[0]->KELAS_JABATAN); ?></td>
            </tr>
            <tr>
              <td class="abu">KELOMPOK JF</td><td><?php e($personal[0]->KELOMPOK_JF); ?></td>
            </tr>
            <tr>
              <td class="abu">PENSIUN JABATAN</td><td><?php e($personal[0]->PENSIUN_JABATAN); ?></td>
            </tr>
            <tr>
              <td class="abu">ID JABATAN INTERNAL</td><td><?php e($personal[0]->ID_JABATAN_INTERNAL); ?></td>
            </tr>
            <tr>
              <td class="abu">ID JABATAN BKN</td><td><?php e($personal[0]->ID_JABATAN_BKN); ?></td>
            </tr>
            <tr>
              <td class="abu">UNIT KERJA</td><td><?php e($personal[0]->UNIT_KERJA); ?></td>
            </tr>
            <tr>
              <td class="abu">SATUAN KERJA</td><td><?php e($personal[0]->SATUAN_KERJA); ?></td>
            </tr>
            <tr>
              <td class="abu">SATUAN KERJA SINGKATAN</td><td><?php e($personal[0]->SATUAN_KERJA_SINGKATAN); ?></td>
            </tr>
            <tr>
              <td class="abu">ESELONISASI UNIT KERJA</td><td><?php e($personal[0]->ESELONISASI_UNIT_KERJA); ?></td>
            </tr>
            <tr>
              <td class="abu">ESELONISASI SATUAN KERJA</td><td><?php e($personal[0]->ESELONISASI_SATUAN_KERJA); ?></td>
            </tr>
            <tr>
              <td class="abu">KEDUDUKAN</td><td><?php e($personal[0]->KEDUDUKAN); ?></td>
            </tr>
            <tr>
              <td class="abu">ALAMAT KANTOR</td><td><?php e($personal[0]->ALAMAT_KANTOR); ?></td>
            </tr>
            <tr>
              <td class="abu">ID UNIT KERJA INTERNAL</td><td><?php e($personal[0]->ID_UNIT_KERJA_INTERNAL); ?></td>
            </tr>
            <tr>
              <td class="abu">ID UNIT KERJA BKN</td><td><?php e($personal[0]->ID_UNIT_KERJA_BKN); ?></td>
            </tr>
            <tr>
              <td class="abu">NOMOR SK</td><td><?php e($personal[0]->NOMOR_SK); ?></td>
            </tr>
            <tr>
              <td class="abu">TGL_SK</td><td><?php e($personal[0]->TGL_SK); ?></td>
            </tr>
            <tr>
              <td class="abu">TMT_SK</td><td><?php e($personal[0]->TMT_SK); ?></td>
            </tr>
            <tr>
              <td class="abu">JENIS_SK</td><td><?php e($personal[0]->JENIS_SK); ?></td>
            </tr>
            <tr>
              <td class="abu">ID_RIWAYAT</td><td><?php e($personal[0]->ID_RIWAYAT); ?></td>
            </tr>
          </table>
        <?php }?>
        <fieldset>
          <legend>Unitkerja Dikbudhr</legend>
          <?php if($rec_unitkerja){ ?>
             <table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-data table-hover" border="0">
                  <tr>
                    <td width="50%" class="abu">KODE INTERNAL</td><td><?php e($rec_unitkerja->KODE_INTERNAL); ?></td>
                  </tr>
                  <tr>
                    <td class="abu">NAMA UNOR</td><td><?php e($rec_unitkerja->NAMA_UNOR); ?></td>
                  </tr>
                  <tr>
                    <td class="abu">NAMA PEJABAT</td><td><?php e($rec_unitkerja->NAMA_PEJABAT); ?></td>
                  </tr>
                  <tr>
                    <td class="abu">KODE BKN</td><td><?php e($rec_unitkerja->ID); ?></td>
                  </tr>
              </table>
          <?php } ?>
        </fieldset>
        
        <fieldset>
          <legend>Jabatan Dikbudhr</legend>
            <?php if($rec_jabatan){ ?>
               <table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-data table-hover" border="0">
                    <tr>
                      <td width="50%" class="abu">KODE JABATAN</td><td><?php e($rec_jabatan->KODE_JABATAN); ?></td>
                    </tr>
                    <tr>
                      <td class="abu">NAMA JABATAN</td><td><?php e($rec_jabatan->NAMA_JABATAN); ?></td>
                    </tr>
                    <tr>
                      <td class="abu">KODE_BKN</td><td><?php e($rec_jabatan->KODE_BKN); ?></td>
                    </tr>
                   
                </table>
            <?php } ?>
      </fieldset>
    </div>
  </div>
</div>
<script type="text/javascript">
  $('.sinkron').click(function(e) {  
  var kode =$(this).attr("kode");
  swal({
      title: "Anda Yakin?",
      text: "Sinkron data!",
      type: "warning",
      showCancelButton: true,
      confirmButtonClass: 'btn-success',
      confirmButtonText: 'Ya!',
      cancelButtonText: "Tidak, Batalkan!",
      closeOnConfirm: false,
      showLoaderOnConfirm: true,
      closeOnCancel: false
  },
  function (isConfirm) {
      if (isConfirm) {
          var post_data = "kode="+kode;
          $.ajax({
                  url: "<?php echo base_url() ?>pegawai/sinkronisasi/sinkron_personal",
                  type:"POST",
                  data: post_data,
                  dataType: "json",
                  timeout:180000,
                  success: function (result) {
                      if(result==null){
                        swal("Perhatian!", "Error, silahkan hubungi admin pusat", "error");

                      }else{
                        if(result.success){
                          $("#modal-global").modal('hide');
                          swal("Perhatian!", result.msg, "success");
                          $grid_daftar_jabatan.ajax.reload();
                        }else{
                          swal("Perhatian!", result.msg, "error");
                        }

                      }
                      
              },
              error : function(error) {
                  console.log(error);
                  
                  alert("error");
                  window.location.reload();
              } 
          });        
          
      } else {
          swal("Batal", "Transaksi dibatalkan", "error");
      }
  });
});
</script>