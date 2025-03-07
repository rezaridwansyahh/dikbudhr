<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/select2/select2.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/css/dropzone/dropzone.min.css">
<script src="<?php echo base_url(); ?>themes/admin/js/dropzone/dropzone.min.js"></script>
<?php 
    $id = isset($jenis_izin->ID) ? $jenis_izin->ID : '';
    $this->load->helper('dikbud');
?>
<div class='box box-warning' id="form-riwayat-assesmen-add">
    <div class="box-body">
        <div class="messages">
        </div>
    <?php echo form_open($this->uri->uri_string(), 'id="frm"'); ?>
        <fieldset>
            

            <div class="control-group<?php echo form_error('KODE') ? ' error' : ''; ?>">
                <?php echo form_label(lang('jenis_izin_field_KODE') . lang('bf_form_label_required'), 'KODE', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='ID' type='hidden' name='ID' maxlength='' value="<?php echo set_value('ID', isset($jenis_izin->ID) ? $jenis_izin->ID : ''); ?>" />
                    <input id='KODE' type='text' class="form-control" required='required' name='KODE' maxlength='8' value="<?php echo set_value('KODE', isset($jenis_izin->KODE) ? $jenis_izin->KODE : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('KODE'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('NAMA_IZIN') ? ' error' : ''; ?>">
                <?php echo form_label(lang('jenis_izin_field_NAMA_IZIN') . lang('bf_form_label_required'), 'NAMA_IZIN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='NAMA_IZIN' type='text' class="form-control" required='required' name='NAMA_IZIN' maxlength='50' value="<?php echo set_value('NAMA_IZIN', isset($jenis_izin->NAMA_IZIN) ? trim($jenis_izin->NAMA_IZIN) : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('NAMA_IZIN'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('KETERANGAN') ? ' error' : ''; ?>">
                <?php echo form_label(lang('jenis_izin_field_KETERANGAN'), 'KETERANGAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <textarea id='KETERANGAN' class="form-control" name='KETERANGAN'><?php echo set_value('KETERANGAN', isset($jenis_izin->KETERANGAN) ? trim($jenis_izin->KETERANGAN) : ''); ?></textarea>
                    <span class='help-inline'><?php echo form_error('KETERANGAN'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('URUTAN') ? ' error' : ''; ?>">
                <?php echo form_label("URUTAN", 'URUTAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='URUTAN' type='number' class="form-control" required='required' name='URUTAN' maxlength='50' value="<?php echo set_value('URUTAN', isset($jenis_izin->URUTAN) ? trim($jenis_izin->URUTAN) : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('URUTAN'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('PERSETUJUAN') ? ' error' : ''; ?>">
                <?php echo form_label("Persetujuan", 'PERSETUJUAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select id="filter_status" class="select2 form-control lazy-select2" multiple name="PERSETUJUAN[]" data-minimum=0 data-placeholder='Filter Status' data-url='<?php echo base_url('admin/masters/jenis_izin/list_pejabat');?>'>
                        <option value=''>--Semua--</option>
                        <?php
                            if($jenis_izin->PERSETUJUAN != ""){

                                foreach(json_decode($jenis_izin->PERSETUJUAN) as $values)
                                 {
                                ?>
                                      <option value='<?php echo $values; ?>' selected="selected"><?php echo get_pejabat_cuti($values); ?></option>
                                 <?php
                                 }
                            }
                            ?>
                    </select>
                    <span class='help-inline'><?php echo form_error('PERSETUJUAN'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('STATUS') ? ' error' : ''; ?>">
                <?php echo form_label("Status", 'STATUS', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select id="STATUS" class="form-control" name="STATUS">
                        <option value=''>--Semua--</option>
                        <option value='1' <?php echo isset($jenis_izin->STATUS) == "1" ? "selected" : ""; ?>>Aktif</option>
                        <option value='2' <?php echo isset($jenis_izin->STATUS) == "2" ? "selected" : ""; ?>>Tidak Aktif</option>
                    </select>
                    <span class='help-inline'><?php echo form_error('PERSETUJUAN'); ?></span>
                </div>
            </div>
        </div>
        <div class="box-footer">
            <a href="javascript:;" id="btnsave"  class="btn green btn-primary button-submit"> 
                <i class="fa fa-save"></i> 
                Simpan
            </a>
             
        </div>
    <?php echo form_close(); ?>
</div>
<script src="<?php echo base_url(); ?>themes/admin/plugins/select2/select2.full.min.js"></script>
<script>
    $("#btnsave").click(function(){
        submitdata();
        return false; 
    }); 
    function submitdata(){
        
        var json_url = "<?php echo base_url() ?>admin/masters/jenis_izin/save";
         $.ajax({    
            type: "POST",
            url: json_url,
            data: $("#frm").serialize(),
            dataType: "json",
            success: function(data){ 
                if(data.success){
                    swal("Pemberitahuan!", data.msg, "success");
                    $("#modal-global").modal("hide");
                    grid_daftar.ajax.reload();
                }
                else {
                    $(".messages").empty().append(data.msg);
                }
            }});
        return false; 
    }
</script>
<script>
     $(".lazy-select2").each(function(i,o){
        $(this).select2(
            {
                placeholder: $(this).data('placeholder'),
                width: '100%',
                minimumInputLength: $(this).data('minimum'),
                allowClear: true,
                ajax: {
                    url: $(this).data('url'),
                    dataType: 'json',
                    data: function(params) {
                        return {
                            term: params.term || '',
                            page: params.page || 1
                        }
                    },
                    cache: true
                }
            }
        );
    });
$(".lazy-select2").on("select2:select", function (evt) {
  var element = evt.params.data.element;
  var $element = $(element);
  
  $element.detach();
  $(this).append($element);
  $(this).trigger("change");
});
</script>