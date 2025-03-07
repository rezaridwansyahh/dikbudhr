<div class="callout callout-success">
       <h4>Perhatian</h4>
       <P>dibawah ini merupakan data riwayat dari BKN</p>
        <p>Silahkan klik tombol <button class="btn btn-warning sinkrondata_diklat" kode="<?php echo $nipBaru; ?>" tooltip="Sinkron data" ><i class="fa fa-refresh" aria-hidden="true"></i> Sinkron</button> untuk sinkronisasi data dengan aplikasi dikbudhr</p>
     </div>
    <div class="box box-info">
        <fieldset>
             <?php
              // echo "<pre>";
              // echo print_r($data_diklat);
              // echo "</pre>";
              if($data_diklat == null){
               ?>
                <div class="callout callout-danger">
                   <h4>Tidak ada data diklat</h4>
                 </div>
              <?php  
              }else{ ?>
                <table class="table table-datatable table-bordered">
                    <thead>
                        <tr>
                            <th width='15px' >No</th>
                            <th>Nama</th>
                            <th>Nomor</th>
                            <th>Tanggal</th>
                            <th>Tahun</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $no = 1; 
                        foreach ($data_diklat as $row) {
                        ?>
                        <tr>
                          <td><?php echo $no; ?></td>
                          <td><?php echo $row->latihanStrukturalNama; ?></td>
                          <td><?php echo $row->nomor; ?></td>
                          <td><?php echo $row->tanggal; ?></td>
                          <td><?php echo $row->tahun; ?></td>
                        </tr>
                        <?php  
                        $no++;
                        }
                    ?>
                    </tbody>

                    
                    <tbody>
                       
                    </tbody>
                </table>  
            <?php } ?>
        </fieldset>
        
          
  </div>
  <script type="text/javascript">
    $('.sinkrondata_diklat').click(function(e) {  
    var kode =$(this).attr("kode");
    swal({
        title: "Anda Yakin?",
        text: "Sinkron riwayat diklat dari data BKN!",
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
                    url: "<?php echo base_url() ?>pegawai/bkn/sinkron_cache_diklat",
                    type:"POST",
                    data: post_data,
                    dataType: "json",
                    timeout:180000,
                    success: function (result) {
                        if(result.success){
                          $("#modal-global").modal('hide');
                          swal("Perhatian!", result.msg, "success");
                          $grid_daftar_diklat.ajax.reload();
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