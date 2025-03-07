<div class="tab-pane" id="<?php echo $TAB_ID;?>">
    <div class="callout callout-success">
       <h4>Perhatian</h4>
       <P>dibawah ini merupakan data riwayat dari BKN</p>
        <p>Silahkan klik tombol <button class='btn btn-warning sinkrondata' kode="<?php echo $pegawai_bkn->nipBaru; ?>" tooltip="Sinkron data" ><i class="fa fa-refresh" aria-hidden="true"></i> Sinkron</button> untuk sinkronisasi data dengan aplikasi dikbudhr</p>
     </div>
    <div class="box box-info">
        <fieldset>
            <legend>Riwayat Golongan</legend>
            <?php
              if($data_golongan == null){
               ?>
                <div class="callout callout-danger">
                   <h4>Tidak ada data golongan</h4>
                 </div>
              <?php  
              }else{ ?>
            <?php if ($this->auth->has_permission('Pegawai.ViewDataBkn.View')) : ?>
                &nbsp;<button class='btn btn-warning pull-right sinkrondata' kode="<?php echo $pegawai_bkn->nipBaru; ?>" tooltip="Sinkron data" ><i class="fa fa-refresh" aria-hidden="true"></i> Sinkron</button>
            <?php endif; ?>
            &nbsp;<a href="<?php echo base_url(); ?>admin/kepegawaian/pegawai/profilen/<?php echo $pegawai->ID; ?>" class='btn btn-success pull-right' kode="<?php echo $pegawai_bkn->nipBaru; ?>" tooltip="Lihat data dikbudhr" ><i class="fa fa-eye" aria-hidden="true"></i> Lihat Profile dikbudhr</a>
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
        
        <fieldset>
            <legend>Data SKP</legend>
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
                <table class="table table-datatable table-bordered">
                        <thead>
                            <tr>
                                <th width='15px' >No</th>
                                <th>Tahun</th>
                                <th>Nilai SKP</th>
                                <th>Orientasi Pelayanan</th>
                                <th>Integrasi</th>
                                <th>Komitmen</th>
                                <th>Disiplin</th>
                                <th>Kerjasama</th>
                                <th>Perilaku Kerja</th>
                                <th>nilaiPrestasiKerja</th>
                                <th>jumlah</th>
                                <th>Nilai rata-rata</th>
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
                              <td><?php echo $row->nilaiPerilakuKerja; ?></td>
                              <td><?php echo $row->nilaiPrestasiKerja; ?></td>
                              <td><?php echo $row->jumlah; ?></td>
                              <td><?php echo $row->nilairatarata; ?></td>
                            </tr>
                            <?php  
                            $no++;
                            }
                        ?>
                        </tbody>
                </table>  
            <?php } ?>
        </fieldset>
        <fieldset>
            <legend>Data Pendidikan</legend>
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
        <fieldset>
            <legend>Data Jabatan</legend>
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
                            <?php echo $row->jenisJabatan; ?><br>
                            <?php echo $row->jabatanFungsionalUmumNama != "" ? "<b>JFU : </b>".$row->jabatanFungsionalUmumNama : ""; ?>    
                            <?php echo $row->jabatanFungsionalNama != "" ? "<b>JFT : </b>".$row->jabatanFungsionalNama : ""; ?>    
                            <?php echo $row->namaJabatan != "" ? "<br><b>ST : </b>".$row->namaJabatan : ""; ?>    
                          </td>
                          <td><?php echo $row->unorNama; ?></td>
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
        <fieldset>
            <legend>Data PNS Unor </legend>
             <?php
              // echo "<pre>";
              // echo print_r($data_pns_unor);
              // echo "</pre>";
              if($data_pns_unor == null){
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
                            <th>SK</th>
                            <th>Nama Unor Baru</th>
                            <th>Asal Nama</th>
                            <th>instansi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $no = 1; 
                        foreach ($data_pns_unor as $row) {
                        ?>
                        <tr>
                          <td><?php echo $no; ?></td>
                          <td>
                            <b>Nomor : </b><?php echo $row->skNomor; ?>
                            <br><b>Tanggal : </b><?php echo $row->skTanggal; ?>
                          </td>
                          <td><?php echo $row->namaUnorBaru; ?></td>
                          <td><?php echo $row->asalNama; ?></td>
                          <td><?php echo $row->instansi; ?></td>
                        </tr>
                        <?php  
                        $no++;
                        }
                    ?>
                    </tbody>

                    
                    <tbody>
                       
                    </tbody>
                </table>  
                <?php
              }
             ?>
        </fieldset>
        <fieldset>
            <legend>Data Diklat</legend>
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
        <fieldset>
            <legend>Data Pasangan</legend>
             <?php
              
              if($data_pasangan == null){
                ?>
                <div class="callout callout-danger">
                   <h4>Tidak ada data Pasangan</h4>
                 </div>
              <?php       
              }else{
                echo "<pre>";
              echo print_r($data_pasangan);
              echo "</pre>";
              }
             ?>
        </fieldset>
        <fieldset>
            <legend>Data Penghargaan</legend>
             <?php
              if($data_penghargaan == null){
                ?>
                <div class="callout callout-danger">
                   <h4>Tidak ada data penghargaan</h4>
                 </div>
              <?php 
              }else{
                echo "<pre>";
                echo print_r($data_penghargaan);
                echo "</pre>";
              }
             ?>
        </fieldset>
        <fieldset>
            <legend>Data Masa Kerja</legend>
             <?php
              if($data_masa_kerja == null){
                ?>
                <div class="callout callout-danger">
                   <h4>Tidak ada data masakerja</h4>
                 </div>
              <?php 
              }else{
                echo "<pre>";
                echo print_r($data_masa_kerja);
                echo "</pre>";
              }
             ?>
        </fieldset>

        <fieldset>
            <legend>Data Hukuman Disiplin </legend>
             <?php
              if($data_hukdis == null){
                ?>
                <div class="callout callout-danger">
                   <h4>Tidak ada data hukdis</h4>
                 </div>
              <?php 
              }else{
                echo "<pre>";
                echo print_r($data_hukdis);
                echo "</pre>";
              }
             ?>
        </fieldset>
        
        <fieldset>
            <legend>Data Anak </legend>
             <?php
             // echo "<pre>";
             //    echo print_r($data_anak);
             //    echo "</pre>";
              if($data_anak == null){
                ?>
                <div class="callout callout-danger">
                   <h4>Tidak ada data Anak</h4>
                 </div>
              <?php 
              }else{
                echo "<pre>";
                echo print_r($data_anak);
                echo "</pre>";
              }
             ?>
        </fieldset>
        <fieldset>
            <legend>Data Orang Tua </legend>
             <?php
              if($data_ortu == null){
                ?>
                <div class="callout callout-danger">
                   <h4>Tidak ada data Orang tua</h4>
                 </div>
              <?php 
              }else{
                echo "<pre>";
                echo print_r($data_ortu);
                echo "</pre>";
              }
             ?>
        </fieldset>
        <fieldset>
            <legend>Data History PPO </legend>
             <?php
              if($data_ppo_hist == null){
                ?>
                <div class="callout callout-danger">
                   <h4>Tidak ada data PPO</h4>
                 </div>
              <?php 
              }else{
                echo "<pre>";
              echo print_r($data_ppo_hist);
              echo "</pre>";
              }
             ?>
        </fieldset>
        <fieldset>
            <legend>Data History KPO </legend>
             <?php
              
              if($data_kpo_sk_hist == null){
                ?>
                <div class="callout callout-danger">
                   <h4>Tidak ada data history KPO</h4>
                 </div>
              <?php 
              }else{
                echo "<pre>";
              echo print_r($data_kpo_sk_hist);
              echo "</pre>";
              }
             ?>
        </fieldset>
         <!--
        <fieldset>
            <legend>Data DP3 </legend>
             <?php
              echo "<pre>";
              echo print_r($data_dp3);
              echo "</pre>";
              if($data_dp3 == null){
                ?>
                <div class="callout callout-danger">
                   <h4>Tidak ada data DP3</h4>
                 </div>
              <?php 
              }
             ?>
        </fieldset>
      -->
      <!--
        <fieldset>
            <legend>Data PWK</legend>
             <?php
              
              if($data_pwk == null){
                ?>
                <div class="callout callout-danger">
                   <h4>Tidak ada data PWK</h4>
                 </div>
              <?php 
              }else{
                echo "<pre>";
              echo print_r($data_pwk);
              echo "</pre>";
              }
             ?>
        </fieldset>
      -->
    </div>
</div>
<script type="text/javascript">
    $('body').on('click','.sinkrondata',function () { 
    var kode =$(this).attr("kode");
    swal({
        title: "Anda Yakin?",
        text: "Sinkron data dari BKN!",
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
                    url: "<?php echo base_url() ?>pegawai/bkn/sinkron_cache",
                    type:"POST",
                    data: post_data,
                    dataType: "json",
                    timeout:180000,
                    success: function (result) {
                        if(result.success){
                         swal("Perhatian!", result.msg, "success");
                        }else{
                            swal("Perhatian!", result.msg, "error");
                        }
                },
                error : function(error) {
                    alert(error);
                } 
            });        
            
        } else {
            swal("Batal", "", "error");
        }
    });
});
</script>