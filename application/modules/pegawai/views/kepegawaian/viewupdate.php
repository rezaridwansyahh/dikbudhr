<script src="<?php echo base_url(); ?>themes/admin/js/jqueryvalidation/jquery.validate.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/daterangepicker/daterangepicker.css">
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/datepicker/datepicker3.css">
<?php
	$this->load->library('convert');
 	$convert = new convert();
?>
<div class="tab-pane active" id="<?php echo $TAB_ID;?>">
    <div class="callout callout-success">
       <h4>Perhatian</h4>
       <P>Halaman ini adalah halaman untuk memverifikasi perubahan data yang telah dilakukan mandiri oleh pegawai</P>
       <p>Silahkan checklist pada setiap kolom yang dirubah jika perubahan disetujui</p>
     </div>
	<form role="form" action="#" id="frmprofile">
	<input id='ID' type='hidden' class="form-control" name='ID' maxlength='25' value="<?php echo set_value('ID', isset($pegawai->ID) ? $pegawai->ID : ''); ?>" />
	<div class="box box-info">
            
            <div class="box-body">
            <fieldset>
            <div class="control-group col-sm-12">
                 <div class="row">
                     <div class="col-sm-4">
                         NAMA PEGAWAI
                     </div>
                     <div class="form-group col-sm-8">
                        <a href="<?php echo base_url(); ?>admin/kepegawaian/pegawai/profilen/"><b><?php echo isset($dataupdate->NAMA) ? $dataupdate->NAMA : '-'; ?></b></a>
                     </div>
                 </div>
             </div>
             <div class="control-group col-sm-12">
                 <div class="row">
                     <div class="col-sm-4">
                         <b> Perubahan Pada </b>
                     </div>
                     <div class="form-group col-sm-8">
                        
                     </div>
                 </div>
             </div>
             <div class="control-group col-sm-12">
                 <div class="row">
                     <div class="col-sm-4">
                         KOLOM
                     </div>
                     <div class="form-group col-sm-8">
                        <b><?php echo isset($dataupdate->NAMA_KOLOM) ? $dataupdate->NAMA_KOLOM : '-'; ?></b>
                     </div>
                 </div>
             </div>
			 <?php if(trim($dataupdate->NAMA_KOLOM) == "Tempat Lahir"){ ?>
			  <div class="control-group col-sm-12">
                 <div class="row">
                     <div class="col-sm-4">
                         DARI
                     </div>
                     <div class="form-group col-sm-8">
                     	<?php 
                     	$this->load->model('pegawai/lokasi_model');
                     	$lokasilahir = $this->lokasi_model->find(trim($dataupdate->DARI));
                     	?>
                        <b><?php echo isset($lokasilahir->NAMA) ? $lokasilahir->NAMA : '-'; ?></b>
                     </div>
                 </div>
             </div>
             <div class="control-group col-sm-12">
                 <div class="row">
                     <div class="col-sm-4">
                         MENJADI
                     </div>
                     <div class="form-group col-sm-8">
                     	<?php
                     	$lokasilahirupdate = $this->lokasi_model->find(trim($dataupdate->PERUBAHAN));
                     	?>
                        <b><?php echo isset($lokasilahirupdate->NAMA) ? $lokasilahirupdate->NAMA : '-'; ?></b>
                     </div>
                 </div>
             </div>
        <?php }elseif(trim($dataupdate->NAMA_KOLOM) == "AGAMA"){ ?>
                <?php
                $this->load->model('pegawai/agama_model');
                ?>
              <div class="control-group col-sm-12">
                 <div class="row">
                     <div class="col-sm-4">
                         DARI
                     </div>
                     <div class="form-group col-sm-8">
                        <?php 
                        $agamas = $this->agama_model->find((int)trim($dataupdate->DARI));
                        ?>
                        <b><?php echo isset($agamas->NAMA) ? $agamas->NAMA : '-'; ?></b>
                     </div>
                 </div>
             </div>
             <div class="control-group col-sm-12">
                 <div class="row">
                     <div class="col-sm-4">
                         MENJADI
                     </div>
                     <div class="form-group col-sm-8">
                        <?php
                        $agamas = $this->agama_model->find((int)trim($dataupdate->PERUBAHAN));
                        ?>
                        <b><?php echo isset($agamas->NAMA) ? $agamas->NAMA : '-'; ?></b>
                     </div>
                 </div>
             </div>
        <?php }elseif(trim($dataupdate->NAMA_KOLOM) == "TINGKAT PENDIDIKAN"){ ?>
                <?php
                $this->load->model('pegawai/tingkatpendidikan_model');
                ?>
              <div class="control-group col-sm-12">
                 <div class="row">
                     <div class="col-sm-4">
                         DARI
                     </div>
                     <div class="form-group col-sm-8">
                        <?php 
                        $tingkat_pendidikans = $this->tingkatpendidikan_model->find(trim($dataupdate->DARI));
                        ?>
                        <b><?php echo isset($tingkat_pendidikans->NAMA) ? $tingkat_pendidikans->NAMA : '-'; ?></b>
                     </div>
                 </div>
             </div>
             <div class="control-group col-sm-12">
                 <div class="row">
                     <div class="col-sm-4">
                         MENJADI
                     </div>
                     <div class="form-group col-sm-8">
                        <?php
                        $tingkat_pendidikans = $this->tingkatpendidikan_model->find(trim($dataupdate->PERUBAHAN));
                        ?>
                        <b><?php echo isset($tingkat_pendidikans->NAMA) ? $tingkat_pendidikans->NAMA : '-'; ?></b>
                     </div>
                 </div>
             </div>
        <?php }elseif(trim($dataupdate->NAMA_KOLOM) == "PENDIDIKAN"){ ?>
                <?php
                $this->load->model('pegawai/pendidikan_model');
                ?>
              <div class="control-group col-sm-12">
                 <div class="row">
                     <div class="col-sm-4">
                         DARI
                     </div>
                     <div class="form-group col-sm-8">
                        <?php 
                        $pendidikans = $this->pendidikan_model->find(trim($dataupdate->DARI));
                        ?>
                        <b><?php echo isset($pendidikans->NAMA) ? $pendidikans->NAMA : '-'; ?></b>
                     </div>
                 </div>
             </div>
             <div class="control-group col-sm-12">
                 <div class="row">
                     <div class="col-sm-4">
                         MENJADI
                     </div>
                     <div class="form-group col-sm-8">
                        <?php
                        $pendidikans = $this->pendidikan_model->find(trim($dataupdate->PERUBAHAN));
                        ?>
                        <b><?php echo isset($pendidikans->NAMA) ? $pendidikans->NAMA : '-'; ?></b>
                     </div>
                 </div>
             </div>
        <?php }elseif(trim($dataupdate->NAMA_KOLOM) == "RIWAYAT PENDIDIKAN"){ ?>
                <?php
                $this->load->model('pegawai/pendidikan_model');
                ?>
              <div class="control-group col-sm-12">
                 <div class="row">
                     <div class="col-sm-4">
                         DARI
                     </div>
                     <div class="form-group col-sm-8">
                        <?php 
                        $pendidikans = $this->pendidikan_model->find(trim($dataupdate->DARI));
                        ?>
                        <b><?php echo isset($pendidikans->NAMA) ? $pendidikans->NAMA : '-'; ?></b>
                     </div>
                 </div>
             </div>
             <div class="control-group col-sm-12">
                 <div class="row">
                     <div class="col-sm-4">
                         MENJADI
                     </div>
                     <div class="form-group col-sm-8">
                        <?php
                        $id_data = explode(",",$dataupdate->PERUBAHAN);
                        $id_data = $id_data[0];
                        $pendidikans = $this->pendidikan_model->find_by("ID",trim(str_replace("Pendidikan ID : ", "", $id_data)));
                        //print_r($dataupdate);
                        //echo trim($dataupdate->PERUBAHAN)." perubahan";
                        ?>
                        <b><?php echo isset($pendidikans->NAMA) ? $pendidikans->NAMA : '-'; ?></b>
                     </div>
                 </div>
             </div>
        <?php }elseif(trim($dataupdate->NAMA_KOLOM) == "JENIS_KAWIN_ID"){ ?>
                <?php
                $this->load->model('pegawai/jenis_kawin_model');
                ?>
              <div class="control-group col-sm-12">
                 <div class="row">
                     <div class="col-sm-4">
                         DARI
                     </div>
                     <div class="form-group col-sm-8">
                        <?php 
                        $pendidikans = $this->jenis_kawin_model->find(trim($dataupdate->DARI));
                        ?>
                        <b><?php echo isset($pendidikans->NAMA) ? $pendidikans->NAMA : '-'; ?></b>
                     </div>
                 </div>
             </div>
             <div class="control-group col-sm-12">
                 <div class="row">
                     <div class="col-sm-4">
                         MENJADI
                     </div>
                     <div class="form-group col-sm-8">
                        <?php
                        $id_data = explode(",",$dataupdate->PERUBAHAN);
                        $id_data = $id_data[0];
                        $pendidikans = $this->jenis_kawin_model->find_by("ID",trim(str_replace("Pendidikan ID : ", "", $id_data)));
                        //print_r($dataupdate);
                        //echo trim($dataupdate->PERUBAHAN)." perubahan";
                        ?>
                        <b><?php echo isset($pendidikans->NAMA) ? $pendidikans->NAMA : '-'; ?></b>
                     </div>
                 </div>
             </div>
        <?php }elseif(trim($dataupdate->NAMA_KOLOM) == "RIWAYAT KEPANGKATAN"){ ?>
                <?php
                $this->load->model('pegawai/golongan_model');
                ?>
              <div class="control-group col-sm-12">
                 <div class="row">
                     <div class="col-sm-4">
                         DARI
                     </div>
                     <div class="form-group col-sm-8">
                        <?php 
                        $pendidikans = $this->golongan_model->find(trim($dataupdate->DARI));
                        ?>
                        <b><?php echo isset($pendidikans->NAMA) ? $pendidikans->NAMA : '-'; ?></b>
                     </div>
                 </div>
             </div>
             <div class="control-group col-sm-12">
                 <div class="row">
                     <div class="col-sm-4">
                         MENJADI
                     </div>
                     <div class="form-group col-sm-8">
                        <?php
                        //echo $dataupdate->PERUBAHAN." update";
                        $id_data = explode(",",$dataupdate->PERUBAHAN);
                        //print_r($id_data);
                        //die();
                        $id_data = $id_data[0];
                        $pendidikans = $this->golongan_model->find_by("ID",trim(str_replace("ID : ", "", $id_data)));
                        //print_r($pendidikans);
                        //echo trim($dataupdate->PERUBAHAN)." perubahan";
                        ?>
                        <b><?php echo isset($pendidikans->NAMA_PANGKAT) ? $pendidikans->NAMA_PANGKAT." / ".$pendidikans->NAMA : '-'; ?></b>
                     </div>
                 </div>
             </div>
        <?php }elseif(trim($dataupdate->NAMA_KOLOM) == "GOLONGAN AWAL"){ ?>
                <?php
                $this->load->model('pegawai/golongan_model');
                ?>
              <div class="control-group col-sm-12">
                 <div class="row">
                     <div class="col-sm-4">
                         DARI
                     </div>
                     <div class="form-group col-sm-8">
                        <?php 
                        $pendidikans = $this->golongan_model->find((int)trim($dataupdate->DARI));
                        ?>
                        <b><?php echo isset($pendidikans->NAMA) ? $pendidikans->NAMA_PANGKAT." / ".$pendidikans->NAMA : '-'; ?></b>
                     </div>
                 </div>
             </div>
             <div class="control-group col-sm-12">
                 <div class="row">
                     <div class="col-sm-4">
                         MENJADI
                     </div>
                     <div class="form-group col-sm-8">
                        <?php
                        //echo $dataupdate->PERUBAHAN." update";
                        $id_data = explode(",",$dataupdate->PERUBAHAN);
                        //print_r($id_data);
                        //die();
                        $id_data = $id_data[0];
                        $pendidikans = $this->golongan_model->find_by("ID",(int)trim(str_replace("ID : ", "", $id_data)));
                        //print_r($pendidikans);
                        //echo trim($dataupdate->PERUBAHAN)." perubahan";
                        ?>
                        <b><?php echo isset($pendidikans->NAMA_PANGKAT) ? $pendidikans->NAMA_PANGKAT." / ".$pendidikans->NAMA : '-'; ?></b>
                     </div>
                 </div>
             </div>
        <?php }elseif(trim($dataupdate->NAMA_KOLOM) == "GOLONGAN"){ ?>
                <?php
                $this->load->model('pegawai/golongan_model');
                ?>
              <div class="control-group col-sm-12">
                 <div class="row">
                     <div class="col-sm-4">
                         DARI
                     </div>
                     <div class="form-group col-sm-8">
                        <?php 
                        $pendidikans = $this->golongan_model->find((int)trim($dataupdate->DARI));
                        ?>
                        <b><?php echo isset($pendidikans->NAMA) ? $pendidikans->NAMA_PANGKAT." / ".$pendidikans->NAMA : '-'; ?></b>
                     </div>
                 </div>
             </div>
             <div class="control-group col-sm-12">
                 <div class="row">
                     <div class="col-sm-4">
                         MENJADI
                     </div>
                     <div class="form-group col-sm-8">
                        <?php
                        //echo $dataupdate->PERUBAHAN." update";
                        $id_data = explode(",",$dataupdate->PERUBAHAN);
                        //print_r($id_data);
                        //die();
                        $id_data = $id_data[0];
                        $pendidikans = $this->golongan_model->find_by("ID",(int)trim(str_replace("ID : ", "", $id_data)));
                        //print_r($pendidikans);
                        //echo trim($dataupdate->PERUBAHAN)." perubahan";
                        ?>
                        <b><?php echo isset($pendidikans->NAMA_PANGKAT) ? $pendidikans->NAMA_PANGKAT." / ".$pendidikans->NAMA : '-'; ?></b>
                     </div>
                 </div>
             </div>
        <?php }else{ ?>

            <div class="control-group col-sm-12">
                 <div class="row">
                     <div class="col-sm-4">
                         DARI
                     </div>
                     <div class="form-group col-sm-8">
                        
                        <b><?php echo isset($dataupdate->DARI) ? $dataupdate->DARI : '-'; ?></b>
                     </div>
                 </div>
             </div>
             <div class="control-group col-sm-12">
                 <div class="row">
                     <div class="col-sm-4">
                         MENJADI
                     </div>
                     <div class="form-group col-sm-8">
                        <b><?php echo isset($dataupdate->PERUBAHAN) ? $dataupdate->PERUBAHAN : '-'; ?></b>
                     </div>
                 </div>
             </div>
         <?php } ?>
             <div class="control-group col-sm-12">
                 <div class="row">
                     <div class="col-sm-4">
                         TANGGAL PERUBAHAN
                     </div>
                     <div class="form-group col-sm-8">
                        <b><?php echo isset($dataupdate->UPDATE_TGL) ? $dataupdate->UPDATE_TGL : ''; ?></b>
                     </div>
                 </div>
             </div>
             <div class="control-group col-sm-12">
                 <div class="row">
                     <div class="col-sm-4">
                         LEVEL UPDATE
                     </div>
                     <div class="form-group col-sm-8">
                        <b><?php echo isset($dataupdate->LEVEL_UPDATE) ? $dataupdate->LEVEL_UPDATE : '-'; ?></b>
                     </div>
                 </div>
             </div>
             <div class="control-group col-sm-12">
                 <div class="row">
                     <div class="col-sm-4">
                         Status
                     </div>
                     <div class="form-group col-sm-4">
                        <select class="validate[required] text-input form-control" kode="<?php echo $dataupdate->ID; ?>" name="status_update" id="status_update" class="chosen-select-deselect">
                            <option value="">-- Pilih  --</option>
                            <option value="1" <?php if(isset($dataupdate->STATUS))  echo  ("1"==$dataupdate->STATUS) ? "selected" : ""; ?>> Diterima </option>
                            <option value="0" <?php if(isset($dataupdate->STATUS))  echo  ("0"==$dataupdate->STATUS) ? "selected" : ""; ?>> Ditolak </option>
                        </select>
                     </div>
                     <div class="form-group col-sm-4">
                     </div>
                 </div>
             </div>
			   
            </fieldset>

              <!-- /.table-responsive -->
             
            <!-- /.box-body -->
            
            <!-- /.box-footer -->
          </div>
    </div>
	  
    </form>
</div> 

<script>
    $('#status_update').change(function() {
        var valstatus_update = $('#status_update').val();
        var valkode         = $(this).attr("kode");
        var json_url = "<?php echo base_url() ?>admin/kepegawaian/pegawai/verifikasiupdatemandiri";
         $.ajax({    
            type: "post",
            url: json_url,
            data: "valstatus_update="+valstatus_update+"&kode="+valkode,
            dataType: "json",
            success: function(data){ 
                if(data.success){
                    swal("Pemberitahuan!", data.msg, "success");
                    $table.ajax.reload(null,false);
                }
                else {
                    $(".messages").empty().append(data.msg);
                }
            }});
        return false; 
    });
</script>
