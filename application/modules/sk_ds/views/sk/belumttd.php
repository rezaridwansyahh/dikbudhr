<div class="tab-pane active" id="<?php echo $TAB_ID;?>">
	<?php echo form_open($this->uri->uri_string(),"id=form_search_pegawai","form"); ?>
			<style>
				table.filter_pegawai tr td {
					padding-top: 2px;
				}
			</style>
			<table class="filter_pegawai" sborder=0 width='100%' cellpadding="10">
				<tr>
					<td width="20px"><input type="checkbox" name="unit_id_cb"></td>
					<td width="200px"><label for="example-text-input" class="col-form-label">Satuan Kerja</label></td>
					<td colspan=2><select id="unit_id_keyblm" name="unit_id_key" width="100%" class=" col-md-10 format-control"></select></td>
				</tr>
				<tr>
					<td width="20px"><input type="checkbox" name="nama_cb"></td>
					<td width="200px"><label for="example-text-input" class="col-form-label">NAMA</label></td>
					<td colspan=2><input class="form-control" type="text" name="nama_key" value="" ></td>
				</tr>
				<tr>
					<td width="20px"><input type="checkbox" name="nip_cb"></td>
					<td width="200px"><label for="example-text-input" class="col-form-label">NIP</label></td>
					<td colspan=2><input class="form-control" type="text" name="nip_key" value="" ></td>
				</tr>
				<tr>
                    <td width="20px"><input type="checkbox" name="ch_nosk"></td>
                    <td width="200px"><label for="example-text-input" class="col-form-label">Nomor SK</label></td>
                    <td colspan=2><input class="form-control" type="text" name="nomor_sk" value="" ></td>
                </tr>
				<tr>
					<td width="20px"><input type="checkbox" name="golongan_cb"></td>
					<td width="200px"><label for="example-text-input" class="col-form-label">Golongan</label></td>
					<td colspan=2>
						<select name="golongan_key" class="form-control">
							<?php 
								foreach($golongans as $row){
									echo "<option value='".$row->ID."'>$row->NAMA_PANGKAT $row->NAMA</option>";
								}
							?>
						</select>
					</td>
				</tr>
				
				<tr>
					<td width="20px"><input type="checkbox" name="kategori_jb"></td>
					<td width="200px"><label for="example-text-input" class="col-form-label">Jabatan</label></td>
					<td colspan=2>
						<select name="kategori_jabatan" class="form-control select2">
                        <option value="">-- Silahkan Pilih --</option>
                        <option value="Pelaksana" <?php if(isset($jabatan->KATEGORI_JABATAN))  echo  (trim($jabatan->KATEGORI_JABATAN)=="Pelaksana") ? "selected" : ""; ?>>Pelaksana</option>
                        <option value="Administrator" <?php if(isset($jabatan->KATEGORI_JABATAN))  echo  (trim($jabatan->KATEGORI_JABATAN)=="Administrator") ? "selected" : ""; ?>>Administrator</option>
                        <option value="Fungsional" <?php if(isset($jabatan->KATEGORI_JABATAN))  echo  (trim($jabatan->KATEGORI_JABATAN)=="Fungsional") ? "selected" : ""; ?>>Fungsional</option>
                        <option value="Menteri" <?php if(isset($jabatan->KATEGORI_JABATAN))  echo  (trim($jabatan->KATEGORI_JABATAN)=="Menteri") ? "selected" : ""; ?>>Menteri</option>
                        <option value="JPT Madya" <?php if(isset($jabatan->KATEGORI_JABATAN))  echo  (trim($jabatan->KATEGORI_JABATAN)=="JPT Madya") ? "selected" : ""; ?>>JPT Madya</option>
                        <option value="Pengawas" <?php if(isset($jabatan->KATEGORI_JABATAN))  echo  (trim($jabatan->KATEGORI_JABATAN)=="Pengawas") ? "selected" : ""; ?>>Pengawas</option>
                        <option value="Staf Khusus" <?php if(isset($jabatan->KATEGORI_JABATAN))  echo  (trim($jabatan->KATEGORI_JABATAN)=="Staf Khusus") ? "selected" : ""; ?>>Staf Khusus</option>
                        <option value="JPT Pratama" <?php if(isset($jabatan->KATEGORI_JABATAN))  echo  (trim($jabatan->KATEGORI_JABATAN)=="JPT Pratama") ? "selected" : ""; ?>>JPT Pratama</option>
                    </select>
					</td>
				</tr>
				<tr>
                        <td width="20px"><input type="checkbox" name="kategori_cb"></td>
                        <td width="200px"><label for="example-text-input" class="col-form-label">Kategori SK</label></td>
                        <td colspan=2>
                            <select name="kategori_sk" id="kategori_skblm" class="form-control">
                                <option value="">-- Silahkan Pilih --</option>
                                <?php 
                                    foreach ($reckategori_ds as $record) {
                                ?>
                                        <option value='<?php echo $record->kategori_ds; ?>'><?php echo $record->kategori_ds; ?></option>
                                <?php
                                    }
                                ?>
                            </select>
                        </td>
                    </tr>
                
				<tr>
					<td colspan=4>
						<a class="btn btn-social btn-google pull-right" id="btn_sign">
			                <i class="fa fa-edit"></i> <span id="texttandatangan">Tanda Tangan SK</span>
			            </a>
			            &nbsp; &nbsp;
			            <button type="submit" class="btn btn-success pull-right "><i class="fa fa-search"></i> Cari</button>
						
					</td>
				</tr>
			</table>
		<?php
		echo form_close();    
		?>
	 
	<div class="box-body">
		<?php echo form_open($this->uri->uri_string(), 'id="frm-sign"'); ?> 
		<input type="hidden" name="username" value="<?php echo isset($pegawai_login->NIK) ? trim($pegawai_login->NIK) : ""; ?>">
		<input type="hidden" name="NIP" value="<?php echo isset($pegawai_login->NIP_BARU) ? trim($pegawai_login->NIP_BARU) : ""; ?>">
		<table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-data table-hover">
			<thead>
			<tr>
				<tr><th style="width:10px"><input type="checkbox" class="group-checkable"></th>
				<th width="15%">Pemilik SK</th>
				<th width="10%">Kategori SK</th>
				<th>No SK / Tgl SK</th>
				<th>Unit Kerja</th>
				<th width="70px" align="center">#</th></tr>
			</thead>
		</table>
		</form>
	</div>
 
<script type="text/javascript">
$("#kategori_skblm").select2();

$("#unit_id_keyblm").select2({
	placeholder: 'Cari Unit Kerja...',
	width: '100%',
	minimumInputLength: 5,
	allowClear: true,
	ajax: {
		url: '<?php echo site_url("pegawai/kepegawaian/ajax_unit_list");?>',
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
$table = $(".table-data").DataTable({
	
	dom : "<'row'<'col-sm-6'><'col-sm-6'>>" +
	"<'row'<'col-sm-12'tr>>" +
	"<'row'<'col-sm-2'l><'col-sm-3'i><'col-sm-7'p>>",
	processing: true,
	serverSide: true,
	stateSave: true,
	"columnDefs": [
					{"className": "text-center", "targets": [5]},
					{ "targets": [0,5], "orderable": false }
				],
	ajax: {
	  url: "<?php echo base_url() ?>admin/sk/sk_ds/getdata",
	  type:'POST',
	  "data": function ( d ) {
			d.search['advanced_search_filters']=  $("#form_search_pegawai").serializeArray();
		}
	}
});
$("#form_search_pegawai").submit(function(){
	$table.ajax.reload(null,true);
	return false;
});
 
 
$('.group-checkable').change(function() {
    var set = $('body').find('.table tbody > tr > td:nth-child(1) input[type="checkbox"]');
    var checked = $(this).prop("checked");
    $(set).each(function() {
        $(this).prop("checked", checked);
    });
    //$.uniform.update(set);
    var jumlahselected = $('.mycxk').filter(':checked').length;
    if(jumlahselected > 0)
    	$('#texttandatangan').html("Tanda Tangan "+jumlahselected+" SK");
   	else
   		$('#texttandatangan').html("Tanda Tangan SK");
});
$('body').on('click','.mycxk',function () { 
	var jumlahselected = $('.mycxk').filter(':checked').length;
    if(jumlahselected > 0)
    	$('#texttandatangan').html("Tanda Tangan "+jumlahselected+" SK");
   	else
   		$('#texttandatangan').html("Tanda Tangan SK");
});

function submitdata(pass){
	var the_data = $( "#frm-sign" ).serialize();
    var json_url = "<?php echo base_url() ?>admin/sk/sk_ds/tandatangansk_all/";
     $.ajax({    
      type: "POST",
      url: json_url,
      timeout:0,
      data: the_data+ '&passphrase=' + pass,
            dataType: "json",
            success: function(data){ 
            	//swal("Pemberitahuan!", JSON.stringify(data), "success");
                if(data.success){
                    swal("Pemberitahuan!", data.msg, "success");
                    location.reload();
                }
                else {
                	swal({
                        title: "Pemberitahuan!",
                        text: data.msg,
                        type: "error",
                        timer: 5000,
                        showConfirmButton: true
                    }, function () {
                        // location.reload();
                    });
                  // swal("Pemberitahuan!", data.msg, "error");    
                }
                
      		},
      error: function (xhr, ajaxOptions, thrownError) {
        //alert(thrownError);
        swal("Pemberitahuan!", thrownError, "error");
        //location.reload();
      }
  	});
    return false; 
  }
   
$( "#btn_sign" ).click(function() {
	var jumlahselected = $('.mycxk').filter(':checked').length;
	if(jumlahselected <= 0){
		swal("Perhatian", "Silahkan checklist dokumen yang akan ditanda tangan", "error");
		return false;
	}
        swal({
        title: "Tanda tangan " +jumlahselected+ " SK",
        //text: "Silahkan Masukan Pasphrase anda dengan NIK <?php echo isset($pegawai_login->NIK) ? $pegawai_login->NIK : ""; ?>",
        text: "Silahkan Masukan Pasphrase anda",
        type: "input",
        inputType: "password",
        showCancelButton: true,
        closeOnConfirm: false,
        animation: "slide-from-top",
        inputPlaceholder: "Pasphrase"
      },
      function(inputValue){
        if (inputValue === false) return false;

        if (inputValue === "") {
          swal.showInputError("Silahkan masukan Pasphrase!");
          return false
        }
        swal("Perhatian", "Tunggu sebentar, tandatangan digital sedang proses....", "info");
        submitdata(inputValue);
      });

    });
</script>
</div>