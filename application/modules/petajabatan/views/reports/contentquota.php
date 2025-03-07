<div class="control-group col-sm-12">
<label for="inputNAMA" class="control-label">FORMASI JABATAN</label>
<div class='controls'>
<div class="callout callout-success">
   <p>Dibawah ini adalah formasi jabatan pada unit kerja tujuan sesuai dengan jabatan yang dipilih </p>
</div>
    <table class="table table-datatable table-bordered">
      <thead>
          <tr>
              <th width='20px' >No</th>
              <th>Unit</th>
              <th>KELAS</th>
              <th width='100px' >BEZET</th>
              <th width='100px' >KBTHN</th>
              <th width='100px' >SELISIH</th>
          </tr>
      </thead>
      
      <tbody>
         <?php
         $nomor_urut=0;
         $selisih = 0;
         if(isset($kuotajabatan) && is_array($kuotajabatan) && count($kuotajabatan)):
          foreach ($kuotajabatan as $record) {
          	$countpegawai_jabatan = isset($akuota[trim($record->ID)]) ? $akuota[trim($record->ID)] : 0;
            ?>
            <tr>
                <td><?php echo $nomor_urut; ?></td>
                <td><?php echo $record->NAMA_UNOR; ?></td>
                <td align="center"><?php echo $record->KELAS; ?></td>
                <td align="center"><?php echo $countpegawai_jabatan; ?></td>
                <td align="center"><?php echo $record->JUMLAH_PEMANGKU_JABATAN; 
                  $JML_KUOTA = (int)$record->JUMLAH_PEMANGKU_JABATAN;
                  $selisih = $countpegawai_jabatan - $JML_KUOTA;
                  ?></td>
                <td valign="top" align="center" class="<?php echo $selisih<0 ? 'bg-blue' : ''; ?> <?php echo $selisih>=0 ? 'bg-red' : ''; ?>">
                <?php 
                  
                  echo $selisih; ?>
                  
                </td>
            </tr>
          <?php
            $nomor_urut++;
          }
        else:
        ?>
        <tfoot>
          <tr>
             <td colspan="5">
              Tidak ada data Kuota Jabatan untuk jabatan dan unitkerja yang anda pilih
             </td>     
          </tr>
      </tfoot>
        <?php
        endif;
        ?>
      </tbody>
  </table>  
</div>
</div> 
<script type="text/javascript">
<?php 
if($selisih <0 && $nomor_urut == 1){
?>
  $("#btnsave").attr('disabled', false);
<?php
}else{
?>
  $("#btnsave").attr('disabled', true);
<?php
}
?>
</script>