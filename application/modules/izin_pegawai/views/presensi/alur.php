<?php
    $this->load->library('Convert');
    $convert = new Convert;
?>

<div class="tab-pane" id="<?php echo $TAB_ID;?>">
    <div class="row">
        <div class="col-md-12">
            <select id="filter_request_catalog_alur" class="select2" style="width:100%">
                <option value="">SILAHKAN PILIH</option>
                <?php
                if(isset($jenis_izin) && is_array($jenis_izin) && count($jenis_izin)):
                        foreach ($jenis_izin as $record) {
                    ?>
                        <option value="<?php echo $record->ID; ?>" <?php echo $id_jenis_izin == $record->ID ? "selected" : ""; ?>><?php echo $record->NAMA_IZIN; ?></option>
                    <?php 
                        }
                    endif;
                    ?>
                
            </select>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-12  form-content-alur">
        
        </div>
    </div>
    
</div>
<script>
$(".select2").select2();
$("#filter_request_catalog_alur").change(function(){
    var kode = $("#filter_request_catalog_alur").val();
    if(kode!=""){
        $(".form-content-alur").load("<?php echo base_url(); ?>admin/izin/izin_pegawai/viewalur/"+kode);    
    }else{
        $(".form-content-alur").html("");
    }
    
});
</script>