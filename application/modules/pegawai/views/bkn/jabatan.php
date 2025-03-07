<div class="callout callout-success">
       <h4>Perhatian</h4>
       <P>dibawah ini merupakan data riwayat dari BKN</p>
        <p>Silahkan klik tombol <button class="btn btn-warning sinkrondata_jabatan" kode="<?php echo $nipBaru; ?>" tooltip="Sinkron data" ><i class="fa fa-refresh" aria-hidden="true"></i> Sinkron</button> untuk sinkronisasi data dengan aplikasi dikbudhr</p>
     </div>
    <div class="box box-info">
        <fieldset>
             <?php
              // echo "<pre>";
              // echo print_r($data_jabatan);
              // echo "</pre>";
              if($data_jabatan == null){
                echo "data pasangan tidak ditemukan";
              ?>
                <div class="callout callout-danger">
                   <h4>Tidak ada data PNS Unor</h4>
                 </div>
              <?php 
              }else{
             ?>
              <table class="table table-datatable table-bordered">
                    <thead>
                        <tr>
                            <th width='15px' >No</th>
                            <th>Jabatan</th>
                            <th>Nama Unor</th>
                            <th>Unor Induk</th>
                            <th>Nomor SK<br>TMT</th>
                            <th>Tmt Pelantikan</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $no = 1; 
                        foreach ($data_jabatan as $row) {
                        ?>
                        <tr>
                          <td><?php echo $no; ?></td>
                          <td>
                            Jenis : <?php echo $row->jenisJabatan; ?><br>
                            <?php echo $row->jabatanFungsionalUmumNama != "" ? "<b>JFU : </b>".$row->jabatanFungsionalUmumNama."<br>".$row->jabatanFungsionalUmumId : ""; ?>    
                            <?php echo $row->jabatanFungsionalNama != "" ? "<b>JFT : </b>".$row->jabatanFungsionalNama."<br>".$row->jabatanFungsionalId : ""; ?>    
                            <?php echo $row->namaJabatan != "" ? "<br><b>ST : </b>".$row->namaJabatan : ""; ?>    
                          </td>
                          <td>
                            <?php echo $row->unorId; ?> | <?php echo $row->unorNama; ?><br>
                             Satker :  <?php echo $row->satuanKerjaId; ?> | <?php echo $row->satuanKerjaNama; ?>

                            </td>
                          <td><?php echo $row->unorIndukNama; ?></td>

                          <td><?php echo $row->nomorSk; ?>
                            <?php echo $row->tmtJabatan ? "<br><b>TMT : </b>".$row->tmtJabatan : ""; ?>
                          </td>
                          <td><?php echo $row->tmtPelantikan; ?></td>
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
    $('.sinkrondata_jabatan').click(function(e) {  
    var kode =$(this).attr("kode");
    swal({
        title: "Anda Yakin?",
        text: "Sinkron riwayat jabatan dari data BKN!",
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
                    url: "<?php echo base_url() ?>pegawai/bkn/sinkron_cache_jabatan",
                    type:"POST",
                    data: post_data,
                    dataType: "json",
                    timeout:180000,
                    success: function (result) {
                        if(result.success){
                          $("#modal-global").modal('hide');
                          swal("Perhatian!", result.msg, "success");
                          $grid_daftar_jabatan.ajax.reload();
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