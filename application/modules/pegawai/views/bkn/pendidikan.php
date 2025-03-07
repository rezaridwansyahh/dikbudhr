<div class="callout callout-success">
       <h4>Perhatian</h4>
       <P>dibawah ini merupakan data riwayat dari BKN</p>
        <p>Silahkan klik tombol <button class="btn btn-warning sinkrondata_pendidikan" kode="<?php echo $nipBaru; ?>" tooltip="Sinkron data" ><i class="fa fa-refresh" aria-hidden="true"></i> Sinkron</button> untuk sinkronisasi data dengan aplikasi dikbudhr</p>
     </div>
    <div class="box box-info">
        <fieldset>
             <?php
              // echo "<pre>";
              // echo print_r($data_pendidikan);
              // echo "</pre>";
              if($data_pendidikan == null){
                ?>
                <div class="callout callout-danger">
                   <h4>Tidak ada data pendidikan</h4>
                 </div>
              <?php  
              }else{
             ?>
              <table class="table table-datatable table-bordered">
                    <thead>
                        <tr>
                            <th width='15px' >No</th>
                            <th>Jenjang</th>
                            <th>Tahun Lulus</th>
                            <th>Tgl Lulus</th>
                            <th>Nomor Ijazah</th>
                            <th>Nama Sekolah</th>
                            <th>Gelar Depan</th>
                            <th>Gelar Belakang</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $no = 1; 
                        foreach ($data_pendidikan as $row) {
                        ?>
                        <tr>
                          <td><?php echo $no; ?></td>
                          <td><?php echo $row->tkPendidikanNama; ?></td>
                          <td><?php echo $row->tahunLulus; ?></td>
                          <td><?php echo $row->tglLulus; ?></td>
                          <td><?php echo $row->nomorIjasah; ?></td>
                          <td><?php echo $row->namaSekolah; ?></td>
                          <td><?php echo $row->gelarDepan; ?></td>
                          <td><?php echo $row->gelarBelakang; ?></td>
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
    $('.sinkrondata_pendidikan').click(function(e) {  
    var kode =$(this).attr("kode");
    swal({
        title: "Anda Yakin?",
        text: "Sinkron riwayat pendidikan dari data BKN!",
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
                    url: "<?php echo base_url() ?>pegawai/bkn/sinkron_cache_pendidikan",
                    type:"POST",
                    data: post_data,
                    dataType: "json",
                    timeout:180000,
                    success: function (result) {
                        if(result.success){
                          $("#modal-global").modal('hide');
                          swal("Perhatian!", result.msg, "success");
                          $grid_daftar_pendidikan.ajax.reload();
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