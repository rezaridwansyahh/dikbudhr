<div class="callout callout-success">
       <h4>Perhatian</h4>
       <P>Dibawah ini merupakan data riwayat SKP dari BKN</p>
        <p>Silahkan klik tombol <button class="btn btn-warning sinkrondata_skp" kode="<?php echo $nipBaru; ?>" tooltip="Sinkron data" ><i class="fa fa-refresh" aria-hidden="true"></i> Sinkron</button> untuk sinkronisasi data dengan aplikasi Simpeg</p>
     </div>
    <div class="box box-info">
        <fieldset>
             <?php
              // echo "<pre>";
              // echo print_r($data_skp);
              // echo "</pre>";
              if($data_skp == null){
              ?>
                <div class="callout callout-danger">
                   <h4>Tidak ada data SKP</h4>
                 </div>
              <?php 
              }else{
             ?>
              <table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-datatable table-hover">
                        <thead>
                            <tr>
                                <th width='15px' >No</th>
                                <th>Tahun</th>
                                <th>Nilai SKP</th>
                                <th>Orientasi Pelayanan</th>
                                <th>Integritas</th>
                                <th>Komitmen</th>
                                <th>Disiplin</th>
                                <th>Kerjasama</th>
                                <th>jumlah</th>
                                <th>Perilaku Kerja</th>
                                <th>nilaiPrestasiKerja</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            $no = 1; 
                            foreach ($data_skp as $row) {
                            ?>
                            <tr>
                              <td><?php echo $no; ?></td>
                              <td><?php echo $row->tahun; ?></td>
                              <td><?php echo $row->nilaiSkp; ?></td>
                              <td><?php echo $row->orientasiPelayanan; ?></td>
                              <td><?php echo $row->integritas; ?></td>
                              <td><?php echo $row->komitmen; ?></td>
                              <td><?php echo $row->disiplin; ?></td>
                              <td><?php echo $row->kerjasama; ?></td>
                              <td><?php echo $row->jumlah; ?></td>
                              <td><?php echo $row->nilairatarata; ?></td>
                              <td><?php echo $row->nilaiPrestasiKerja; ?></td>
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
    $('.sinkrondata_skp').click(function(e) {  
    var kode =$(this).attr("kode");
    swal({
        title: "Anda Yakin?",
        text: "Sinkron riwayat SKP dari data BKN!",
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
                    url: "<?php echo base_url() ?>pegawai/bkn/sinkron_cache_skp",
                    type:"POST",
                    data: post_data,
                    dataType: "json",
                    timeout:180000,
                    success: function (result) {
                        if(result.success){
                          $("#modal-global").modal('hide');
                          swal("Perhatian!", result.msg, "success");
                          $grid_daftar_skp.ajax.reload();
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