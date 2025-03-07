<script src="<?php echo base_url(); ?>themes/admin/js/sweetalert.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/css/sweetalert.css">

<?php
$id = isset($rumusan->id) ? $rumusan->id : '';
?>
<div class='box box-primary'>
    <div class="box-body">
    <form id="frm">
		<input id='id_bidang' type='hidden' name='id_bidang' maxlength='10' value="<?php echo isset($id_bidang) ? $id_bidang : ''; ?>" />
        <input id='id_kabupaten' type='hidden' name='id_kabupaten' maxlength='10' value="<?php echo isset($rumusan->id_kabupaten) ? $rumusan->id_kabupaten : ''; ?>" />
		<input id='id_data' type='hidden' name='id_data' maxlength='10' value="<?php echo isset($rumusan->id) ? $rumusan->id : ''; ?>" />
            <div class="control-group col-sm-12">
                <label class="control-label">Bidang</label>
                <div class='controls'>
                    <?php echo isset($rumusan->nama_bidang) ? $rumusan->nama_bidang : ''; ?>
                </div>
            </div>
            <div class="control-group col-sm-12">
                <label class="control-label">Rumusan Masalah</label>
                <div class='controls'>
                    <?php echo isset($rumusan->permasalahan) ? $rumusan->permasalahan : ''; ?>
                </div>
            </div>
            <div class="control-group col-sm-12">
                <label class="control-label">Lokasi</label>
                <div class='controls'>
                    <?php echo isset($rumusan->lokasi) ? $rumusan->lokasi : ''; ?>
                </div>
            </div>
             <div class="control-group penyebab col-sm-6">
				<label class="control-label">No KTP</label>
				<div class='controls'>
					<input class="form-control" type='text' placeholder="silahkan isikan no ktp" id="no_ktp" name="no_ktp"  value="" />
				</div>
			</div>
			 <div class="control-group penyebab col-sm-6">
				<label class="control-label">Nama</label>
				<div class='controls'>
					<input class="form-control" type='text' placeholder="silahkan isikan nama anda" id="nama_pengusul" name="nama_pengusul"  value="" />
				</div>
			</div>
            <br>
            <div class="control-group penyebab col-sm-12">
				<label class="control-label">Usulan anda</label>
				<div class='controls'>
					<input class="form-control" type='text' placeholder="silahkan isikan usulan anda" name="usulan" id="usulan" value="" />
				</div>
			</div>
			 
        </div>
    <div class="box-footer">
        	<div class="btn btn-primary" id="submit_reset"><i class="fa fa-edit"></i> Kirim Usulan</div>
            
        </div>
    </form>
</div>
<script>
	var jumlahpenyebab = 1;
	$("#submit_reset").click(function(){
		submitdata();
		return false; 
	});	
	$("#frm").submit(function(){
		submitdata();
		return false; 
	});	
	function submitdata(){
		var valmasalah = $("#no_ktp").val();
		if(valmasalah == ""){
			$("#no_ktp").focus();	
		   	swal("Silahkan isi no KTP", "Warning");
		   	return false;
	   	}
	   	var valnama_pengusul = $("#nama_pengusul").val();
		if(valnama_pengusul == ""){
			$("#nama_pengusul").focus();	
		   	swal("Silahkan isi nama anda", "Warning");
		   	return false;
	   	}
	   	var valusulan = $("#usulan").val();
		if(valusulan == ""){
			$("#usulan").focus();	
		   	swal("Silahkan isi usulan anda", "Warning");
		   	return false;
	   	}
		var json_url = "<?php echo base_url() ?>publik/savedatausulanmasalah";
		 $.ajax({    
		 	type: "POST",
			url: json_url,
			data: $("#frm").serialize(),
			success: function(data){ 
				swal(data, "Warning");
				location.reload(true);
			}});
		return false; 
	}
	 
</script>
 