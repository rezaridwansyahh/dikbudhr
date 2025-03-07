<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/select2/select2.min.css">
<!-- daterange picker -->
<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/daterangepicker/daterangepicker.css">
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/datepicker/datepicker3.css">
<div class="callout callout-info">
   <h4>Peta Jabatan!</h4>

   <p>Silahkan cari Unitkerja untuk melihat peta jabatan pada unit tersebut.</p>
 </div>
<div class="admin-box box box-primary">
	<div class="box-header">
        <?php echo form_open($this->uri->uri_string(),"id=form_search_pegawai","form"); ?>
            <style>
                table.filter_pegawai tr td {
                    padding-top: 2px;
                }
            </style>
            <table class="filter_pegawai" sborder=0 width='100%' cellpadding="10">
                <tr>
                    <td width="200px"><label for="example-text-input" class="col-form-label">Unit Kerja</label></td>
                    <td colspan=2>
                        <select name="unitkerja" id="Unit_Kerja_ID" class="form-control select2" style="width:700px">
                            <?php 
                            if($unit_kerja){
                                echo "<option selected value='".$unit_kerja->ID."'>".$unit_kerja->NAMA_UNOR."</option>";
                            }
                        ?>
                        </select>
                    </td>
                </tr>
                 
                <tr>
                    <td colspan=4>
                        <button type="button" class="btn btn-success pull-right btn_cari"><i class="fa fa-search"></i> Cari</button>
                        <a class="btn btn-small btn-warning pull-right download_xls" title="Download excell" href="#" tooltip="download excell"><i class="fa fa-download"> </i>  Download excell</a>
                    </td>
                </tr>
            </table>
        <?php
        echo form_close();    
        ?>
	</div>
	<div class="box-body" id="divcontent">
	
	</div>
</div>
<script>
showdata();
    $(".btn_cari").click(function(){
        showdata();
     }); 
	$("#Unit_Kerja_ID").change(function(){
		showdata();
	 }); 
    $("#Unit_Kerja_ID").select2({
        placeholder: 'Cari Unit Kerja...',
        width: '100%',
        allowClear: true,
        ajax: {
            url: '<?php echo site_url("pegawai/manage_unitkerja/ajaxkodeinternal");?>',
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
    function showdata(){
    	 var ValUnit_Kerja_ID = $("#Unit_Kerja_ID").val();
		 var json_url = "<?php echo base_url() ?>admin/reports/petajabatan/viewdata";
		 $.ajax({    type: "GET",
			url: json_url,
			data: "unitkerja="+ValUnit_Kerja_ID,
			success: function(data){ 
				$('#divcontent').html(data);
			}});
		 return false; 
	  return false;
    }
$(".download_xls").click(function(){
    var xyz = $("#form_search_pegawai").serialize();
    window.open("<?php echo base_url('admin/reports/petajabatan/downloadexcell');?>?"+xyz);
});
</script>