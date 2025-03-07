<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/select2/select2.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/css/dropzone/dropzone.min.css">
<script src="<?php echo base_url(); ?>themes/admin/js/dropzone/dropzone.min.js"></script>
<?php 
    $id = isset($jenis_izin->ID) ? $jenis_izin->ID : '';
?>
<div class='box box-warning' id="form-riwayat-assesmen-add">
    <div class="box-body">
        <div class="messages">
        </div>
    <?php echo form_open($this->uri->uri_string(), 'id="frm_pilihpejabat"'); ?>
        <fieldset>
            <div class="control-group<?php echo form_error('ATASAN') ? ' error' : ''; ?> col-lg-7">
                <?php echo form_label("Nama" . lang('bf_form_label_required'), 'ATASAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select name="ATASAN_TAMBAHAN[]" width="100%" class="form-control select2 ATASAN">
                        <?php
                        if($selectedAtasanLangsung){
                            echo "<option selected value='".$selectedAtasanLangsung->ATASAN."'>".$selectedAtasanLangsung->NAMA."</option>";
                        }
                        ?>
                    </select>
                    <span class='help-inline'><?php echo form_error('ATASAN'); ?></span>
                     
                </div>
            </div>
            <div class="control-group col-lg-2">
                <?php echo form_label("SEBAGAI" . lang('bf_form_label_required'), 'SEBAGAI', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select name="SEBAGAI[]" id="classsebagai" width="100%" class="form-control select2 form-control lazy-select2 classsebagai" required data-minimum=0 data-placeholder='Filter Status' data-url='<?php echo base_url('admin/masters/jenis_izin/list_pejabat');?>'>
                        
                    </select>
                    <span class='help-inline'><?php echo form_error('SEBAGAI'); ?></span>
                     
                </div>
            </div>
            <div class="control-group<?php echo form_error('ATASAN') ? ' error' : ''; ?> col-lg-3">
                <?php echo form_label("-", 'ATASAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                   
                    <a href="javascript:;" id="btnadd_atasan"  class="btn green btn-primary button-submit"> 
                        <i class="fa fa-plus"></i> 
                        Tambah Atasan
                    </a>
                </div>
            </div>
            <div class="divpejabattambahan">
            </div>
        </fieldset>
        </div>
        <div class="box-footer">
            <a href="javascript:;" id="btnsaveatasan"  class="btn green btn-primary button-submit"> 
                <i class="fa fa-save"></i> 
                 Simpan/Tetapkan Pejabat
            </a>
             
        </div>
    <?php echo form_close(); ?>
</div>
<script>
   var indexsebagai = 1; 
    $("#btnadd_atasan").click(function(){
        addatasan(indexsebagai);
        return false; 
    }); 
    function addatasan(){
        var strpejabat = '<div class="control-group col-lg-7">'+
                '<label for="ATASAN" class="control-label">Nama <span class="required">*</span></label><div class="controls">'+
                    '<select name="ATASAN_TAMBAHAN[]" width="100%" class="form-control select2 ATASAN">'+
                        
                    '</select>'+
                    '<span class="help-inline"></span>'+
                '</div>'+
            '</div>'+
            '<div class="control-group col-lg-2">'+
                '<label for="SEBAGAI" class="control-label">SEBAGAI <span class="required">*</span></label><div class="controls">'+
                '<div class="controls">'+
                    '<select name="SEBAGAI[]" width="100%" required class="form-control select2 form-control lazy-select2 classsebagai" id="'+indexsebagai+'" data-minimum=0 data-placeholder="Filter Status" data-url="<?php echo base_url("admin/masters/jenis_izin/list_pejabat");?>">'+
                        '<option value="">Silahkan Pilih</option>'+
                    '</select>'+
                '</div>'+
            '</div>';
        indexsebagai++;
        $('.divpejabattambahan').append(strpejabat);
        $(".ATASAN").select2({
        placeholder: 'Cari Pejabat.....',
        width: '100%',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: '<?php echo site_url("pegawai/kepegawaian/ajaxnip");?>',
            dataType: 'json',
            data: function(params) {
                return {
                    term: params.term || '',
                    page: params.page || 1
                }
            },
            cache: true
        }
    });
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
    }
    $("#btnsaveatasan").click(function(){
        var status_jabatan = formcheck();
        if(status_jabatan){
            setatasan();    
        }
        
        return false; 
    }); 
    $(".ATASAN").select2({
        placeholder: 'Cari Pejabat.....',
        width: '100%',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: '<?php echo site_url("pegawai/kepegawaian/ajaxnip");?>',
            dataType: 'json',
            data: function(params) {
                return {
                    term: params.term || '',
                    page: params.page || 1
                }
            },
            cache: true
        }
    });
    $("#PPK").select2({
        placeholder: 'Cari Pejabat.....',
        width: '100%',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: '<?php echo site_url("pegawai/kepegawaian/ajaxnip");?>',
            dataType: 'json',
            data: function(params) {
                return {
                    term: params.term || '',
                    page: params.page || 1
                }
            },
            cache: true
        }
    });
    function setatasan(){
        var data_pegawai = $("#frm-usulanpejabat").serialize();
        var valfrm_pilihpejabat = $("#frm_pilihpejabat").serialize();
        var post_data = data_pegawai+"&"+valfrm_pilihpejabat;
        //alert(post_data);
        var json_url = "<?php echo base_url() ?>admin/izin/izin_pegawai/savepejabat";
        $.ajax({    
            type: "POST",
            url: json_url,
            data: post_data,
            dataType: "json",
            success: function(data){ 
                if(data.success){
                    swal("Pemberitahuan!", data.msg, "success");
                    $table.ajax.reload(null,true);
                    $("#modal-global").modal("hide");
                }
                else {
                    swal("Pemberitahuan!", data.msg, "error");
                    $(".messages").empty().append(data.msg);
                }
            },
            error: function (e) {
                swal("Pemberitahuan!", "Ada kesalahan, sialhkan hubungi admin", "error");
                $(".messages").empty().append("Ada kesalahan, sialhkan hubungi admin");
                //console.log("ERROR : ", e);

            }
        });

        var NIP_ATASAN = $("#ATASAN").val();
        var NAMA_ATASAN = $("#ATASAN").text();
        var NIP_PPK = $("#PPK").val();
        var NAMA_PPK = $("#PPK").text();
        var VAL_KETERANGAN_TAMBAHAN = $("#KETERANGAN_TAMBAHAN").val();
        
        $("#divatasan").html("<b>Atasan</b> : "+NAMA_ATASAN);
        $("#txtatasan").val(NIP_ATASAN);
        $("#divppk").html("<b>PPK</b> : "+NAMA_PPK);
        $("#txtppk").val(NIP_PPK);
        $("#txtketerangan").val(VAL_KETERANGAN_TAMBAHAN);
        
        //$("#modal-global").modal("hide");
    }
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
function formcheck() {
    var val, str = "";
    var inputs = document.getElementsByClassName("classsebagai");
    for(var i=0; i<inputs.length; i++){
        val = inputs[i].value;
        if(val === "") {
            str = addError(str, "Silahkan Lengkapi jabatan", inputs[i]);
            alert(str);
            return false;
        }
    }
    
    return true;
}
function addError(str, msg, element) {
    str += "\u2022";
    if(element !== undefined && element !== null){
        var label = findLableForControl(element);
        if(label !== undefined && label !== null) {
            str += label.innerHTML;
            if(!str.endsWith(":")) str += ":";
        }
    }
    str += " "+msg+"\n"; //Change '\n' to '<br/>' if displaying error message in HTML
    return str;
}
function findLableForControl(el) {
    var idVal = el.id;
    var labels = document.getElementsByTagName('label');
    for( var i = 0; i < labels.length; i++ ) {
        if (labels[i].htmlFor == idVal) return labels[i];
    }
}
 
//Custom function used to select elements with classnames using regex
document['getElementsByClassNameWildcard'] = function(str){
   var arrElements = [];
 
   function findRecursively(aNode) {
      if (!aNode) 
          return;
      if (aNode.className !== undefined && aNode.className.indexOf(str) !== -1)
          arrElements.push(aNode);
      for (var idx in aNode.childNodes)
          findRecursively(aNode.childNodes[idx]);
   };
 
   findRecursively(document);
   return arrElements;
}; 
 
</script>