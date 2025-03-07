
<div class='box'>
    <div class="box-header">
        <div class="control-group col-sm-12">
            <label for="inputNAMA" class="control-label">IZIN</label>
            <div class='controls'>
                <select id="filter_request_catalog" class="select2" style="width:100%">
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
                <span class='help-inline'><?php echo form_error('DARI_TANGGAL'); ?></span>
            </div>
        </div> 
    </div>
</div>
<div class="form-content">
</div>
<script>
<?php 
if($id_jenis_izin != ""){
?>
    $(".form-content").load("<?php echo base_url(); ?>admin/izin/izin_pegawai/create/<?php echo $id_jenis_izin; ?>");    
<?php
}
?>
$(".select2").select2();
    $(document).ready(function(){
        $("#filter_request_catalog").change(function(){
            var kode = $("#filter_request_catalog").val();
            if(kode!=""){
                $(".form-content").load("<?php echo base_url(); ?>admin/izin/izin_pegawai/create/"+kode);    
            }else{
                $(".form-content").html("");
            }
            
        });
        
    });

</script>