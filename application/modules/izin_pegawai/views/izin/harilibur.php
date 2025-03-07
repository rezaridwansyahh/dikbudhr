<?php
    $this->load->library('Convert');
    $convert = new Convert;
?>
<div class="tab-pane" id="<?php echo $TAB_ID;?>">
	<div class="callout callout-success">
        <h5>Informasi Hari libur tahun <?php echo date("Y"); ?></h5>
      </div>
   <?php
    if(isset($record_hari_libur_tahunan) && is_array($record_hari_libur_tahunan) && count($record_hari_libur_tahunan)):
        $index = 1;
        foreach ($record_hari_libur_tahunan as $record) {
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
    <div class="callout callout-danger">
	    <h5>Informasi!</h5>

	    <p>Tidak ada data hari libur pada tahun ini</p>
	  </div>
	 
    <?php
    endif;
    ?> 
</div>