<div class="callout callout-success">
       <h4>Perhatian</h4>
       <P>dibawah ini merupakan data riwayat dari BKN</p>
        <p>Silahkan klik tombol <button class="btn btn-warning sinkrondata_kepangkatan" kode="<?php echo $nipBaru; ?>" tooltip="Sinkron data" ><i class="fa fa-refresh" aria-hidden="true"></i> Sinkron</button> untuk sinkronisasi data dengan aplikasi dikbudhr</p>
     </div>
    <div class="box box-info">
        <fieldset>
             <?php
              if($data_golongan == null){
               ?>
                <div class="callout callout-danger">
                   <h4>Tidak ada data golongan</h4>
                 </div>
              <?php  
              }else{ ?>

                <br><br>
            <table class="table table-datatable table-bordered">
                <thead>
                    <tr>
                        <th width='15px' >No</th>
                        <th>Golongan</th>
                        <th>No SK</th>
                        <th>Tgl SK</th>
                        <th>TMT</th>
                        <th>No Pertek</th>
                        <th>Tgl Pertek</th>
                        <th>Jenis KP</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $no = 1; 
                    foreach ($data_golongan as $row) {
                    ?>
                    <tr>
                      <td><?php echo $no; ?></td>
                      <td><?php echo $row->golongan; ?></td>
                      <td><?php echo $row->skNomor; ?></td>
                      <td><?php echo $row->skTanggal; ?></td>
                      <td><?php echo $row->tmtGolongan; ?></td>
                      <td><?php echo $row->noPertekBkn; ?></td>
                      <td><?php echo $row->tglPertekBkn; ?></td>
                      <td><?php echo $row->jenisKPNama; ?></td>
                    </tr>
                    <?php  
                    $no++;
                    }
                ?>
                </tbody>
 
            </table>  
        <?php } ?>
        </fieldset>
        
          
  </div>
  <script type="text/javascript">
    $('.sinkrondata_kepangkatan').click(function(e) {  
    var kode =$(this).attr("kode");
    swal({
        title: "Anda Yakin?",
        text: "Sinkron riwayat kepangkatan dari data BKN!",
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
                    url: "<?php echo base_url() ?>pegawai/bkn/sinkron_cache_golongan",
                    type:"POST",
                    data: post_data,
                    dataType: "json",
                    timeout:180000,
                    success: function (result) {
                        if(result.success){
                          $("#modal-global").modal('hide');
                          swal("Perhatian!", result.msg, "success");
                          $grid_daftar.ajax.reload();
                        }else{
                         swal("Perhatian!", result.msg, "error");
                        }
                },
                error : function(error) {
                    alert(error);
                } 
            });        
            
        } else {
            swal("Batal", "Transaksi dibatalkan", "error");
        }
    });
  });
  </script>